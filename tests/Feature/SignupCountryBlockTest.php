<?php

namespace Tests\Feature;

use App\Http\Middleware\BlockSignupCountries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Stevebauman\Location\Facades\Location;
use Tests\TestCase;

/**
 * Client, 23 Nov 2026: block Pakistan IPs from the Hello Transport signup. The country lookup is
 * faked; nothing reaches a real geo service.
 */
class SignupCountryBlockTest extends TestCase
{
    private const PK_IP = '39.32.10.20';
    private const US_IP = '66.102.0.10';

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        config(['signup.blocked_countries' => ['PK'], 'signup.allow_ips' => [], 'signup.blocked_redirect' => null]);
    }

    private function countryIs(?string $code): void
    {
        Location::shouldReceive('get')->andReturn($code === null ? false : (object) ['countryCode' => $code]);
    }

    private function through(string $ip, string $method = 'GET')
    {
        $request = Request::create('/register', $method, [], [], [], ['REMOTE_ADDR' => $ip]);

        return (new BlockSignupCountries())->handle($request, fn () => response('the form'));
    }

    public function test_pakistan_visitor_gets_the_notice_page_instead_of_the_form(): void
    {
        $this->countryIs('PK');

        $this->withServerVariables(['REMOTE_ADDR' => self::PK_IP])->get('/register')
            ->assertStatus(403)
            ->assertSee('Applications are not available in your region')
            ->assertDontSee('Apply to Join the Network');
    }

    public function test_pakistan_submission_is_refused_with_a_message_the_form_shows(): void
    {
        $this->countryIs('PK');

        $response = $this->through(self::PK_IP, 'POST');

        $this->assertSame(403, $response->getStatusCode());
        $this->assertSame(BlockSignupCountries::MESSAGE, $response->getData(true)['message']);
    }

    public function test_us_visitor_reaches_the_form(): void
    {
        $this->countryIs('US');

        $this->assertSame('the form', $this->through(self::US_IP)->getContent());
        $this->assertSame('the form', $this->through(self::US_IP, 'POST')->getContent());
    }

    public function test_office_ip_on_the_allow_list_still_reaches_the_form(): void
    {
        $this->countryIs('PK');
        config(['signup.allow_ips' => ['203.0.113.7', '39.32.0.0/16']]);

        $this->assertSame('the form', $this->through(self::PK_IP)->getContent());
    }

    public function test_failed_lookup_lets_the_visitor_through(): void
    {
        $this->countryIs(null);

        $this->assertSame('the form', $this->through(self::PK_IP)->getContent());
    }

    public function test_country_is_looked_up_once_per_ip(): void
    {
        Location::shouldReceive('get')->once()->andReturn((object) ['countryCode' => 'pk']);

        $this->assertSame(403, $this->through(self::PK_IP)->getStatusCode());
        $this->assertSame(403, $this->through(self::PK_IP, 'POST')->getStatusCode());
    }

    public function test_optional_redirect_instead_of_the_notice(): void
    {
        $this->countryIs('PK');
        config(['signup.blocked_redirect' => 'https://example.test/apply']);

        $this->assertSame('https://example.test/apply', $this->through(self::PK_IP)->headers->get('Location'));
    }

    public function test_both_signup_routes_carry_the_check(): void
    {
        foreach (['register', 'register.store'] as $name) {
            $this->assertContains('signup.country', app('router')->getRoutes()->getByName($name)->middleware(), $name);
        }
    }
}
