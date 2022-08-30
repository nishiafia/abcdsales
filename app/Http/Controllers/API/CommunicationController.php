<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Communication;
use App\Subcommunication;
use App\User;

class CommunicationController extends Controller
{
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
       
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional')
        {
            $data = Communication::select('communications.*','customers.customername','customers.telephone')
            ->join('customers', 'communications.vendorid', '=', 'customers.id')->where('communications.systemid', $userinfo->systemid)->orderby('communications.id', 'desc')->paginate(20);

            return response()->json($data);
         }
         if($userinfo->usertype == 'team')
         {
            $data = Communication::select('communications.*','customers.customername','customers.telephone')
            ->join('customers', 'communications.vendorid', '=', 'customers.id')->where('communications.systemid', $userinfo->systemid)->where('communications.companyid', $userinfo->companyid)->orderby('communications.id', 'desc')->paginate(20);

           //  $data = Communication::with('vendordata')->with('pname')->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->orderby('id', 'desc')->paginate(20);
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
            'vendorid' => 'required',
            'cmessage' => 'required',
            'csourse' => 'required',
            //'appointmentdate' => 'required'
        ]);
        $apptdate="";
        if($request['appointmentdate'] != '')
        {
            $apptdate = $request['appointmentdate'];
           // Log::info( "request1 ===>" . $apptdate);
        }
        else{
            $apptdate = NULL;
            //Log::info( "request2 ===>" . $apptdate);
        }
        $activity = Communication::create([
            'vendorid' => $request['vendorid'],
            'partnertype' => $request['partnertype'],
            'cname' => $request['cname'],
            'cmessage' => $request['cmessage'],
            'csourse' => $request['csourse'],
            'appointmentdate' => $apptdate,
            'systemid' => $request['systemid'],
            'entryid' => $request['entryid'],
            'companyid' => $request['companyid'],
            'branchid' => $request['branchid'],
            'activitystatus' => $request['activitystatus'],
         ]);
         $lasactivityID = $activity->id;
         $subactivity = Subcommunication::create([
            'vendorid' => $request['vendorid'],
            'partnertype' => $request['partnertype'],
            'activityid' => $lasactivityID,
            'cname' => $request['cname'],
            'cmessage' => $request['cmessage'],
            'csourse' => $request['csourse'],
            'appointmentdate' => $apptdate,
            'systemid' => $request['systemid'],
            'baseid' => 1,
            'activitystatus' => $request['activitystatus'],
         ]);
         return response()->json([
            'message' => 'done'
           ]);
    }
    public function createsubcommunication(Request $request)
    {
        $this->validate($request, [
            'vendorid' => 'required',
            'cmessage' => 'required',
            'csourse' => 'required',
            //'appointmentdate' => 'required'
        ]);
       // Log::info( "date ===>" . $request['appointmentdate']);
        $apptdate="";
        if($request['appointmentdate'] != '')
        {
            $apptdate = $request['appointmentdate'];
           // Log::info( "request1 ===>" . $apptdate);
        }
        else{
            $apptdate = NULL;
            //Log::info( "request2 ===>" . $apptdate);
        }
        $subactivity = Subcommunication::create([
            'vendorid' => $request['vendorid'],
            'partnertype' => $request['partnertype'],
            'activityid' => $request['activityid'],
            'cname' => $request['cname'],
            'cmessage' => $request['cmessage'],
            'csourse' => $request['csourse'],
            'appointmentdate' => $apptdate,
            'systemid' => $request['systemid'],
            'baseid' => 0,
            'activitystatus' => $request['activitystatus'],
         ]);
        return $request['activityid'];
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
            'vendorid' => 'required',
            'cmessage' => 'required',
            'csourse' => 'required',
            'appointmentdate' => 'required'
        ]);
        $scom = Subcommunication::findOrFail($id);

         $apptdate="";
         if($request['appointmentdate'] != '')
         {
             $apptdate = $request['appointmentdate'];
            // Log::info( "request1 ===>" . $apptdate);
         }
         else{
             $apptdate = NULL;
             //Log::info( "request2 ===>" . $apptdate);
         }

         $scom->where('id', $id)->update(['vendorid' => $request['vendorid'],
                                            'partnertype' => $request['partnertype'],
                                            'activityid' => $request['activityid'],
                                            'cname' => $request['cname'],
                                            'cmessage' => $request['cmessage'],
                                            'csourse' => $request['csourse'],
                                            'appointmentdate' => $apptdate,
                                            'systemid' => $request['systemid'],
                                            'baseid' => $request['baseid'],
                                            'activitystatus' => $request['activitystatus']
                                                   ]);
       if($request['baseid'] === 1)
       {
        $com = Communication::findOrFail($request['activityid']);
        $com->where('id', $request['activityid'])->update(['vendorid' => $request['vendorid'],
                                            'partnertype' => $request['partnertype'],
                                            'cname' => $request['cname'],
                                            'cmessage' => $request['cmessage'],
                                            'csourse' => $request['csourse'],
                                            'appointmentdate' => $apptdate,
                                            'systemid' => $request['systemid'],
                                            'activitystatus' => $request['activitystatus']
                                                   ]);
       }


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
        $com = Communication::findOrFail($id);

        if($status === 'close')
        {
            $com->where('id', $id)->update(['activitystatus' => 2]);
            Subcommunication::where('activityid', $id)->update(['activitystatus' => 2]);
        }
        if($status === 'open')
        {
            $com->where('id', $id)->update(['activitystatus' => 1]);
            Subcommunication::where('activityid', $id)->update(['activitystatus' => 1]);
        }
        if($status === 'del')
        {
        $com->delete();
        return response()->json([
         'message' => 'successfully'
        ]);
        }
    }
    public function getcustomercommunication(Request $request,$id)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        Log::info( "systemid ===>" . $systemid);
        if($userinfo->usertype == 'superadmin')
        {
            $data = Communication::orderby('id', 'asc')->paginate(20);

            return response()->json($data);
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional')
        {
            $data = Communication::with('vendordata')->where('systemid', $userinfo->systemid)->where('vendorid', $id)->orderby('id', 'desc')->paginate(20);

            return response()->json($data);
         }
         if($userinfo->usertype == 'team')
         {
             $data = Communication::with('vendordata')->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('vendorid', $id)->orderby('id', 'desc')->paginate(20);
             return response()->json($data);
          }
    }
    public function getsubactivity(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $activityid = $request->header('ActivityId');
        $userinfo = User::where('id', $systemid)->first();
        Log::info( "systemid ===>" . $systemid);
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional')
        {
            $data = Subcommunication::where('systemid', $userinfo->systemid)->where('activityid', $activityid)->orderby('id', 'desc')->get();

            return response()->json($data);
         }
         if($userinfo->usertype == 'team')
         {
             $data = Subcommunication::where('systemid', $userinfo->systemid)->where('activityid', $activityid)->orderby('id', 'desc')->get();
             return response()->json($data);
          }
    }
    public function getcurrentactivity(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $activityid = $request->header('ActivityId');
        $userinfo = User::where('id', $systemid)->first();
        Log::info( "systemid ===>" . $systemid);
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional')
        {
            $data = Communication::where('systemid', $userinfo->systemid)->where('id', $activityid)->orderby('id', 'desc')->first();

            return response()->json($data);
         }
         if($userinfo->usertype == 'team')
         {
             $data = Communication::where('systemid', $userinfo->systemid)->where('id', $activityid)->orderby('id', 'desc')->first();
             return response()->json($data);
          }
    }

    public function searchActivity(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        Log::info( "systemid ===>" . $systemid);
        $search=$request['searchvalue'];
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional')
        {
            $data = Communication::select('communications.*','customers.customername','customers.telephone')
            ->join('customers', 'communications.vendorid', '=', 'customers.id');
            if($request['searchvalue'] != "")
            {
            $data->where(function($q) use ($search) {
            $q->where('communications.cname', 'like', '%' . $search . '%')
            ->orWhere('communications.appointmentdate', 'like', '%' . $search . '%')
            ->orWhere('customers.telephone', 'like', '%' . $search .'%') ;
            });
           }
            if($request['partnertype'] != '')
            {
                $data->where('communications.partnertype', $request['partnertype']);
            }
            $resp=$data->where('communications.systemid', $userinfo->systemid)->where('communications.activitystatus', $request['activitystatus'])->orderby('communications.id', 'desc')->paginate(20);
            return response()->json($resp);
         }
         if($userinfo->usertype == 'team')
         {

            $data = Communication::select('communications.*','customers.customername','customers.telephone')
            ->join('customers', 'communications.vendorid', '=', 'customers.id');
            if($request['searchvalue'] != "")
            {
            $data->where(function($q) use ($search) {
            $q->where('communications.cname', 'like', '%' . $search . '%')
            ->orWhere('communications.appointmentdate', 'like', '%' . $search . '%')
            ->orWhere('customers.telephone', 'like', '%' . $search .'%') ;
            });
           }
            if($request['partnertype'] != '')
            {
                $data->where('communications.partnertype', $request['partnertype']);
            }
            $resp=$data->where('communications.systemid', $userinfo->systemid)->where('communications.activitystatus', $request['activitystatus'])->where('communications.companyid', $userinfo->companyid)->orderby('communications.id', 'desc')->paginate(20);
            return response()->json($resp);

          }
    }

}
