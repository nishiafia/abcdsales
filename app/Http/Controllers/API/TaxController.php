<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tax;
use App\User;
use Illuminate\Support\Facades\Log;

class TaxController extends Controller
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
          $data = Tax::with(array('companydata'=>function($query){
            $query->select('id','companyname');}))->orderby('id', 'asc')->paginate(10);
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            $data = Tax::with(array('companydata'=>function($query){
                $query->select('id','companyname');}))->where('systemid', $userinfo->systemid)->orderby('id', 'asc')->paginate(10);
        }
        if($userinfo->usertype == 'team'){
            $data = Tax::with(array('companydata'=>function($query){
                $query->select('id','companyname');}))->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->orderby('id', 'asc')->paginate(10);
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
            'taxrate' => 'required',
            'taxtitle' => 'required',
        ]);
        $dis = Tax::where('taxrate', $request['taxrate'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->where('isactive', true)->first();
        if(!$dis)
        {
            return Tax::create([
                'taxrate' => $request['taxrate'],
                'taxtitle' => $request['taxtitle'],
                'abbreviation' => $request['abbreviation'],
                'recoverable' => $request['recoverable'],
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
    public function show(Request $request,$id)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        // Log::info( "systemid ===>" . $systemid);
         $data = Tax::select('taxrate')->where('systemid', $systemid)->where('isactive', true)->where('id', $id)->first();
 
         return response()->json($data);
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
            'taxrate' => 'required',
            'taxtitle' => 'required',
        ]);
        $taxdata = Tax::findOrFail($id);
        if($request['taxrate'] != $taxdata->taxrate)
        {
            $txrate = Tax::where('taxrate', $request['taxrate'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->where('isactive', true)->first();
            if(!$txrate){
                $taxdata->update($request->all());
                return response()->json('new');
            }
        }
        else{
            $taxdata->update($request->all());
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
        $txrate = Tax::findOrFail($id);

        if($status === 'inactive')
        {
            $txrate->where('id', $id)->update(['isactive' => false]);
        }
        if($status === 'active')
        {
            $txrate->where('id', $id)->update(['isactive' => true]);
        }
        if($status === 'del')
        {
        $txrate->delete();
        return response()->json([
         'message' => 'Tax deleted successfully'
        ]);
        }
    }
    public function gettax(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
        $data = Tax::where('systemid', $userinfo->systemid)->where('isactive', true)->orderby('taxrate', 'asc')->get();
        }
        if($userinfo->usertype == 'team'){
            $data = Tax::where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('isactive', true)->orderby('taxrate', 'asc')->get();
            }
    	return response()->json($data);
    }
}
