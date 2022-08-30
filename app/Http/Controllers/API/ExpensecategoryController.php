<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Expensecategory;
use App\Bank;
use App\User;
use Illuminate\Http\Request;

class ExpensecategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
       // Log::info( "systemid ===>" . $systemid);
       $userinfo = User::where('id', $systemid)->first();
       if($userinfo->usertype == 'superadmin')
       {
        $data = Expensecategory::with(array('companydata'=>function($query){
            $query->select('id','companyname');}))->orderby('id', 'asc')->paginate(15);
       }
       if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
        $data = Expensecategory::with(array('companydata'=>function($query){
            $query->select('id','companyname');}))->where('systemid', $userinfo->systemid)->orderby('id', 'asc')->paginate(15);
       }
       if($userinfo->usertype == 'team'){
        $data = Expensecategory::with(array('companydata'=>function($query){
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
            'ecategoryname' => 'required',
        ]);
        //$systemid = base64_decode($request->header('sessionKey'));
        $excat = Expensecategory::where('ecategoryname', $request['ecategoryname'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->first();
       // $title = Groupcode::where('gtitle', $request['gtitle'])->first();
        if(!$excat)
        {
            return Expensecategory::create([
                'ecategoryname' => $request['ecategoryname'],
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
            'ecategoryname' => 'required',
        ]);
        $excat = Expensecategory::findOrFail($id);
        if($request['ecategoryname'] !== $excat->ecategoryname)
        {
            $excatexist=Expensecategory::where('ecategoryname', $request['ecategoryname'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->first();
            if(!$excatexist){
                $excat->update($request->all());
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
    public function destroy(Request $request,$id)
    {
        $status = $request->header('StatusKey');
        $excat = Expensecategory::findOrFail($id);

        if($status === 'inactive')
        {
            $excat->where('id', $id)->update(['isactive' => false]);
        }
        if($status === 'active')
        {
            $excat->where('id', $id)->update(['isactive' => true]);
        }
        if($status === 'del')
        {
        $excat->delete();
        return response()->json([
         'message' => 'Expense Category deleted successfully'
        ]);
        }
    }

    public function getexcat(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
       // Log::info( "systemid ===>" . $systemid);
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
        $data = Expensecategory::where('systemid', $userinfo->systemid)->orderby('id', 'asc')->where('isactive', true)->get();
        }
        if($userinfo->usertype == 'team'){
            $data = Expensecategory::where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->orderby('id', 'asc')->where('isactive', true)->get();
            }
    	return response()->json($data);
    }
    public function getbank(Request $request)
    {
        $data = Bank::orderby('id', 'asc')->get();

    	return response()->json($data);
    }
}
