<?php

namespace App\Http\Middleware;
use Auth;
use Session;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {//$auth_user = Auth::user();
       // Log::info( "authentic ===>" . $request);
        if (! $request->expectsJson()) {
         return route('login');
        // return '/#/login';
          // $this->redirectTo = 'http://127.0.0.1:8000/#/';
           //return $this->redirectTo;
        }
    }
}
