<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Communication;
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
        if($userinfo->usertype == 'superadmin')
        {
            $data = Communication::orderby('id', 'asc')->paginate(20);

            return response()->json($data);
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional')
        {
            $data = Communication::with('vendordata')->where('systemid', $userinfo->systemid)->orderby('id', 'desc')->paginate(20);

            return response()->json($data);
         }
         if($userinfo->usertype == 'team')
         {
             $data = Communication::with('vendordata')->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->orderby('id', 'desc')->paginate(20);
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
            'appointmentdate' => 'required'
        ]);

        $activity = Communication::create([
            'vendorid' => $request['vendorid'],
            'partnertype' => $request['partnertype'],
            'cname' => $request['cname'],
            'cmessage' => $request['cmessage'],
            'csourse' => $request['csourse'],
            'appointmentdate' => $request['appointmentdate'],
            'systemid' => $request['systemid'],
            'entryid' => $request['entryid'],
            'companyid' => $request['companyid'],
            'branchid' => $request['branchid'],
            'activitystatus' => $request['activitystatus'],
         ]);
         return response()->json([
            'message' => 'done'
           ]);
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
        $com = Communication::findOrFail($id);
        $com->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
}
