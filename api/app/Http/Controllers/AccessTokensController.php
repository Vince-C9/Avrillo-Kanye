<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessTokenRequest;
use App\Models\AccessToken;
use App\Models\App;
use Illuminate\Http\Request;

class AccessTokensController extends Controller
{
    /**
     * Generates access token for provided app ID
     *
     * @param AccessTokenRequest $request
     * @return void
     */
    public function token(AccessTokenRequest $request) {
        $token = AccessToken::getAccessToken(App::whereAppAccessId($request->input('app_id'))->first());
        if(empty($token)){
            $token = AccessToken::generateNewAccessToken(App::whereAppAccessId($request->input('app_id'))->first());
        }
        
        return response()->json([
            'access_token'=>$token
        ]);
    }
}
