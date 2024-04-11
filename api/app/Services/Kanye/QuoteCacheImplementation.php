<?php

namespace App\Services\Kanye;

use App\Interfaces\KanyeQuoteInterface;
use Illuminate\Support\Facades\Cache;
use App\Models\Quote;

/**
 * I'm starting to run out of time, so this one has been done a little sloppily.
 * We could improve this greatly by using redis, offloading our cache onto a new service
 * and relying on system RAM to access it.
 */

class QuoteCacheImplementation implements KanyeQuoteInterface
{
    /**
     * Parses the json stored cache of quotes and returns the first 5 in a random order.
     *
     * @param integer $amount
     * @return array|null
     */
    public function quote(int $amount) {
        return Quote::get($amount);
    }
}