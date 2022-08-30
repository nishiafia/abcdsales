<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Variationlabel;
use App\Variation;
use Illuminate\Support\Facades\Log;
class VariationController extends Controller
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
        $data = Variation::orderby('id', 'asc')->paginate(15);
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            $data = Variation::with(array('companydata'=>function($query){
                $query->select('id','companyname');}))->with(array('labeldata'=>function($query){
                    $query->select('id','vlabel');}))->where('systemid', $userinfo->systemid)->orderby('id', 'asc')->paginate(15);
        }
        if($userinfo->usertype == 'team'){
            $data = Variation::with(array('companydata'=>function($query){
                $query->select('id','companyname');}))->with(array('labeldata'=>function($query){
                    $query->select('id','vlabel');}))->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->orderby('id', 'asc')->paginate(15);
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
            'vlabelid' => 'required',
            'variationname' => 'required',
        ]);
            $vlabel = Variation::where('variationname', $request['variationname'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->first();
        if(!$vlabel)
        {
            return Variation::create([
                'vlabelid' => $request['vlabelid'],
                'variationname' => $request['variationname'],
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
            'vlabelid' => 'required',
            'variationname' => 'required',
        ]);

        $vlabel = Variation::findOrFail($id);
        if($request['variationname'] !== $vlabel->variationname)
        {
            $vlabelexist = Variation::where('variationname', $request['variationname'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->first();
            if(!$vlabelexist){
                $vlabel->update($request->all());
                return response()->json('new');
            }
        }
        else{
            $vlabel->update($request->all());
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
        $vlabel = Variation::findOrFail($id);
        if($vlabel->isactive == 1)
        {
             $vlabel->where('id', $id)->update(['isactive' => false]);
        }
        else{
             $vlabel->where('id', $id)->update(['isactive' => true]);
        }
        return response()->json([
         'message' => 'Variation deleted successfully'
        ]);
    }

    public function getvariation(Request $request)
    {
            $systemid = base64_decode($request->header('sessionKey'));
            $userinfo = User::where('id', $systemid)->first();
            Log::info( "userinfo1111111111111 ===>" . $userinfo->usertype);
                return Variation::with(array('labeldata'=>function($query){
                    $query->select('id','vlabel');
                }))->orderby('vlabelid', 'asc')->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('isactive',true)->get()->groupBy('vlabelid');
    }
}
