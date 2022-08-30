<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Productcolor;
use App\Producttype;
use App\User;
use Illuminate\Http\Request;

class ProductcolorController extends Controller
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
        $data = Productcolor::orderby('id', 'asc')->paginate(15);
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            $data = Productcolor::with(array('companydata'=>function($query){
                $query->select('id','companyname');}))->where('systemid', $userinfo->systemid)->orderby('id', 'asc')->paginate(15);
        }
        if($userinfo->usertype == 'team'){
            $data = Productcolor::with(array('companydata'=>function($query){
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
            'colorname' => 'required',
        ]);
            $pcolor = Productcolor::where('colorname', $request['colorname'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->first();
        if(!$pcolor)
        {
            return Productcolor::create([
                'colorname' => $request['colorname'],
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
            'colorname' => 'required',
        ]);
        $pcolor = Productcolor::findOrFail($id);
        if($request['colorname'] !== $pcolor->colorname)
        {
            $pcolorexist = Productcolor::where('colorname', $request['colorname'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->first();
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
        $pcolor = Productcolor::findOrFail($id);
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

    public function getproductcolor(Request $request)
    {
            $systemid = base64_decode($request->header('sessionKey'));
            $userinfo = User::where('id', $systemid)->first();
            if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
            return Productcolor::orderby('id', 'asc')->where('systemid', $userinfo->systemid)->where('isactive',true)->get();
            }
            if($userinfo->usertype == 'team'){
                return Productcolor::orderby('id', 'asc')->where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('isactive',true)->get();
                }
    }
    public function getproducttype(Request $request)
    {
            return Producttype::orderby('id', 'asc')->get();
    }
}
