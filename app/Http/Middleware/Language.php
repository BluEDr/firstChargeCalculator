<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Auth;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()) {
            if (Auth()->user()->language != NULL)
                $locale = Auth()->user()->language; //edo me to middleware bazo tin glossa poy einai proepilegmeni ap ton xristi
        } else {
            $locale = config('app.locale'); //an den einai syndemenos mpainei i default timi
        }
        App::setLocale($locale);
        return $next($request);
    }
}
