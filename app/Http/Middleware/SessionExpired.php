<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Session\Store;
use Auth;
use Session;
use App\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class SessionExpired {
    protected $session;
    protected $timeout = 5;
   
    public function handle($request, Closure $next){
            if (!Auth::check()) {
                return $next($request);
              }
            //if (Auth::check()) {
            $auth_user = Auth::user();
            $now = Carbon::now();

            $last_seen = Carbon::parse($auth_user->last_seen_at);
            $absence = $last_seen->diffInMinutes($now,true);
 // If user has been inactivity longer than the allowed inactivity period
            if ($absence > config('session.lifetime')) {
            $auth_user->islogin = 0;

            $auth_user->save();
            Auth::logout();
            $request->session()->invalidate();

      return $next($request);
    }
   $auth_user->last_seen_at = $now->format('Y-m-d H:i:s');
    $auth_user->save();
  
       return $next($request);
    }
}