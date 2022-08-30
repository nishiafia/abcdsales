<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Deliveryagent;
use Illuminate\Support\Facades\Log;

class DeliveryagentController extends Controller
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
          $data = Deliveryagent::with(array('companydata'=>function($query){
            $query->select('id','companyname');}))->orderby('id', 'asc')->paginate(15);
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            $data = Deliveryagent::with(array('companydata'=>function($query){
                $query->select('id','companyname');}))->where('systemid', $userinfo->systemid)->orderby('id', 'asc')->paginate(15);
        }
        if($userinfo->usertype == 'team'){
            $data = Deliveryagent::with(array('companydata'=>function($query){
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
            'agentname' => 'required',
            'insiderate' => 'required',
            'outsiderate' => 'required',
        ]);
        $agentExists = Deliveryagent::where('agentname', $request['agentname'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->where('isactive', true)->first();
        if(!$agentExists)
        {
            return Deliveryagent::create([
                'agentname' => $request['agentname'],
                'insiderate' => $request['insiderate'],
                'outsiderate' => $request['outsiderate'],
                'insidecodcharge' => $request['insidecodcharge'],
                'outsidecodcharge' => $request['outsidecodcharge'],
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
            'agentname' => 'required',
            'insiderate' => 'required',
            'outsiderate' => 'required',
        ]);;
        $agentdata = Deliveryagent::findOrFail($id);
        if($request['agentname'] != $agentdata->agentname)
        {
            $agentrate = Deliveryagent::where('agentname', $request['agentname'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->where('isactive', true)->first();
            if(!$agentrate){
                $agentdata->update($request->all());
                return response()->json('new');
            }
        }
        else{
            $agentdata->update($request->all());
            return response()->json('same');
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
        $agentdata = Deliveryagent::findOrFail($id);

        if($status === 'inactive')
        {
            $agentdata->where('id', $id)->update(['isactive' => false]);
        }
        if($status === 'active')
        {
            $agentdata->where('id', $id)->update(['isactive' => true]);
        }
        if($status === 'del')
        {
        $agentdata->delete();
        return response()->json([
         'message' => 'Agent deleted successfully'
        ]);
        }
    }
    public function getagent(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'superadmin')
        {
            return Deliveryagent::orderby('id', 'asc')->select('id','agentname')->where('isactive',1)->get();
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            return Deliveryagent::orderby('id', 'asc')->select('id','agentname')->where('systemid', $userinfo->systemid)->where('isactive',1)->get();
        }
        if($userinfo->usertype == 'team'){
            return Deliveryagent::orderby('id', 'asc')->select('id','agentname')->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('isactive',1)->get();
        }
    }
    public function getdeliveryprice(Request $request,$id)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        //$delarea= $request->header('delArea');
       // Log::info( "delarea ===>" . $delarea);
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'superadmin')
        {
            return Deliveryagent::orderby('id', 'asc')->select('id','agentname')->where('isactive',1)->get();
        }
        else{
            return Deliveryagent::orderby('id', 'asc')->select('insiderate','insidecodcharge','outsiderate','outsidecodcharge')->where('systemid', $userinfo->systemid)->where('id', $id)->where('isactive',1)->get();
        }
    }
}
