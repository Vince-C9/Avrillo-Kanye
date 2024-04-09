<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Config;


class App extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_name',
        'app_access_id',
        'app_secret'
    ];

    public function accessToken(): HasMany { 
        return $this->hasMany(AccessToken::class);
    }


    /**
     * Generates a new app and access token and links them together.
     */
    public static function generate(string $appName = null): App {
        
        //Create a new app
        $app = App::create([
            'app_name' => !empty($appName) ? $appName : self::generateRandomAppName(),
            'app_access_id' => 'app-'.self::getUniqueAppId(),
            'app_secret' => 'sk-'.bin2hex(random_bytes(32))
        ]);
        
        //Create a new access token for said app.  This needs to be cryptographically strong
        AccessToken::GenerateNewAccessToken($app);
        
        //return the result
        return $app;
    }


    /**
     * Generates a new unique app ID and checks against the database to ensure that it is indeed unique.
     * Chances are slim with Microtime but not impossible.
     * 
     * Note: We could use raw SQL here to avoid the overheads of using the ORM within a loop.  This could also be 
     * put in a stored procedure to achieve the same.
     * 
     * returns string
     */
    private static function getUniqueAppId(): string {
            $key=sha1(microtime());
            
            $result = App::whereAppName('app-'.$key)->count();
            if($result > 0){
                return self::getUniqueAppId();
            }
        
        return $key;        
    }


    /**
     * Generates a random app name in case one was not provided.
     * 
     * return string
     */

     private static function generateRandomAppName(): string {
        $emotions = Config::get('random.emotions');
        $animals = Config::get('random.animals');
        return ucfirst($emotions[array_rand($emotions)]).' '.ucfirst($animals[array_rand($animals)]);
     }
}
