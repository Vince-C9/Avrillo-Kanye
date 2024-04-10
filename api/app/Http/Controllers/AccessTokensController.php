<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessTokenRequest;
use App\Models\AccessToken;
use App\Models\App;
use Illuminate\Http\Request;

class AccessTokensController extends Controller
{
    public function token(AccessTokenRequest $request) {
        $token = AccessToken::generateNewAccessToken(App::whereAppAccessId($request->input('app_id'))->first());
        return response()->json([
            'access_token'=>$token
        ]);
    }
}
