<?php

namespace App\Services;

use App\Interfaces\KanyeQuoteInterface;
use Illuminate\Http\Request;
use App\Services\Kanye\KanyeRestImplementation;
use App\Services\Kanye\QuoteCacheImplementation;
use App\Services\Kanye\QuoteDatabaseImplementation;

class QuoteService { 
    /**
     * Gathers quotes based on the provided interface.  It will
     * return the number of quotes requested as an array.
     * mode (at this point in time) governed by choice from front end
     * Will upgrade to driver control if time permits
     *
     * @param KanyeQuoteInterface $quoteImplementation
     * @param integer $numberOfQuotes
     * @return array
     */
    public function getQuote(Request $request, int $numberOfQuotes) {

        switch($request->input('implementation')) {
            case 'database':
                $quoteImplementation = new QuoteDatabaseImplementation();
            break;
            case 'cache':
                $quoteImplementation = new QuoteCacheImplementation();
            break;
            default:
                $quoteImplementation = new KanyeRestImplementation();
            break;
        }

        $quotes = $quoteImplementation->quote($numberOfQuotes);
        return $quotes;
    }
}