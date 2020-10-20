<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\User;
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
      Auth::logout();
      return redirect('/');
    }

    public function vuelogin(Request $request)
    {
     
    //if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
      if(Auth::attempt(['telephone' => $request->telephone, 'password' => $request->password])){ 
          $auth_user = Auth::user();
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
}