<?php

namespace App\Http\Controllers;

use App\Services\QuoteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Throwable;

class QuoteController extends Controller
{
    //Protected by middleware - should only get here if AT is present.

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request) {
        try {
            $quoteService = new QuoteService;
            $quotes = $quoteService->getByImplementation(5);

            
            return response()->json(
                 ['quotes'=>$quotes] 
                , 200
            );
        } catch (Throwable $t) {
            report($t->getMessage());
            throw $t;
        }
    }

    /**
     * Clears cache before running the index method again.
     *
     * @param Request $request
     * @return void
     */
    public function refresh(Request $request){
        Cache::forget('quotes');
        return $this->index($request);
    }

    
}
