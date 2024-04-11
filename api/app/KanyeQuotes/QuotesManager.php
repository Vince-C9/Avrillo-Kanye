<?php

namespace App\KanyeQuotes;

use App\Services\Kanye\KanyeRestImplementation;
use App\Services\Kanye\QuoteCacheImplementation;
use Illuminate\Support\Manager;
use Illuminate\Support\Facades\Config;

class QuotesManager extends Manager
{
    public function getDefaultDriver()
    { 
        return Config::get('kanye.api_drivers.priority_driver') ?? Config::get('kanye.api_drivers.default_driver');
    }

    public function createCacheDriver()
    {
        return new QuoteCacheImplementation();
    }

    public function createRestfulDriver()
    {
        return new KanyeRestImplementation();
    }
}