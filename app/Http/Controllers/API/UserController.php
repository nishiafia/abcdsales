<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        return User::orderby('id', 'asc')->get();
    }

    public function store(Request $request)
    {   
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
           // 'usertype' => 'required',
            'telephone' => 'required',
            'password' => 'required',
            //'isactive' => 'required',
        ]);
        $emailExist = User::where('email', $request['email'])->first();
        $phoneExist = User::where('telephone', $request['telephone'])->first();
       if(!$emailExist && !$phoneExist)
       {
        return User::create([
           'name' => $request['name'],
           'email' => $request['email'],
           'dialcode' => $request['dialcode'],
           'telephone' => $request['telephone'],
           'password' => \Hash::make($request['password']),
           'usertype' => $request['usertype'],
           'businesscategory' => $request['businesscategory']
        
        ]);
      }
    }
    

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {   
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'usertype' => 'required',
            'isactive' => 'required',
        ]);

        $user = User::findOrFail($id);

        $user->update($request->all());
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
         'message' => 'User deleted successfully'
        ]);
    }

    public function getcompany()
    {
        return User::orderby('id', 'asc')->where('usertype', 'company')->where('isactive', true)->get();
    }
    public function getbranchcontactperson()
    {
        return User::orderby('id', 'asc')->where('usertype', 'branch')->where('isactive', true)->get();
    }
    
}
