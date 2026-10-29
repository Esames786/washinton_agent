<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Privacy Policy and Terms (client, 28 Oct 2026): the website pages and the terms page the booking
 * emails link to show the client's new text, without the draft's notes to the reviewer.
 */
class LegalPagesTest extends TestCase
{
    private const DRAFT_NOTES = ['before publication', 'existing policy', 'existing terms', 'existing fee schedule',
        'current policy specifies', 'current fee schedule', 'legal counsel', 'Superior Court'];

    public function test_terms_pages_show_the_new_terms(): void
    {
        foreach (['/terms', '/terms-conditions'] as $path) {
            $html = $this->get($path)->assertOk()->getContent();

            foreach (['1. Agreement to These Terms', '17. Order Cancellation and Refunds', '28. Ownership and Required Vehicle Documents',
                '$99 cancellation fee', '$199 cancellation fee', 'Baltimore County, Maryland', 'We do not sell or rent your personal information',
                'USDOT No. 4572171 and MC No. 1818817'] as $text) {
                $this->assertStringContainsString(e($text), $html, "$path is missing: $text");
            }
            foreach (self::DRAFT_NOTES as $note) {
                $this->assertStringNotContainsStringIgnoringCase($note, $html, "$path still says: $note");
            }
            $this->assertStringNotContainsString('No claims or legal actions can be initiated', $html, "$path still shows the old terms");
        }
    }

    public function test_privacy_page_shows_the_new_policy(): void
    {
        $html = $this->get('/Privacy-Policy')->assertOk()->getContent();

        foreach (['1. Introduction', '10. Data Retention', '12. Third-Party Websites and Contact Information',
            'Text messaging opt-in data and consent will not be shared with third parties', 'We do not sell or rent your personal information'] as $text) {
            $this->assertStringContainsString(e($text), $html, "Privacy page is missing: $text");
        }
        $this->assertStringNotContainsString('Welcome to the Privacy Policy', $html, 'Privacy page still shows the old policy');
    }
}
