<?php

namespace App\Http\Controllers;

use App\Services\QuoteService;
use Illuminate\Http\Request;
use Throwable;

class QuoteController extends Controller
{
    //Protected by middleware - should only get here if AT is present.
    public function index(Request $request) {
        try {
            $quoteService = new QuoteService;
            $quotes = $quoteService->getQuote($request, 5);
            return response()->json(['quotes'=>$quotes]);
        } catch (Throwable $t) {
            report($t->getMessage());
            throw $t;
        }
    }

    
}
