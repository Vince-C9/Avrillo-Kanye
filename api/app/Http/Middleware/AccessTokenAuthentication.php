<?php

namespace App\Http\Middleware;

use App\Http\Requests\AccessTokenAuthenticationRequest;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AccessToken;
use App\Models\App;
use Exception;
use Throwable;
use Tests\Feature\Auth\AccessTokens\AccessTokenTest;

class AccessTokenAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


     /**
      * We could make this a lot better by adding scopes, factor in XSRF etc.  We can iterate up from here =)
      */
    public function handle(Request $request, Closure $next): Response
    {
        try{

            //Get bearer token from headers
            $accessToken = $request->bearerToken();
            //Get app ID from headers
            $applicationId = $request->header('app-id');

            if(empty($accessToken) || empty($applicationId)){
                return response('Access denied', Response::HTTP_UNAUTHORIZED);
            }

            //Get existing access token owned by the account ID
            $existingAccessToken = AccessToken::getAccessToken(App::whereId($applicationId)->firstOrFail());
            //If existing access token and token matches, approve access.
            if($existingAccessToken && $existingAccessToken === $accessToken){
                return $next($request);
            }  
            
            //If access token is not present, throw 304 error
            return response('Access denied', Response::HTTP_UNAUTHORIZED);

        } catch(Throwable $t) {
            //If access token is not present, throw 304 error
            report($t->getMessage());
            throw new Exception($t->getMessage(), (int)$t->getCode());
        }
    }
}
