<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
//se Illuminate\Support\Facades\Auth;
//use App\Middleware\Authenticate;
use App\Http\Controllers\Controller;
use App\Company;
use App\Branch;
use App\User;
use App\Usercompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Auth;



class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use AuthenticatesUsers;
       /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       // Log::info( "Nishi ===>" . base64_decode($request->header('sessionKey')));
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        $now = Carbon::now();
        $last_seen = Carbon::parse($userinfo->last_seen_at);
        $absence = $last_seen->diffInMinutes($now,true);
         Log::info( "authout ===>" . $userinfo);
        Log::info( "nowout ===>" . $now);
        Log::info( "absenceout ===>" . $absence);
        Log::info( "last_seenout ===>" . $last_seen);
        Log::info( "timeoutc ===>" . config('session.lifetime'));

        if($absence > config('session.lifetime')) {
            User::where('id', $userinfo->id)->update(['islogin' => 0]);
           // Session::flush();
           
           Auth::logout();
           return redirect('/');
       }
       else{
        if($userinfo->usertype == 'superadmin')
        {
            return Company::with('businesscategoryname')
                       ->with('city')
                       ->with('companytypename')
                       ->where('systemid', $systemid)
                        ->orderby('id', 'asc')->get();

        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            return Company::with('businesscategoryname')
                       ->with('city')
                       ->with('companytypename')
                       ->where('systemid', $userinfo->systemid)
                        ->orderby('id', 'asc')->get();

        }
        if($userinfo->usertype == 'team'){
            return Company::with('businesscategoryname')
                       ->with('city')
                       ->with('companytypename')
                       ->where('systemid', $userinfo->systemid)
                       ->where('companyid', $userinfo->companyid)
                        ->orderby('id', 'asc')->get();

        }
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
            'companyname' => 'required',
            'businesscategory' => 'required',
            'thanaid' => 'required',
            'address' => 'required',
            'companytype' => 'required',
            //'isactive' => 'required',
        ]);
    $userinfo = User::where('id', $request['entryid'])->first();
     $data= Company::create([
           'companyname' => $request['companyname'],
           'businesscategory' => $request['businesscategory'],
           'country' => $request['country'],
           'thanaid' => $request['thanaid'],
           'address' => $request['address'],
           'companytype' => $request['companytype'],
           'systemid' => $request['systemid'],
           'entryid' => $request['entryid']
        ]);

        $lastID = $data->id;
        Log::info( "Lastid))  ====> " .  $lastID);
       // User::where('id', $request['entryid'])->update(['companyid' => $lastID]);
        $brname = Branch::where('branchname', 'Main Branch')->where('companyid', $lastID)->where('systemid', $request['systemid'])->first();
        if(!$brname)
        {
            $brnamedata= Branch::create([
                'branchname' => 'Main Branch',
                'address' =>$request['address'],
                'contactperson' => $request['systemid'],
                'systemid' => $request['systemid'],
                'companyid' => $lastID,
                'entryid' => $request['entryid']
             ]);
             $lastbranchID = $brnamedata->id;
        }
        //Log::info( "branchid))  ====> " .  $lastbranchID);
        if($userinfo->companyid === 0)
        {
        User::where('id', $request['entryid'])->update(['companyid' => $lastID,'branchid' => $lastbranchID]);
        }
        $comid = Usercompany::where('companyid', $lastID)->where('userid', $request['entryid'])->first();
        if(!$comid)
        {
            Usercompany::create([
                'userid' => $request['entryid'],
                'companyid' =>$lastID,
                'branchid' => $lastbranchID,
             ]);
        }

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
            'companyname' => 'required',
            'businesscategory' => 'required',
            //'thanaid' => 'required',
            'address' => 'required',
            'companytype' => 'required',
            //'isactive' => 'required',
        ]);
        $company = Company::findOrFail($id);
        $branchid = Branch::where('companyid', $company->id)->first();
        $comid = Usercompany::where('companyid', $id)->where('userid', $request['entryid'])->first();
        $del=Usercompany::where('companyid', $id)->delete();
        if(!$comid)
        {
            Usercompany::create([
                'userid' => $request['entryid'],
                'companyid' =>$id,
                'branchid' => $branchid->id,
             ]);
        }

        $company->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $del=Usercompany::where('companyid', $id)->delete();
        $company->delete();
        return response()->json([
         'message' => 'Company deleted successfully'
        ]);
    }

    public function getteamcompany(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        Log::info( "Systemid ===>" . $systemid);
        $userinfo = User::where('id', $systemid)->first();
        Log::info( "usertype ===>" . $userinfo->usertype);
        if($userinfo->usertype == 'superadmin')
        {
        return Company::orderby('id', 'asc')->get();
        }
        else{
            return Company::select('id','companyname')->orderby('id', 'asc')->where('systemid', $userinfo->systemid)->get();
        }
    }
    public function getcompany(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'superadmin')
        {
            return Company::select('id','companyname')->orderby('id', 'asc')->get();
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            return Company::select('id','companyname')->orderby('id', 'asc')->where('systemid', $userinfo->systemid)->get();
        }
        if($userinfo->usertype == 'team'){
            return Company::select('id','companyname')->orderby('id', 'asc')->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->get();
        }
    }
}
