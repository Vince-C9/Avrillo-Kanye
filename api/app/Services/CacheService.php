<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Throwable;

class CacheService { 

    /**
     * Takes an array of quotes and merges them with the existing quotes in the system.
     *
     * @param array $quotes
     * @return void
     */
    public function cacheQuotes(array $quotes){
        try {
            $cachedQuotes = Cache::pull('quotes');
        
            $cachedQuotes = $cachedQuotes === null ? $quotes : array_merge($quotes, $cachedQuotes);
            Cache::add('quotes', json_encode(array_unique($cachedQuotes)), now()->addHours(48));
            
        } catch (Throwable $t){
            report($t->getMessage());
        }
        
    }
}