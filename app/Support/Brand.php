<?php

namespace App\Support;

use App\User;
use Illuminate\Support\Facades\Auth;

/**
 * Central per-user branding resolver.
 *
 * CrazyRays-originated users (user.is_crazyrays = 1) get the "crazyrays"
 * brand; everyone else gets the default brand. Use this everywhere a brand
 * name / email / footer / contract company name is rendered so branding is
 * consistent across screens, emails, and redirects.
 */
class Brand
{
    /**
     * Resolve the brand array for a given user (or the default brand if null).
     *
     * @return array{key:string,name:string,legal:string,site:string,email:string,phone:string,footer:string}
     */
    /**
     * Brand OF A PERSON — use for anything that is *about* a specific user: their NDA, their
     * contract, and every email addressed to them (OTP, activation, action-required).
     *
     * The person's own origin wins here and PORTAL_BRAND does NOT override it, because a Hello
     * agent administered from the CrazyRays portal must still receive Hello-worded documents and
     * Hello-branded email — the document belongs to them, not to the screen it was created on.
     * With no identified user (public/guest context) we fall back to the deployment brand.
     */
    public static function for(?User $user): array
    {
        if ($user) {
            return self::byKey($user->isCrazyrays() ? 'crazyrays' : config('brands.default', 'hellotransport'));
        }

        $force = config('brands.force');

        return self::byKey($force ?: config('brands.default', 'hellotransport'));
    }

    /**
     * Brand OF THE DOMAIN — use for portal chrome (logo, navbar, footer, page titles).
     *
     * PORTAL_BRAND=crazyrays makes the whole florida domain render CrazyRays for everyone,
     * including guests on the login page, so no Hello branding leaks onto that domain. On the
     * Hello domains PORTAL_BRAND is unset, so the visitor's own brand applies.
     */
    public static function current(): array
    {
        // 1. Explicit deployment brand wins (PORTAL_BRAND).
        $force = config('brands.force');
        if ($force) {
            return self::byKey($force);
        }

        // 2. Otherwise infer it from the HOST being visited, so each domain brands itself even
        //    when PORTAL_BRAND was never set: florida.crazyrayssolutions.com.pk → CrazyRays,
        //    hellotransport.com → Hello. This is what makes the branding follow the domain.
        $host = self::hostBrandKey();
        if ($host) {
            return self::byKey($host);
        }

        // 3. Fall back to the signed-in person's own brand.
        return self::for(Auth::user());
    }

    /**
     * Is this deployment a CrazyRays one? True when PORTAL_BRAND says so, or when the request
     * is on a crazyrays host. Used for mail routing: only a CrazyRays deployment may send
     * through the CrazyRays mailbox — a Hello domain has no such mailbox to authenticate with.
     */
    public static function isCrazyraysDeployment(): bool
    {
        if (config('brands.force') === 'crazyrays') {
            return true;
        }

        // A different explicit PORTAL_BRAND means "definitely not CrazyRays".
        if (config('brands.force')) {
            return false;
        }

        return self::hostBrandKey() === 'crazyrays';
    }

    /**
     * Brand implied by the current request's hostname, or null when it matches neither domain
     * (e.g. a local/staging host) so the caller can fall back.
     */
    public static function hostBrandKey(): ?string
    {
        try {
            $host = strtolower((string) request()->getHost());
        } catch (\Throwable $e) {
            return null;   // console/queue context — no request
        }

        if ($host === '') {
            return null;
        }

        if (str_contains($host, 'crazyrays')) {
            return 'crazyrays';
        }

        if (str_contains($host, 'hellotransport')) {
            return 'hellotransport';
        }

        return null;
    }

    /**
     * Resolve a brand by its explicit key ('crazyrays' | 'hellotransport').
     */
    public static function byKey(string $key): array
    {
        $brands  = config('brands.brands', []);
        $default = config('brands.default', 'hellotransport');

        $data = $brands[$key] ?? $brands[$default] ?? [];

        return array_merge(['key' => isset($brands[$key]) ? $key : $default], $data);
    }

    /**
     * Convenience: resolve a brand from a boolean CrazyRays flag.
     */
    public static function fromFlag(bool $isCrazyrays): array
    {
        return self::byKey($isCrazyrays ? 'crazyrays' : config('brands.default', 'hellotransport'));
    }

    /**
     * Name of the configured mailer this brand's outgoing mail should use.
     * CrazyRays mail goes through the dedicated 'crazyrays' SMTP mailer (careers@…),
     * everything else through the default 'smtp' mailer (Hello support@…). This keeps
     * SPF/DKIM valid after the default mailer was pointed at the Hello domain.
     */
    public static function mailer(array $brand): string
    {
        // On the Hello domains EVERY outgoing email is a Hello email — customer mail, OTP,
        // activation, NDA/contract notices. Those deployments have no CrazyRays mailbox to
        // authenticate with, so never route through the CR mailer there.
        if (!self::isCrazyraysDeployment()) {
            return 'smtp';
        }

        return (($brand['key'] ?? '') === 'crazyrays') ? 'crazyrays' : 'smtp';
    }

    /**
     * Authenticated "From" identity [address, name] to pair with mailer().
     */
    public static function mailFrom(array $brand): array
    {
        // Paired with mailer(): on a Hello domain the From address must be the Hello mailbox,
        // otherwise the message would claim a CrazyRays sender the server can't authenticate.
        if (($brand['key'] ?? '') === 'crazyrays' && self::isCrazyraysDeployment()) {
            return [
                config('mail.crazyrays_from.address', 'careers@crazyrayssolutions.com.pk'),
                config('mail.crazyrays_from.name', $brand['name'] ?? 'Crazy Rays Solutions'),
            ];
        }

        return [
            config('mail.from.address', 'support@hellotransport.com'),
            config('mail.from.name', $brand['name'] ?? 'Hello Transport'),
        ];
    }

    /**
     * Rebrand a block of HTML/text (e.g. a contract or T&C) for the given brand.
     *
     * Supports explicit placeholders ({{COMPANY_NAME}}, {{COMPANY_LEGAL}}) and
     * also rewrites the legacy hard-coded "Hello Transport LLC" / "Hello Transport"
     * literals so existing stored templates pick up the brand without manual edits.
     */
    public static function applyTokens(string $html, array $brand): string
    {
        $name  = $brand['name']  ?? 'Hello Transport';
        $legal = $brand['legal'] ?? 'Hello Transport LLC';

        $replacements = [
            '{{COMPANY_NAME}}'   => $name,
            '{{COMPANY_LEGAL}}'  => $legal,
            '{{COMPANY_SITE}}'   => $brand['site']  ?? '',
            '{{COMPANY_EMAIL}}'  => $brand['email'] ?? '',
            '{{COMPANY_PHONE}}'  => $brand['phone'] ?? '',
        ];
        $html = strtr($html, $replacements);

        // Legacy literals — replaced in BOTH directions so a block of text stored under one brand
        // re-brands correctly when rendered for the other (e.g. a contract saved while the CrazyRays
        // portal was forced, later shown to a Hello agent). Longest match first so the "LLC" /
        // "Solutions" variants are handled before the short names.
        // Matched in ONE case-insensitive pass (longest alternative first) so replaced text is never
        // re-scanned — a sequential str_ireplace would turn "Crazy Rays Solutions" into
        // "Crazy Rays Solutions Solutions" when the target brand is CrazyRays.
        $literals = [
            'hello transport llc'  => $legal,
            'crazy rays solutions' => $legal,
            'crazyrays solutions'  => $legal,
            'hello transport'      => $name,
            'crazy rays'           => $name,
            'crazyrays'            => $name,
        ];

        $pattern = '/' . implode('|', array_map(
            static fn ($needle) => preg_quote($needle, '/'),
            array_keys($literals)
        )) . '/i';

        $replaced = preg_replace_callback(
            $pattern,
            static fn ($m) => $literals[strtolower($m[0])] ?? $m[0],
            $html
        );

        // preg_* returns null on failure (e.g. backtrack limit) — never destroy the content.
        return $replaced ?? $html;
    }
}
