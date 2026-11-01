<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Tests\TestCase;

class TrustHostsTest extends TestCase
{
    protected function tearDown(): void
    {
        // setTrustedHosts() is static: leave no restriction behind for other tests.
        Request::setTrustedHosts([]);

        parent::tearDown();
    }

    private function actAsProduction(): void
    {
        config(['app.url' => 'https://larablog.example']);
        $this->app['env'] = 'production';
    }

    public function test_a_request_for_the_application_host_is_served(): void
    {
        $this->actAsProduction();

        $this->get('https://larablog.example/')->assertOk();
    }

    public function test_a_forged_host_header_is_refused(): void
    {
        $this->actAsProduction();

        $response = $this->get('https://evil.example/');

        $this->assertGreaterThanOrEqual(400, $response->getStatusCode());
        $this->assertStringNotContainsString('evil.example', (string) $response->headers->get('Location'));
    }
}
