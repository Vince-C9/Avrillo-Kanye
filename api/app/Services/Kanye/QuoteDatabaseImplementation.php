<?php

namespace App\Services\Kanye;

use App\Interfaces\KanyeQuoteInterface;
use App\Models\Quote;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class QuoteDatabaseImplementation implements KanyeQuoteInterface
{
    //Fetch a quote from the database
    public function quote(int $amount) {
        $quotes = Quote::select('quote')->inRandomOrder()->limit($amount)->pluck('quote')->toArray();
        
        $cacheService = new CacheService();
        $cacheService->cacheQuotes($quotes);

        return $quotes;
    }
}