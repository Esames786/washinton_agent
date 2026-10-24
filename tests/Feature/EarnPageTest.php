<?php

namespace Tests\Feature;

use Tests\TestCase;

/** Earn With Us (client, 24 Oct 2026): every "Why Work With Us" card leads to the signup page. */
class EarnPageTest extends TestCase
{
    public function test_every_benefit_card_links_to_signup(): void
    {
        $html = $this->get('/earn-with-hello-transport')->assertOk()->getContent();

        $this->assertSame(6, substr_count($html, '<a href="' . url('/register') . '" class="ht-benefit-link"'));
    }
}
