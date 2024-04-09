<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\App as APIApp;
use Illuminate\Support\Carbon;

class AccessToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_id',
        'access_token',
        'expiration_date',
        'last_accessed',
        'active'
    ];

    public function app(): BelongsTo {
        return $this->belongsTo(AccessToken::class);
    }

   /**
    * Generates a new unique access key relating to the app ID provided.
    *
    * @param APIApp $app
    * @return string
    */
    public static function GenerateNewAccessToken(APIApp $app): string {
        //If we have a valid access token, we don't need a new one. Return it.
        $validToken = $app->with([
            'accessToken' => fn($q) => $q->where('expiration_date', '>', Carbon::now())
        ])
        ->whereActive(true)
        ->whereHas('accessToken', function($query){
           $query->where('expiration_date', '>', Carbon::now());
        })
        ->first();

        if($validToken){
            return $validToken->accessToken->first()->access_token;
        }

        //We don't have a valid token.  Make one! :) 
        $token = bin2hex(random_bytes(64));
        $uniqueToken = AccessToken::whereAppId($app->id)->where('access_token', $token)->count();
        //If this token has already been used in the database, re-run
        if($uniqueToken > 0){
            return Self::GenerateNewAccessToken($app);
        }
        // add new token to the database.
        $app->accessToken()->create([
            'access_token'=>$token,
            'expiration_date' => Carbon::now()->addHours(2)
        ]);

        return $token;
    }
}
