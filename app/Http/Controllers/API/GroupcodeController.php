<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Groupcode;
use App\User;
use App\Productunit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class GroupcodeController extends Controller
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
        if($userinfo->usertype == 'superadmin')
        {
        $data = Groupcode::with(array('companydata'=>function($query){
            $query->select('id','companyname');}))->orderby('id', 'asc')->paginate(15);
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            $data = Groupcode::with(array('companydata'=>function($query){
                $query->select('id','companyname');}))->where('systemid', $userinfo->systemid)->orderby('id', 'asc')->paginate(15);
        }
        if($userinfo->usertype == 'team'){
            $data = Groupcode::with(array('companydata'=>function($query){
                $query->select('id','companyname');}))->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->orderby('id', 'asc')->paginate(15);
        }
    	return response()->json($data);
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
            'gcode' => 'required',
            'gtitle' => 'required',
        ]);
        $code = Groupcode::where('gcode', $request['gcode'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->first();
       // $title = Groupcode::select('gtitle')->where('gtitle', $request['gtitle'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->first();
       Log::info( "code ===>" . $code);
       //Log::info( "title ===>" . $title->gtitle);
       if(!$code)
        {
           return Groupcode::create([
                'gcode' => $request['gcode'],
                'gtitle' => $request['gtitle'],
                'systemid' => $request['systemid'],
                'entryid' => $request['entryid'],
                'companyid' => $request['companyid'],
                'branchid' => $request['branchid'],
             ]);
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
            'gcode' => 'required',
            'gtitle' => 'required',
        ]);
        $groupdata = Groupcode::findOrFail($id);
        if($request['gcode'] !== $groupdata->gcode)
        {
            $groupcode = Groupcode::where('gcode', $request['gcode'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->first();
          //  $grouptitle = Groupcode::where('gtitle', $request['gtitle'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->first();
            if(!$groupcode){
                $groupdata->update($request->all());
                return response()->json('new');
            }
        }
        else{
            $groupdata->update($request->all());
            return response()->json('same');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $groupdata = Groupcode::findOrFail($id);
        if($groupdata->isactive == 1)
        {
            $groupdata->where('id', $id)->update(['isactive' => false]);
        }
        else{
            $groupdata->where('id', $id)->update(['isactive' => true]);
        }
        return response()->json([
         'message' => 'Code deleted successfully'
        ]);
    }

    public function getinventorygroup(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'superadmin')
        {
        return Groupcode::orderby('id', 'asc')->where('isactive',true)->get();
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            return Groupcode::orderby('id', 'asc')->where('systemid', $userinfo->systemid)->where('isactive',true)->get();
        }
        if($userinfo->usertype == 'team'){
            return Groupcode::orderby('id', 'asc')->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('isactive',true)->get();
        }
    }
    public function getgroupcode(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'superadmin')
        {
        return Groupcode::orderby('id', 'asc')->where('isactive',true)->get();
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            return Groupcode::orderby('id', 'asc')->where('systemid', $userinfo->systemid)->where('isactive',true)->get();
        }
        if($userinfo->usertype == 'team'){
            return Groupcode::orderby('id', 'asc')->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('isactive',true)->get();
        }
    }
    public function getunittype(Request $request)
    {
            return Productunit::orderby('id', 'asc')->get();
    }
}
