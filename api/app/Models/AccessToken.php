<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\App as APIApp;
use Exception;
use Illuminate\Support\Carbon;
use Throwable;

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
    public static function generateNewAccessToken(APIApp $app): string {
        
        $token = bin2hex(random_bytes(64));
        $uniqueToken = AccessToken::whereAppId($app->id)->where('access_token', $token)->count();
        //If this token has already been used in the database, re-run
        if($uniqueToken > 0){
            return Self::generateNewAccessToken($app);
        }
        // add new token to the database.
        $app->accessToken()->create([
            'access_token'=>$token,
            'expiration_date' => Carbon::now()->addHours(2)
        ]);

        return $token;
    }

    /**
     * Gets existing valid access token
     *
     * @param APIApp $app
     * @return string|null
     */
    public static function getAccessToken(APIApp $app): string|null {

        try {
            //If we have a valid access token, we don't need a new one. Return it.
            $validToken = $app->with([
                'accessToken' => fn($q) => $q->where('expiration_date', '>', Carbon::now())
            ])
            ->whereActive(true)
            ->whereHas('accessToken', function($query) use ($app){
                $query->where('expiration_date', '>', Carbon::now());
            })
            ->first();

            if($validToken){
                return $validToken->accessToken->first()->access_token;
            }
            return null;
        }
        catch(Throwable $e){
            report($e->getMessage());
            throw new Exception($e);
        }
    }
}
