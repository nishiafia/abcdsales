<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use App\Variationlabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class VariationlabelController extends Controller
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
        $data = Variationlabel::orderby('id', 'asc')->paginate(15);
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            $data = Variationlabel::with(array('companydata'=>function($query){
                $query->select('id','companyname');}))->where('systemid', $userinfo->systemid)->orderby('id', 'asc')->paginate(15);
        }
        if($userinfo->usertype == 'team'){
            $data = Variationlabel::with(array('companydata'=>function($query){
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

         $data=DB::table("select * from thanademo");
         foreach ($data as $list) {
            echo $list;
        }
      /*  
        $this->validate($request, [
            'vlabel' => 'required',
        ]);
            $vlabel = Variationlabel::where('vlabel', $request['vlabel'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->first();
        if(!$vlabel)
        {
            return Variationlabel::create([
                'vlabel' => $request['vlabel'],
                'systemid' => $request['systemid'],
                'entryid' => $request['entryid'],
                'companyid' => $request['companyid'],
                'branchid' => $request['branchid'],
             ]);
        }*/

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
            'vlabel' => 'required',
        ]);
        $pcolor = Variationlabel::findOrFail($id);
        if($request['vlabel'] !== $pcolor->vlabel)
        {
            $pcolorexist = Variationlabel::where('vlabel', $request['vlabel'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->first();
            if(!$pcolorexist){
                $pcolor->update($request->all());
                return response()->json('new');
            }
        }
        else{
            $pcolor->update($request->all());
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
        $pcolor = Variationlabel::findOrFail($id);
        if($pcolor->isactive == 1)
        {
             $pcolor->where('id', $id)->update(['isactive' => false]);
        }
        else{
             $pcolor->where('id', $id)->update(['isactive' => true]);
        }
        return response()->json([
         'message' => 'Color deleted successfully'
        ]);
    }

    public function getvariationlabel(Request $request)
    {
            $systemid = base64_decode($request->header('sessionKey'));
            $userinfo = User::where('id', $systemid)->first();
            Log::info( "userinfo1111111111111 ===>" . $userinfo->usertype);
            if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            return Variationlabel::select('id','vlabel')->orderby('id', 'asc')->where('systemid', $userinfo->systemid)->where('isactive',true)->get();
            }
            if($userinfo->usertype == 'team'){
                return Variationlabel::select('id','vlabel')->orderby('id', 'asc')->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('isactive',true)->get();
                }
    }
}
