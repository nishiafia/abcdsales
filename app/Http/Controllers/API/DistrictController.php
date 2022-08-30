<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\District;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = District::orderby('id', 'asc')->paginate(20);

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
            'districtname' => 'required',
        ]);

            //$sizename = Thana::where('thananame', $request['thananame'])->first();
        //if(!$sizename)
        //{
            return District::create([
                'districtname' => $request['districtname'],
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
            'districtname' => 'required',
        ]);
        $dis = District::findOrFail($id);

       /* if($request['psize'] !== $size->psize)
        {
            $sizename = Productsize::where('psize', $request['psize'])->first();
            if(!$sizename){*/
                $dis->update($request->all());
                return response()->json('new');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dis = District::findOrFail($id);
        if($dis->isactive == 1)
        {
            $dis->where('id', $id)->update(['isactive' => false]);
        }
        else{
            $dis->where('id', $id)->update(['isactive' => true]);
        }
        return response()->json([
         'message' => 'District deleted successfully'
        ]);
    }
    public function getdistrict()
    {

        return District::orderby('id', 'asc')->where('isactive', true)->get();
    }
}
