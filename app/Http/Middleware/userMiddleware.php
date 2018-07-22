<?php

namespace App\Http\Middleware;

use Closure;

class userMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user = $request->user();

        if($user){
            if($user->isUser()){
                return $next($request);
            }
        }

        return redirect()
                ->back()
                ->withErrors("Silahkan Login Terlebih Dahulu");
    }
}
