<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Groupcode;
use Illuminate\Http\Request;

class GroupcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Groupcode::orderby('id', 'asc')->paginate(5);

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
            'gcode' => 'required',
            'gtitle' => 'required',
        ]);
        $code = Groupcode::where('gcode', $request['gcode'])->first();
        $title = Groupcode::where('gtitle', $request['gtitle'])->first();
        if(!$code && !$title)
        {
            return Groupcode::create([
                'gcode' => $request['gcode'],
                'gtitle' => $request['gtitle'],
               
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
            'gcode' => 'required',
            'gtitle' => 'required',
        ]);
        $groupdata = Groupcode::findOrFail($id);
       
        if($request['gcode'] !== $groupdata->gcode || $request['gtitle'] !== $groupdata->gtitle)
        { 
            $groupcode = Groupcode::where('gcode', $request['gcode'])->first();
            $grouptitle = Groupcode::where('gtitle', $request['gtitle'])->first();
            if(!$groupcode || !$grouptitle){
               
                $groupdata->update($request->all());
                return response()->json('new');
            }
        }
        else{
            return response()->json('same');
            $groupdata->update($request->all());
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
        $groupdata = Groupcode::findOrFail($id);
        if($groupdata->isactive == 1)
        {
            $groupdata->where('id', $id)->update(['isactive' => false]);
        }
        else{
            $groupdata->where('id', $id)->update(['isactive' => true]);   
        }
        return response()->json([
         'message' => 'Code deleted successfully'
        ]);
    }
}
