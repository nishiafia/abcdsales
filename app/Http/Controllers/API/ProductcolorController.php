<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Productcolor;
use Illuminate\Http\Request;

class ProductcolorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Productcolor::orderby('id', 'asc')->paginate(5);

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

      
            $pcolor = Productcolor::where('colorname', $request['colorname'])->first();
        if(!$pcolor)
        {
            return Productcolor::create([
                'colorname' => $request['colorname'],
               
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
            $pcolorexist = Productcolor::where('colorname', $request['colorname'])->first();
            if(!$pcolorexist){
                $pcolor->update($request->all());
                return response()->json('new');
            }
        }
        else{
            return response()->json('same');
            $pcolor->update($request->all());
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
}
