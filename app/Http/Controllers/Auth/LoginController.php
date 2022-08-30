<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/dashboard';

    /*public function redirectPath()
    {
        return 'admin/dashboard';
    }*/

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware('guest')->except('logout');
    }
   public function index()
    {
    return view('index', [
      'auth_user' => Auth::user()
    ]);
    }
    public function logout(Request $request) {
      $auth_user = Auth::user();
      Log::info( "remember_token ===>" . $auth_user->remember_token);
      User::where('id', $auth_user->id)->update(['islogin' => 0,'remember_token' =>$auth_user->remember_token]);
      Auth::logout();
      return redirect('/');
    }

    public function vuelogin(Request $request)
    {
      if (Auth::attempt([
        filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'telephone' => $request->telephone,
        'password' => $request->password,'isactive'=>1,'islogin' =>0
      ])){
        $auth_user = Auth::user();
        Log::info( "auth ===>" . $auth_user->isactive);
       $lastlogtime = Carbon::now()->toDateTimeString();
        User::where('id', $auth_user->id)
          ->update(['remember_token' =>  base64_encode($auth_user->id),'logintime' =>  $lastlogtime,'islogin' => true]);
        $username = $auth_user->name;
        return response()->json([
          'status'   => 'success',
          'user' => $username,
        ]);
      } else {
        return response()->json([
          'status' => 'error',
          'user'   => 'Unauthorized Access'
        ]);
      }
    //if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
      // if(Auth::attempt(['telephone' => $request->telephone, 'password' => $request->password])){
      //     $auth_user = Auth::user();
      //     $username = $auth_user->name;
      //     return response()->json([
      //       'status'   => 'success',
      //       'user' => $username,

      //     ]);
      //   } else {
      //     return response()->json([
      //       'status' => 'error',
      //       'user'   => 'Unauthorized Access'
      //     ]);
      //   }
    }
    public function vueloginadmin(Request $request)
    {
      if (Auth::attempt([
        filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'telephone' => $request->telephone,
        'password' => $request->password,'isactive'=>1
      ])){
        $auth_user = Auth::user();
        Log::info( "auth ===>" . $auth_user->isactive);
       $lastlogtime = Carbon::now()->toDateTimeString();
        User::where('id', $auth_user->id)
          ->update(['remember_token' =>  base64_encode($auth_user->id),'logintime' =>  $lastlogtime]);
        $username = $auth_user->name;
        return response()->json([
          'status'   => 'success',
          'user' => $username,
        ]);
      } else {
        return response()->json([
          'status' => 'error',
          'user'   => 'Unauthorized Access'
        ]);
      }
    }

    public function adminuserlogin(Request $request)
    {
      //Log::info("User Request=>" . $request);
      $user1 = User::where('telephone', $request->telephone)->get()->first();
     Log::info("adminpassdb=>" . $user1->name);
     Log::info("realpss=>" . $request->adminuserpassword);
      //if (Hash::check($request->adminuserpassword, $user->adminuserpassword)) {
        if (Hash::check($request->adminuserpassword, $user1->adminuserpassword)){
         Auth::login($user1, true);
        //Log::info("Auth username=>" .  $auth_user->name);
        $username = $user1->name;
        if($user1->remember_token === '')
        {
        User::where('id', $user1->id)
        ->update(['remember_token' =>  base64_encode($user1->id)]);
        Log::info("Update  Token=>" .  $user1->remember_token);
        }
       return response()->json([
          'status'   => 'success',
          'user' => $username,
        ]);
      }else {
        return response()->json([
          'status' => 'error',
          'user'   => 'Unauthorized Access'
        ]);
      }
    }
}

/*
if (Auth::attempt([
        filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'telephone' => $request->telephone,
        'adminuserpassword' => $request->adminuserpassword,'isactive'=>1
      ])){
        $auth_user = Auth::user();
        Log::info( "auth ===>" . $auth_user->isactive);
       $lastlogtime = Carbon::now()->toDateTimeString();
       User::where('id', $auth_user->id)
          ->update(['remember_token' =>  base64_encode($auth_user->id),'logintime' =>  $lastlogtime,'islogin' => true]);
        $username = $auth_user->name;
        return response()->json([
          'status'   => 'success',
          'user' => $username,
        ]);
      } else {
        return response()->json([
          'status' => 'error',
          'user'   => 'Unauthorized Access'
        ]);
      }
 */