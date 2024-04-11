<?php

namespace App\KanyeQuotes;

use App\KanyeQuotes\QuotesManager;
use Illuminate\Support\ServiceProvider;

class QuotesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('quotes', function ($app) {
            return new QuotesManager($app);
        });
    }
}