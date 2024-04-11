<?php

namespace App\Services\Kanye;

use App\Interfaces\KanyeQuoteInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Config;
use App\Models\Quote;

class KanyeRestImplementation implements KanyeQuoteInterface
{
    /**
     * Gets the amount of quotes requested from the API.
     * I wasn't able to find a way to get the rest API to return more than one quote.
     * I even checked the github for a way to pass in a value, but didn't seem to be
     * supported!  Sadly, we'll need multiple API calls. :( 
     *
     * @param int $amount
     * @return void
     */
    public function quote(int $amount) {
 
        //dynamically build 'amount' of requests
        $dynamicRequests = [];
        $quotes = [];

        for($i=0; $i < $amount; $i++){
            $dynamicRequests[] = Config::Get('kanye.api_endpoint');
        }

        //Convert to a pool type
        $pooledRequests = function (Pool $pool) use ($dynamicRequests){
            foreach($dynamicRequests as $request){
                $poolArray[] = $pool->get($request);
            }
            return $poolArray;
        };

        //Send concurrent/pooled http requests.
        $apiResponses = Http::pool($pooledRequests);

        /**
         * Store quotes in array for returning.
         * We could return the whole object and parse in the controller
         * but I like to keep controllers tidy, and I know that I want this interface
         * to always return an array of quotes.
         * 
         * could also check for each of the responses to be ok ($response[0]->ok())
         * before returning.  Probably a good idea to do so! :) 
         **/

        foreach($apiResponses as $response){
            $quote = json_decode($response->body())->quote;
            $quotes[] = $quote;
            //Store in database if doesn't exist already
            Quote::firstOrCreate([
                'quote' => $quote
            ]);
        }
        
        //Return quotes
        return $quotes;
    }
}