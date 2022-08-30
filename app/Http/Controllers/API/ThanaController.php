<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Thana;
use App\District;
use App\Companytype;
use App\Partner;
use Illuminate\Http\Request;

class ThanaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Thana::with('districtdata')->orderby('id', 'asc')->paginate(10);

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
            'thananame' => 'required',
        ]);

            //$sizename = Thana::where('thananame', $request['thananame'])->first();
        //if(!$sizename)
        //{
            return Thana::create([
                'thananame' => $request['thananame'],
                'districtid' => $request['districtid'],
             ]);
        //}
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
            'thananame' => 'required',
        ]);
        $thana = Thana::findOrFail($id);

       /* if($request['psize'] !== $size->psize)
        {
            $sizename = Productsize::where('psize', $request['psize'])->first();
            if(!$sizename){*/
                $thana->update($request->all());
                return response()->json('new');
            /*}
        }
        else{
            return response()->json('same');
            $size->update($request->all());
        }*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $thana = Thana::findOrFail($id);
        if($thana->isactive == 1)
        {
            $thana->where('id', $id)->update(['isactive' => false]);
        }
        else{
            $thana->where('id', $id)->update(['isactive' => true]);
        }
        return response()->json([
         'message' => 'Thana deleted successfully'
        ]);
    }

    public function getcity()
    {

        return Thana::orderby('id', 'asc')->where('isactive', true)->get();
    }
    public function getcompanytype()
    {
        return Companytype::orderby('id', 'asc')->where('isactive', true)->get();
    }
    public function getpartnertype()
    {
        return Partner::orderby('id', 'asc')->where('isactive', true)->get();
    }
}
