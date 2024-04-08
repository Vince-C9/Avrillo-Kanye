<?php

namespace Tests\Feature\Auth\ApiApps;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\App as APIApp;

class ApiAppTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * It generates a new app and assigns it a unique access token
     * 
     * @test
     * @group ApiApp
     */

     public function it_generates_a_new_api_app_and_assigns_it_an_access_token(){
        $apiApp = APIApp::generate();
        $appKey = $apiApp->app_access_id;
        $token = $apiApp->accessToken->first()->secret_token;

        $this->assertNotNull($token);
        $this->assertNotNull($appKey);
     }

     
}
