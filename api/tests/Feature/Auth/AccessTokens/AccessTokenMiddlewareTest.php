<?php

namespace Tests\Feature\Auth\AccessTokens;

use App\Http\Middleware\AccessTokenAuthentication;
use App\Models\App;
use App\Models\AccessToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AccessTokenMiddlewareTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Unauthenticated users cannot access any protected endpoints
     * 
     * @test
     * @group Middleware
     */

     public function it_denies_access_without_access_token_and_app_id(){

        $request = Request::create(route('health-check'));
        $next = function() {
            return response('Access Granted :)');
        };

        $middleware = new AccessTokenAuthentication();
        $response = $middleware->handle($request, $next);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $this->assertEquals('Access denied', $response->getContent());
     }

     /**
     * @test
     * @group Middleware
     */

     public function it_denies_access_if_only_one_of_the_required_variables_are_present(){

        $app = App::factory()->create();

        $request = Request::create(route('health-check'));
        $request->headers->set('app-id', $app->id);

        $next = function() {
            return response('Access Granted :)');
        };

        $middleware = new AccessTokenAuthentication();
        $response = $middleware->handle($request, $next);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        $this->assertEquals('Access denied', $response->getContent());
     }

     /**
     * @test
     * @group Middleware
     */

     public function it_allows_access_if_a_valid_access_token_app_id_pair_is_present() {

        $app = App::factory()->create();
        $accessToken = AccessToken::generateNewAccessToken($app);

        $request = Request::create(route('health-check'));
        $request->headers->set('app-id', $app->app_access_id);
        $request->headers->set('Authorization', 'Bearer '.$accessToken);

        $next = function() {
            return response('Access Granted :)');
        };

        $middleware = new AccessTokenAuthentication();
        $response = $middleware->handle($request, $next);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('Access Granted :)', $response->getContent());
     }
}
