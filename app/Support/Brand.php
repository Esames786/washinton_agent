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
