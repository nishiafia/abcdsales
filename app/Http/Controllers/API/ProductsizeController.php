<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Productsize;
use Illuminate\Http\Request;

class ProductsizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Productsize::orderby('id', 'asc')->paginate(5);

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
            'psize' => 'required',
        ]);

      
            $sizename = Productsize::where('psize', $request['psize'])->first();
        if(!$sizename)
        {
            return Productsize::create([
                'psize' => $request['psize'],
               
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
            'psize' => 'required',
        ]);
        $size = Productsize::findOrFail($id);
       
        if($request['psize'] !== $size->psize)
        { 
            $sizename = Productsize::where('psize', $request['psize'])->first();
            if(!$sizename){
                $size->update($request->all());
                return response()->json('new');
            }
        }
        else{
            return response()->json('same');
            $size->update($request->all());
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
        $size = Productsize::findOrFail($id);
        if($size->isactive == 1)
        {
            $size->where('id', $id)->update(['isactive' => false]);
        }
        else{
            $size->where('id', $id)->update(['isactive' => true]);   
        }
        return response()->json([
         'message' => 'Size deleted successfully'
        ]);
    }
}
