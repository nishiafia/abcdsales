<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use App\Branch;
use App\Usercompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
//use Illuminate\Foundation\Auth\AuthenticatesUsers;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\Mail;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{

    public function index(Request $request)
    {
       
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        $auser=Auth::user();
       // Log::info( "authnnnnnnnnnnnn ===>" . $auser);
        $now = Carbon::now();
        $last_seen = Carbon::parse($userinfo->last_seen_at);
        $absence = $last_seen->diffInMinutes($now,true);
        Log::info( "absence ===>" . $absence);
        if($absence > config('session.lifetime')) {
            User::where('id', $userinfo->id)->update(['islogin' => 0]);

           Auth::logout();
           return response()->json([
            'mgs' => 'logout'
           ]);
       }
       else{
        if($userinfo->usertype == 'superadmin')
        {
        /*return User::with(array('systemname'=>function($query){
                        $query->select('id','name');
                     }))->where('usertype','standard')->ORwhere('usertype','platinum')->orderby('id', 'asc')->paginate(10);*/
                     return User::where('usertype','standard')->ORwhere('usertype','professional')->ORwhere('usertype','basic')->orderby('id', 'desc')->paginate(30);
                    }
        else{
            return User::orderby('id', 'asc')->get();
         }
        }
    }

    public function store(Request $request)
    {
        $auser=Auth::user();
        // Log::info( "authnnnnnnnnnnnn ===>" . $auser);
         $now = Carbon::now();
         $last_seen = Carbon::parse($auser->last_seen_at);
         $absence = $last_seen->diffInMinutes($now,true);
         //Log::info( "absence ===>" . $absence);
         if($absence > config('session.lifetime')) {
             User::where('id', $auser->id)->update(['islogin' => 0]);
 
            Auth::logout();
            return response()->json([
             'mgs' => 'logout'
            ]);
        }
        else{
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'usertype' => 'required',
            'telephone' => 'required',
            'password' => 'required',
            //'isactive' => 'required',
        ]);
        $emailExist = User::where('email', $request['email'])->first();
        $phoneExist = User::where('telephone', $request['telephone'])->first();

        $date = strtotime("+7 day");
        $afterdate= date('Y-m-d', $date);
        $startdate=date('Y-m-d');
        $adminuserpassword=Hash::make('@ABCU789');

        Log::info( "7 days after ===>" . $afterdate);
   if(!$phoneExist)
       {
        $usercreate= User::create([
           'name' => $request['name'],
           'email' => $request['email'],
           'dialcode' => $request['dialcode'],
           'telephone' => $request['telephone'],
           'password' => Hash::make($request['password']),
           'adminuserpassword' => $adminuserpassword,
           'usertype' => $request['usertype'],
          // 'businesscategory' => $request['businesscategory'],
           'systemid' => $request['systemid'],
           'companyid' => $request['companyid'],
           'branchid' => $request['branchid'],
           'address' => $request['address'],
          // 'companylimit' => $request['companylimit'],
           //'entrylimit' => $request['entrylimit'],
        ]);

        $userID = $usercreate->id;
        if($request['usertype'] === 'basic')
        {
            $companylimit=1;
            $entrylimit=10;
            $orderlimit=10;
            $teamlimit=1;
            Log::info( "usertype ===>" .$request['usertype']);
            User::where('id', $userID)->update(['systemid' => $userID,'companylimit' =>$companylimit,'orderlimit' => $orderlimit,'entrylimit' => $entrylimit, 'subscriptionstartdate' => $startdate, 'subscriptiondate' => $afterdate,'teamlimit' => $teamlimit]);


        }
        if($request['usertype'] === 'standard' || $request['usertype'] === 'professional')
        {
            $companylimit=1;
            $entrylimit=10;
            $teamlimit=1;
            $orderlimit=10;
            Log::info( "usertype ===>" .$request['usertype']);
            User::where('id', $userID)->update(['systemid' => $userID]);
        }
        // Email Send To Management
        $string = Str::ucfirst($request['usertype']);
        $loginurl="http://store.abcdsales.com.bd/#/";
        $copyrighturl="https://www.abcdsales.com.bd/";
        $data = array('name'=>$request['name'],'phone'=>$request['telephone'],'memail'=>$request['email'],'pass'=>$request['password'],'package'=> $string,'logurl'=> $loginurl,'courl'=> $copyrighturl);

        Mail::send([], ['data' => $data], function($message) use ($data) {
           // $message->to('team@abcdsales.com.bd', 'ABCD Sales')->subject('New Member Signup Notification');
          $message->to('nishi9004@gmail.com', 'ABCD Sales')->subject('New Member Signup Notification');
           $message->from("noreply@abcdsales.com.bd",'ABCD Sales');
            $message->setBody('<h3>Below New User Registered in ABCD Sales System</h3><p>Name: '.$data['name'].' </p><p>Phone: '.$data['phone'].'</p><p> Email: '.$data['memail'].'</p><p> Package: '.$data['package'].'</p>','text/html');
        });
        // Email Send To User
        Mail::send([], ['data' => $data], function($message) use ($data) {
            // $message->to('team@abcdsales.com.bd', 'ABCD Sales')->subject('New Member Signup Notification');
           $message->to($data['memail'], 'ABCD Sales')->subject('Registration Successful');
           $message->from("noreply@abcdsales.com.bd",'ABCD Sales');
             $message->setBody('<h3>Welcome to ABCD Sales System</h3><p>Name: '.$data['name'].' </p><p>Phone: '.$data['phone'].'</p><p> Email: '.$data['memail'].'</p><p> Password: '.$data['pass'].'</p><p> Package: '.$data['package'].'</p><p> Login Url: '.$data['logurl'].'</p><p style="text-align:center"> Copyright: '.$data['courl'].'</p>','text/html');
         });

        return  $usercreate;
      }
    }
    }

    public function storeTeam(Request $request)
    {
        $auser=Auth::user();
        // Log::info( "authnnnnnnnnnnnn ===>" . $auser);
         $now = Carbon::now();
         $last_seen = Carbon::parse($auser->last_seen_at);
         $absence = $last_seen->diffInMinutes($now,true);
         //Log::info( "absence ===>" . $absence);
         if($absence > config('session.lifetime')) {
             User::where('id', $auser->id)->update(['islogin' => 0]);
 
            Auth::logout();
            return response()->json([
             'mgs' => 'logout'
            ]);
        }
        else{
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'usertype' => 'required',
            'telephone' => 'required',
            'password' => 'required',
            //'isactive' => 'required',
        ]);
        $emailExist = User::where('email', $request['email'])->first();
        $phoneExist = User::where('telephone', $request['telephone'])->first();
        $userinfo = User::where('id', $request['systemid'])->first();

        $teamcount = User::where('systemid', $request['systemid'])->where('isactive', true)->count();
        Log::info( "teamcount ===>" .$teamcount);
        $adminuserpassword=Hash::make('@ABCU789');
            if(!$phoneExist)
            {
            $usercreate= User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'dialcode' => $request['dialcode'],
            'telephone' => $request['telephone'],
            'password' => Hash::make($request['password']),
            'adminuserpassword' => $adminuserpassword,
            'usertype' => $request['usertype'],
            'systemid' => $request['systemid'],
            ]);

            $userID = $usercreate->id;
            $companylimit=$userinfo->companylimit;
            $entrylimit=$userinfo->entrylimit;
            $orderlimit=$userinfo->orderlimit;
            $teamlimit=$userinfo->teamlimit;
            $subscriptiondate=$userinfo->subscriptiondate;
            $subscriptionstartdate=$userinfo->subscriptionstartdate;
            Log::info( "usertype ===>" .$request['usertype']);
            User::where('id', $userID)->update(['companylimit' =>$companylimit,'entrylimit' => $entrylimit,'subscriptionstartdate' => $subscriptionstartdate, 'subscriptiondate' => $subscriptiondate,'teamlimit' => $teamlimit,'orderlimit' => $orderlimit]);
            return  $userID;
        }
     }
    }
    public function saveTeamCompany(Request $request)
    {
        $lastuserID = $request->teamId;
        Usercompany::where('userid', $lastuserID)->delete();
        //$req = $request->all();
       // Log::info( $req);
        $companyInfo = $request->companyInfo;
        Log::info( "teamid ===>" . $lastuserID);
        foreach ($companyInfo as $ci) {
            Log::info( "Company Areay". $ci );
            $branchid = Branch::where('companyid', $ci)->first();
            $usercreate= Usercompany::create([
                'userid' => $lastuserID,
                'companyid' => $ci,
                'branchid' => $branchid->id,
             ]);
        }

        $comdata = Usercompany::where('userid', $lastuserID)->first();
        User::where('id', $lastuserID)->update(['companyid' => $comdata->companyid, 'branchid' =>$comdata->branchid]);
        return  $lastuserID;
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();

        $auser=Auth::user();
         Log::info( "authnhh ===>" .$auser->id);
         $now = Carbon::now();
         $last_seen = Carbon::parse($userinfo->last_seen_at);
         $absence = $last_seen->diffInMinutes($now,true);
         Log::info( "absence ===>" . $absence);
         if($absence > config('session.lifetime')) {
             User::where('id', $auser->id)->update(['islogin' => 0]);
 
            Auth::logout();
            return response()->json([
             'mgs' => 'logout'
            ]);
        }
        else{
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'usertype' => 'required',
            'isactive' => 'required',
        ]);

        $user = User::findOrFail($id);
       // $datefrom=date('Y-m-d', strtotime($request['deliverydate']));
        $user->update($request->all());
        $status = $request->header('StatusKey');
       // $adminuserpassword=Hash::make('@ABCU789');
        Log::info( "status ===>" . $status);
        if($status === 'team')
        {
            Log::info( "sytemid ===>" . $user['systemid']);
            $userinfo = User::where('id', $user['systemid'])->first();

            $companylimit=$userinfo->companylimit;
            $entrylimit=$userinfo->entrylimit;
            $orderlimit=$userinfo->orderlimit;
            $teamlimit=$userinfo->teamlimit;
            $subscriptiondate=$userinfo->subscriptiondate;
            $subscriptionstartdate=$userinfo->subscriptionstartdate;
           // Log::info( "usertype ===>" .$request['usertype']);
            User::where('id', $request['id'])->update(['companylimit' =>$companylimit,'entrylimit' => $entrylimit, 'subscriptiondate' => $subscriptiondate,'subscriptionstartdate' => $subscriptionstartdate,'teamlimit' => $teamlimit,'orderlimit' => $orderlimit,'adminuserpassword' =>$adminuserpassword]);
        }
        return response()->json([
            'mgs' => 'success'
           ]);
    }
       
    }

    public function destroy(Request $request,$id)
    {
       
        $status = $request->header('StatusKey');
        Log::info( "Status ===>" . $status);
        $user = User::findOrFail($id);

        if($status === 'inactive')
        {
            $user->where('id', $id)->update(['isactive' => false]);
        }
        if($status === 'active')
        {
            $user->where('id', $id)->update(['isactive' => true]);
        }
        if($status === 'del')
        {
        $user->delete();
        
        }
        return response()->json([
            'mgs' => 'success'
           ]);
    
    }

    public function getteam(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'superadmin')
        {
        return User::orderby('id', 'asc')->where('usertype','team')->get();
        }
        else{
            return User::with('cominfo')->orderby('id', 'asc')->where('systemid', $userinfo->systemid)->where('usertype','team')->get();
        }
    }

    public function getcompany()
    {
        return User::orderby('id', 'asc')->where('usertype', 'company')->where('isactive', true)->get();
    }

    public function getbranchcontactperson()
    {
        return User::orderby('id', 'asc')->where('usertype', 'branch')->where('isactive', true)->get();
    }

    public function getuser($id)
    {
        return User::where('id', $id)->get();
    }

     // update profile
     public function updateprofile($id, Request $request)
     {
         $profiledata = User::find($id);
         $profiledata->update($request->all());
         return response()->json('The Profile successfully updated');
     }
     public function getadminuser()
     {
         return User::orderby('id', 'asc')->where([['usertype', '!=' , 'team'],['usertype', '!=' , 'business partner'] ])->orWhereNull('usertype')->where('isactive', true)->get();
     }

     public function getswitchcompany(Request $request)
     {
         $systemid = base64_decode($request->header('sessionKey'));
         Log::info( "Systemid ===>" . $systemid);
         $userinfo = User::where('id', $systemid)->first();
         return Usercompany::with('teamcompanyname')->orderby('id', 'asc')->where('userid', $systemid)->get();
     }
     public function updateSwitchCompany(Request $request, $id)
     {
         $systemid = base64_decode($request->header('sessionKey'));
         $usercompany = Usercompany::where('userid', $systemid)->where('companyid', $id)->first();
         Log::info( "usercompany ===>" . $usercompany);

         User::where('id', $systemid)->update(['companyid' => $usercompany->companyid, 'branchid' =>$usercompany->branchid]);
        return $usercompany->companyid;
     }
     public function searchUser(Request $request)
     {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
            if($userinfo->usertype == 'superadmin')
            { 
            $data= User::where('usertype',$request['usertype']);
            if($request['telephone'] != "" ){
                $data->where('telephone', $request['telephone']);
            }
            $searchrecord=$data->orderby('id', 'asc')->paginate(10);
            return $searchrecord;
            }
     }

     public function changeUserPassword(Request $request)
     {
        $request->validate([
            'oldpassword' => ['required'],
            'password' => ['required'],
            'confirmpassword' => ['required'],

        ]);
        $inputs = $request->all();
        Log::info( $inputs);
         $userinfo = User::where('id', $request['uid'])->first();
       // Log::info( "7 days after ===>" . $userinfo->password);
       if($request['password'] === $request['confirmpassword']){
        if (Hash::check($request['oldpassword'], $userinfo->password)) {
             User::where('id', $request['uid'])->update(['password' =>Hash::make($request->password)]);
             return response()->json([
                'mgs'   => 'success',
                //'user' => $username,
              ]);
         } else {
            return response()->json([
                'mgs'   => 'not',
                //'user' => $username,
              ]);
         }
        }
        else{
            return response()->json([
                'mgs'   => 'notmatch',
                //'user' => $username,
              ]);
        }
     }
     public function changeUserPasswordAdmin(Request $request)
     {
        $request->validate([
            //'oldpassword' => ['required'],
            'newpassword' => ['required'],
            'confirmpassword' => ['required'],

        ]);
        $inputs = $request->all();
        Log::info( $inputs);
         $userinfo = User::where('id', $request['id'])->first();
       // Log::info( "7 days after ===>" . $userinfo->password);
       if($request['newpassword'] === $request['confirmpassword']){

             User::where('id', $request['id'])->update(['password' =>Hash::make($request->newpassword)]);
             return response()->json([
                'mgs'   => 'success',
                //'user' => $username,
              ]);
        }
        else{
            return response()->json([
                'mgs'   => 'notmatch',
                //'user' => $username,
              ]);
        }
     }
     public function changeUserPasswordAdmin1(Request $request)
     {
        $request->validate([
            //'oldpassword' => ['required'],
            'newpassword' => ['required'],
            'confirmpassword' => ['required'],

        ]);
        $inputs = $request->all();
        Log::info( $inputs);
         $userinfo = User::where('id', $request['id'])->first();
       // Log::info( "7 days after ===>" . $userinfo->password);
       if($request['newpassword'] === $request['confirmpassword']){

             User::where('id', $request['id'])->update(['adminuserpassword' =>Hash::make($request->newpassword)]);
             return response()->json([
                'mgs'   => 'success',
                //'user' => $username,
              ]);
        }
        else{
            return response()->json([
                'mgs'   => 'notmatch',
                //'user' => $username,
              ]);
        }
     }
}
