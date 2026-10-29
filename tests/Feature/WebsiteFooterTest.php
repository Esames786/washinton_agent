<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Website footer (client, 29 Oct 2026): like ShipA1's, the left block shows the address, email,
 * USDOT/MC numbers, social icons and the BBB / Google Reviews / Trustpilot logos.
 */
class WebsiteFooterTest extends TestCase
{
    public function test_footer_shows_company_details_and_review_logos(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        foreach (['6340 Security Blvd Ste 100, Baltimore, MD 21207', 'mailto:info@hellotransport.com',
            'USDOT No. 4572171 | MC No. 1818817', 'Follow Us on Social Media'] as $text) {
            $this->assertStringContainsString($text, $html, "Footer is missing: $text");
        }
        foreach (['bbb.webp', 'google-reviews.webp', 'trustpilot.webp'] as $logo) {
            $this->assertStringContainsString('frontend/img/badges/' . $logo, $html);
            $this->assertFileExists(public_path('frontend/img/badges/' . $logo));
        }
        // Links not supplied yet: no dead "#" links that open a blank tab.
        $this->assertStringNotContainsString('<a href="#" target="_blank"><i class="bx bxl-', $html);
    }
}
