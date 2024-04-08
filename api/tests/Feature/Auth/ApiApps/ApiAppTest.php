<?php

namespace Tests\Feature\Auth\ApiApps;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\App as APIApp;

use function PHPUnit\Framework\assertCount;

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
     * Could be improved by checking for more than 'not null', eg
     * string length, data type, data structure etc.
     * 
     * @test
     * @group ApiApp
     */

     public function it_generates_a_new_api_app_and_assigns_it_an_access_token(){
        $apiApp = APIApp::generate();
        $appKey = $apiApp->app_access_id;
        $token = $apiApp->accessToken->first()->secret_token;

        $this->assertTrue(APIApp::count() === 1);

        $this->assertNotNull($token);
        $this->assertNotNull($appKey);
     }

     /**
     * It generates a new app and assigns it a unique access token using console command
     * 
     * Could be improved by checking for more than 'not null', eg
     * string length, data type, data structure etc.
     * 
     * @test
     * @group ApiApp
     */
     public function it_can_generate_a_new_app_token_pair_via_console_command(){
        $this->artisan('app:generate', ['name'=>'Test Application']);
        $newApplication = APIApp::with('accessToken')->first();

        $this->assertTrue(APIApp::count() === 1);

        $this->assertTrue($newApplication->app_name === 'Test Application');
        $this->assertNotNull($newApplication->app_access_id);
        $this->assertNotNull($newApplication->accessToken->first()->secret_token);
     }
     
}
