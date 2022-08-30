<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Discount;
use App\User;
use Illuminate\Support\Facades\Log;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
      //  Log::info( "systemid ===>" . $systemid);
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'superadmin')
        {
        $data = Discount::orderby('id', 'asc')->paginate(10);
        }
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
        $data = Discount::with(array('companydata'=>function($query){
            $query->select('id','companyname');}))->where('systemid', $userinfo->systemid)->orderby('id', 'asc')->paginate(10);
        }
        if($userinfo->usertype == 'team'){
            $data = Discount::with(array('companydata'=>function($query){
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
            'discountrate' => 'required',
        ]);
        $dis = Discount::where('discountrate', $request['discountrate'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->where('isactive', true)->first();
        if(!$dis)
        {
            return Discount::create([
                'discountrate' => $request['discountrate'],
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
         $data = Discount::select('discountrate')->where('systemid', $systemid)->where('isactive', true)->where('id', $id)->first();
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
            'discountrate' => 'required',
        ]);

        $discountdata = Discount::findOrFail($id);
        if($request['discountrate'] != $discountdata->discountrate)
        {
            Log::info( "dis ===>" . $request['discountrate']);
            Log::info( "database ===>" . $discountdata->discountrate);

            $discountrate = Discount::where('discountrate', $request['discountrate'])->where('systemid', $request['systemid'])->where('companyid', $request['companyid'])->where('isactive', true)->first();
            if(!$discountrate){
                $discountdata->update($request->all());
                return response()->json('new');
            }
        }
        else{
            Log::info( "dis1 ===>" . $request['discountrate']);
            $discountdata->update($request->all());
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
        $disrate = Discount::findOrFail($id);

        if($status === 'inactive')
        {
            $disrate->where('id', $id)->update(['isactive' => false]);
        }
        if($status === 'active')
        {
            $disrate->where('id', $id)->update(['isactive' => true]);
        }
        if($status === 'del')
        {
        $disrate->delete();
        return response()->json([
         'message' => 'Discount deleted successfully'
        ]);
        }
    }
    public function getdiscount(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'basic' || $userinfo->usertype == 'standard' || $userinfo->usertype == 'professional'){
        $data = Discount::where('systemid', $userinfo->systemid)->where('isactive', true)->orderby('discountrate', 'asc')->get();
        }
        if($userinfo->usertype == 'team'){
            $data = Discount::where('systemid', $userinfo->systemid)->where('companyid', $userinfo->companyid)->where('isactive', true)->orderby('discountrate', 'asc')->get();
            }
    	return response()->json($data);
    }
}
