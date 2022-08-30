<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Vendor;
use App\User;
use Illuminate\Http\Request;

class VendorController extends Controller
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
            $data = Vendor::with('businesscategoryname')->orderby('id', 'asc')->paginate(10);

            return response()->json($data);
        }
        else{
            $data = Vendor::with('businesscategoryname')->where('systemid', $userinfo->systemid)->orderby('id', 'asc')->paginate(10);

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
            'vendorname' => 'required',
            'contactperson' => 'required',
            'telephone' => 'required',
            'address' => 'required',
            'businesscategory' => 'required',
        ]);
        return Vendor::create([
           'vendorname' => $request['vendorname'],
           'contactperson' => $request['contactperson'],
           'email' => $request['email'],
           'dialcode' => $request['dialcode'],
           'telephone' => $request['telephone'],
           'systemid' => $request['systemid'],
           'entryid' => $request['entryid'],
           'address' => $request['address'],
           'description' => $request['description'],
           'businesscategory' => $request['businesscategory'],
           'isactive' => $request['isactive'],
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
            'vendorname' => 'required',
            'contactperson' => 'required',
            'telephone' => 'required',
            'address' => 'required',
            //'isactive' => 'required',
        ]);

        $vendor = Vendor::findOrFail($id);

        if($request['vendorname'] !== $vendor->vendorname)
        {
            $vendorexist=Vendor::where('vendorname', $request['vendorname'])->where('systemid', $request['systemid'])->first();
            if(!$vendorexist){
                $vendor->update($request->all());
                return response()->json('new');
            }
        }
        else{
            $vendor->update($request->all());
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
        $vendor = Vendor::findOrFail($id);

        if($status === 'inactive')
        {
            $vendor->where('id', $id)->update(['isactive' => false]);
        }
        if($status === 'active')
        {
            $vendor->where('id', $id)->update(['isactive' => true]);
        }
        if($status === 'del')
        {
        $vendor->delete();
        return response()->json([
         'message' => 'Vendor deleted successfully'
        ]);
        }
    }

    public function getvendor(Request $request)
    {
        $systemid = base64_decode($request->header('sessionKey'));
        $userinfo = User::where('id', $systemid)->first();
        if($userinfo->usertype == 'superadmin')
        {
            return Vendor::orderby('id', 'asc')->select('id','vendorname')->where('isactive',1)->get();
        }
        else{
            return Vendor::orderby('id', 'asc')->select('id','vendorname')->where('systemid', $userinfo->systemid)->where('isactive',1)->get();
        }
    }

}
