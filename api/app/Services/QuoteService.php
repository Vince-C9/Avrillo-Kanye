<?php

namespace App\Services;

use App\Interfaces\KanyeQuoteInterface;
use App\Services\Kanye\KanyeRestImplementation;
use App\Services\Kanye\QuoteCacheImplementation;
use Illuminate\Support\Facades\Cache;
use Throwable;

class QuoteService { 
    /**
     * Gathers quotes based on the provided interface.  It will
     * favour cached quotes over fetching new ones.  It no longer saves 
     * quotes to the database!
     *
     * @param KanyeQuoteInterface $quoteImplementation
     * @param integer $numberOfQuotes
     * @return array
     */
    public function getByImplementation(int $numberOfQuotes) {

        $quotes = Cache::get('quotes');
        if(!empty($quotes)){
            $quoteImplementation = new QuoteCacheImplementation();
        }else{
            $quoteImplementation = new KanyeRestImplementation();
        }

        $quotes = $quoteImplementation->quote($numberOfQuotes);
        return $quotes;
    }



    /**
     * Takes an array of quotes and merges them with the existing quotes in the system.
     *
     * @param array $quotes
     * @return void
     */
    public function cache(array $quotes){
        try {
            $cachedQuotes = Cache::pull('quotes');
        
            $cachedQuotes = $cachedQuotes === null ? $quotes : array_merge($quotes, $cachedQuotes);
            Cache::add('quotes', json_encode(array_unique($cachedQuotes)), now()->addHours(48));
            
        } catch (Throwable $t){
            report($t->getMessage());
        }
    }

    /**
     * Gets quotes prioritising cache over database.
     *
     * @param int $amount
     * @return void
     */
    public static function getFromCache(){
        //Prioritise Cached quotes as array
        $quotes = json_decode(Cache::get('quotes'), true);

        //return the chosen quotes as an array
        return !empty($quotes) ? $quotes : [];
    }
}