<?php

namespace App\Http\Middleware;

use Closure;
use \App;

class SetLanguageForRequest
{
    /**
     * Check if a language query parameter is set and update the used
     * localization language accordingly
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        App::setLocale(request('lang', config('app.locale')));

        return $next($request);
    }
}
