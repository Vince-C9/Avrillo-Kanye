<?php

namespace Tests\Feature\Quote;

use App\Models\App as APIApp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Models\AccessToken;
use App\Models\Quote;
use App\Services\QuoteService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class QuotesTest extends TestCase
{
    use RefreshDatabase;

    private $token;
    private $apiApp;

    /**
     * Each time a test runs, set up at least one app and access token.
     *
     * @return void
     */
    public function setUp(): void{

        parent::setUp();
        $this->apiApp = APIApp::factory()->create();
        $this->token = Accesstoken::generateNewAccessToken($this->apiApp);
        
    }

    /**
     * It calls the endpoint to generate quotes and returns 5.
     *
     * @test
     * @group Quotes
     */
    public function it_generates_kanye_quotes_from_the_kanye_api(){
        //Fake the HTTP response.
        Http::fake([
            Config::get('kanye.api_endpoint') => Http::response(
                json_encode(['quote'=> fake()->sentence()]), 200,[]
            )
        ]);

        $response = $this->get(
            route('quote.get', ['implementation'=>'api']), 
            [
                'Authorization' => "Bearer ".$this->token, 
                'app-id'=>$this->apiApp->app_access_id
            ]
        );

        //It sent 5 requests for quotes
        Http::assertSentCount(5);
        $response->assertOk();
        $response->assertSee('quotes');
        $response->assertJsonIsArray('quotes');

        $quotes = json_decode($response->content())->quotes;
        $this->assertCount(5, $quotes);
    }

    

    /**
     * It calls the endpoint to generate quotes and returns 5 from the database (if they exist!).
     *
     * @test
     * @group Quotes
     */
    public function it_generates_kanye_quotes_from_cache(){

        $quoteService = new QuoteService();
        $quoteService->cache([
            fake()->sentence(),
            fake()->sentence(),
            fake()->sentence(),
            fake()->sentence(),
            fake()->sentence(),
        ]);

        $response = $this->get(
            route('quote.get', ['implementation'=>'cache']), 
            [
                'Authorization' => "Bearer ".$this->token, 
                'app-id'=>$this->apiApp->app_access_id
            ]
        );

        //It sent 5 requests for quotes
        $response->assertOk();
        $response->assertSee('quotes');
        $response->assertJsonIsArray('quotes');

        $quotes = json_decode($response->content())->quotes;
        $cachedQuotes = json_decode(Cache::get('quotes'));

        $this->assertCount(5, $quotes);
        $this->assertCount(5, $cachedQuotes);
        
    }

}
