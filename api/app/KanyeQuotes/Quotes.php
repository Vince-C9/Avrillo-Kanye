<?php

namespace App\KanyeQuotes;

use Illuminate\Support\Facades\Facade;

class Quotes extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'quotes';
    }
}