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
    public static function for(?User $user): array
    {
        // Deployment-level override: on the CrazyRays portal (florida) PORTAL_BRAND=crazyrays
        // forces CR branding for EVERYONE — including guests on the login page — so no Hello
        // branding ever shows on that domain, regardless of the logged-in user.
        $force = config('brands.force');
        if ($force) {
            return self::byKey($force);
        }

        $key = ($user && $user->isCrazyrays()) ? 'crazyrays' : config('brands.default', 'hellotransport');

        return self::byKey($key);
    }

    /**
     * Resolve the brand for the currently authenticated user.
     */
    public static function current(): array
    {
        return self::for(Auth::user());
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
        return (($brand['key'] ?? '') === 'crazyrays') ? 'crazyrays' : 'smtp';
    }

    /**
     * Authenticated "From" identity [address, name] to pair with mailer().
     */
    public static function mailFrom(array $brand): array
    {
        if (($brand['key'] ?? '') === 'crazyrays') {
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

        // Legacy literals (longest first so "LLC" variant is handled before the short name)
        $html = str_ireplace('Hello Transport LLC', $legal, $html);
        $html = str_ireplace('Hello Transport', $name, $html);

        return $html;
    }
}
