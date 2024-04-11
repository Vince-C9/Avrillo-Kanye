<?php

namespace App\Models;

use App\Services\QuoteService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Services\CacheService;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'quote'
    ];


    /**
     * Gets quotes prioritising cache over database.
     *
     * @param int $amount
     * @return void
     */
    public static function get(int $amount){
        //Prioritise Cached quotes as array
        $quotes = json_decode(Cache::get('quotes'), true);

        //Populate from database if cache doesn't exist
        if(empty($quotes))
        {
            $cacheService = new CacheService();
            $quotes = self::select('quote')->inRandomorder();
            //If we have some quotes, cache them so that we can prioritise them next time!
            if(!empty($quotes))
            {
                $cacheService->cacheQuotes($quotes->pluck('quote')->toArray());
                $quotes = $quotes->limit($amount)->pluck('quote')->toArray();
            }
        }

        //return the chosen quotes as an array
        return !empty($quotes) ? $quotes : [];
    }
}
