<?php

namespace Tests\Feature\Auth\AccessTokens;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\AccessToken;

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
     * An access token can be generated
     * 
     * @test
     * @group AccessTokens
     */

     public function it_can_generate_a_unique_access_token(){
        $token = AccessToken::factory()->create(['app_id'=>1]);

        $this->assertTrue(!is_null($token));
     }
}
