<?php

namespace Tests\Feature;

use Tests\TestCase;

class TrustedProxyTest extends TestCase
{
    public function test_assets_use_forwarded_https_scheme_behind_a_proxy(): void
    {
        $response = $this
            ->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->withHeaders([
                'Host' => 'cazeer-example.ngrok-free.app',
                'X-Forwarded-Host' => 'cazeer-example.ngrok-free.app',
                'X-Forwarded-Proto' => 'https',
                'X-Forwarded-Port' => '443',
            ])
            ->get('/login');

        $response
            ->assertOk()
            ->assertSee('https://cazeer-example.ngrok-free.app/assets/css/style.css', false)
            ->assertDontSee('http://cazeer-example.ngrok-free.app/assets/', false);
    }
}
