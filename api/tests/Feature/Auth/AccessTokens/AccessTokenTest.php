<?php

namespace Tests\Feature\Auth\AccessTokens;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\AccessToken;
use App\Models\App;
use Illuminate\Support\Carbon;

class AccessTokenTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     * @group AccessTokens
     */

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Returns existing AT if it exists and is valid
     *
     * @test
     * @group AccessTokens
     */
    public function it_returns_an_existing_valid_token_if_one_exists(){
        $app = App::factory()->create(['id' => 1]);
        AccessToken::factory()->create([
            'secret_token','totallysecuresecrettoken123', 
            'app_id'=>1
        ]);

        $token = AccessToken::GenerateNewAccessToken($app);
        $this->assertTrue($token === 'totallysecuresecrettoken123');

    }

    /**
     * Creates a new token if it doesn't exist 
     *
     * @test
     * @group AccessTokens
     */
    public function it_creates_a_new_token_if_one_doesnt_exist(){
        $app = App::factory()->create(['id' => 1]);
        $token = AccessToken::GenerateNewAccessToken($app);

        $this->assertNotEmpty($app->accessToken->first()->secret_token);
    }

    /**
     * Creates a new token if it only finds invalid ones 
     *
     * @test
     * @group AccessTokens
     */
    public function it_creates_a_new_token_if_all_tokens_are_invalid(){
        $app = App::factory()->create(['id' => 1]);
        AccessToken::factory()->create([
            'secret_token','totallysecuresecrettoken123', 
            'expiration_date'=>Carbon::now()->subDays(1),
            'app_id'=>1
        ]);

        $token = AccessToken::GenerateNewAccessToken($app);
        $this->assertNotEmpty($token);
        $this->assertTrue($token !== 'totallysecuresecrettoken123');
    }
}
