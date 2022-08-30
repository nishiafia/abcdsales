<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use App\Salesordercomment;
use Illuminate\Http\Request;

class SalesordercommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'comments' => 'required',
            
        ]);
        return Salesordercomment::create([
            'comments' => $request['comments'],
            'systemid' => $request['systemid'],
            'entryid' => $request['entryid'],
            'companyid' => $request['companyid'],
            'orderid' => $request['orderid'],
            'branchid' => $request['branchid'],
           
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
            'comments' => 'required',
            
        ]);
        $scommentdata = Salesordercomment::findOrFail($id);
        return $scommentdata->update($request->all());
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
       
        $scommentdata = Salesordercomment::findOrFail($id);

        if($status === 'inactive')
        {
            $scommentdata->where('id', $id)->update(['isactive' => false]);
        }
    }
}
