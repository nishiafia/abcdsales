<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Customer;
use App\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
//use Auth;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware('guest')->except('logout');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        Log::info( "systemid ===>" . $systemid);
       $user1=Auth::user();
       // Auth::logout();
       Log::info( "authuser ===>" . $user1);
        $now = Carbon::now();

        $last_seen = Carbon::parse($userinfo->last_seen_at);
        $absence = $last_seen->diffInMinutes($now,true);
        Log::info( "authcustomer ===>" . $userinfo);
         Log::info( "nowcustomer ===>" . $now);
         Log::info( "absencecustomer ===>" . $absence);
         Log::info( "last_seenustomer ===>" . $last_seen);
         Log::info( "timeoutc ===>" . config('session.lifetime'));
        if($absence > config('session.lifetime')) {
            User::where('id', $userinfo->id)->update(['islogin' => 0]);
           // Session::flush();
           
           Auth::logout();
           return redirect('/');
          //return $next($request);
        }
        else{
        if($userinfo->usertype == 'superadmin')
        {
            $data = Customer::orderby('id', 'asc')->paginate(10);

            return response()->json($data);
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional')
        {
            $data = Customer::with('pname')->where('systemid', $userinfo->systemid)->orderby('id', 'desc')->paginate(20);

            return response()->json($data);
         }
         if($userinfo->usertype == 'team')
         {
             $data = Customer::with('pname')->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->orderby('id', 'desc')->paginate(20);
             return response()->json($data);
          }
        }
    }
    public function searchPartner(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        Log::info( "systemid ===>" . $systemid);
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional')
        {
            $data = Customer::with('pname')->where('systemid', $userinfo->systemid)->where('partnertype', $request['partnertype'])->orderby('id', 'desc')->paginate(20);

            return response()->json($data);
         }
         if($userinfo->usertype == 'team')
         {
             $data = Customer::with('pname')->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('partnertype', $request['partnertype'])->orderby('id', 'desc')->paginate(20);
             return response()->json($data);
          }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'customername' => 'required',
            'telephone' => 'required',
            'address' => 'required',
            'partnertype' => 'required'
        ]);
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        $phoneExist = Customer::where('telephone', $request['telephone'])->first();
        $currentdate=date("Y-m-d");
        if(!$phoneExist)
        {
        if($currentdate <= $userinfo->subscriptiondate && $userinfo->subscriptionstatus === 1) {
            Log::info( "currentdate ===>" . $currentdate);
            Log::info( "subscriptiondate ===>" . $userinfo->subscriptiondate);
            if($userinfo->entrylimit === 0)
            {
             $customer = Customer::create([
           'partnertype' => $request['partnertype'],
           'customername' => $request['customername'],
           'email' => $request['email'],
           'dialcode' => $request['dialcode'],
           'telephone' => $request['telephone'],
           'country' => $request['country'],
           'systemid' => $request['systemid'],
           'entryid' => $request['entryid'],
           'companyid' => $request['companyid'],
           'branchid' => $request['branchid'],
           'address' => $request['address'],
           'description' => $request['description'],
           'isactive' => $request['isactive'],
        ]);
        return response()->json([
            'message' => 'done'
           ]);
        }
        else{
                $Customercount = Customer::where('systemid', $request['systemid'])->whereDate('created_at',Carbon::today())->where('isactive', true)->count();
                Log::info( "Customercount ===>" .$Customercount);
                if($Customercount < $userinfo->entrylimit){
                    $customer = Customer::create([
                        'partnertype' => $request['partnertype'],
                        'customername' => $request['customername'],
                        'email' => $request['email'],
                        'dialcode' => $request['dialcode'],
                        'telephone' => $request['telephone'],
                        'country' => $request['country'],
                        'systemid' => $request['systemid'],
                        'entryid' => $request['entryid'],
                        'companyid' => $request['companyid'],
                        'branchid' => $request['branchid'],
                        'address' => $request['address'],
                        'description' => $request['description'],
                        'isactive' => $request['isactive'],
                     ]);
                     return response()->json([
                        'message' => 'done'
                       ]);
                }
                else{
                    return response()->json([
                        'message' => 'not'
                       ]);
                }
            }
        }
        else{
            return response()->json([
                'message' => 'expired'
               ]);
        }
        //$lascustomerID = $customer->id;
    }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'customername' => 'required',
            'telephone' => 'required',
            'address' => 'required',
        ]);
        $customer = Customer::findOrFail($id);

        return $customer->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $status = $request->header('StatusKey');
        Log::info( "Status ===>" . $status);
        $customer = Customer::findOrFail($id);

        if($status === 'inactive')
        {
            $customer->where('id', $id)->update(['isactive' => false]);
        }
        if($status === 'active')
        {
            $customer->where('id', $id)->update(['isactive' => true]);
        }
        if($status === 'del')
        {
        $customer->delete();
        return response()->json([
         'message' => 'customer deleted successfully'
        ]);
        }
    }
    public function getcustomer(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'superadmin')
        {
            return Customer::orderby('id', 'asc')->select('id','customername')->where('partnertype', 1)->where('isactive',1)->get();
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            return Customer::orderby('id', 'asc')->select('id','customername','dialcode','telephone','address')->where('partnertype', 1)->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('isactive',1)->get();
        }
        if($userinfo->usertype == 'team'){
            return Customer::orderby('id', 'asc')->select('id','customername','dialcode','telephone','address')->where('partnertype', 1)->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('isactive',1)->get();
        }
    }
    public function getvendor(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'superadmin')
        {
            return Customer::orderby('id', 'asc')->select('id','customername')->where('partnertype', 12)->where('isactive',1)->get();
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            return Customer::orderby('id', 'asc')->select('id','customername')->where('partnertype', 2)->where('systemid', $userinfo->systemid)->where('isactive',1)->get();
        }
        if($userinfo->usertype == 'team'){
            return Customer::orderby('id', 'asc')->select('id','customername')->where('partnertype', 2)->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('isactive',1)->get();
        }
    }
    public function getactivityvendor(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $ptype = $request->header('partnerType');
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'superadmin')
        {
            return Customer::orderby('id', 'asc')->select('id','customername','telephone')->where('partnertype', 12)->where('isactive',1)->get();
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            return Customer::orderby('id', 'asc')->select('id','customername','telephone')->where('partnertype', $ptype)->where('systemid', $userinfo->systemid)->where('isactive',1)->get();
        }
        if($userinfo->usertype == 'team'){
            return Customer::orderby('id', 'asc')->select('id','customername','telephone')->where('partnertype', $ptype)->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('isactive',1)->get();
        }
    }
}
