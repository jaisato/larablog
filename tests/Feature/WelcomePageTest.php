<?php

namespace Tests\Feature;

use Illuminate\Foundation\Application;
use Tests\TestCase;

class WelcomePageTest extends TestCase
{
    public function test_the_welcome_page_does_not_disclose_the_framework_or_php_version(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertDontSee('Laravel v'.Application::VERSION)
            ->assertDontSee('PHP v'.PHP_VERSION);
    }
}
