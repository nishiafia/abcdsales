<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Branch;
//use App\User;
use Illuminate\Http\Request;
use DB;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        DB::connection()->enableQueryLog();

       /* $data = Branch::leftJoin('users', function($join) {
            $join->on('users.id', '=', 'branches.branch_contact_person');
               // ->orOn('users.id', '=', 'branches.branch_supervisor');
        })
       //->selectRaw(DB::raw('group_concat(branches.id)'))
       ->where('branches.isactive', true)
        ->orderby('branches.id', 'asc')
        //->groupBy('branches.id')
        //->distinct('branches.id')
        ->paginate(5);*/

        $data = Branch::with('contact_person')
                ->with('supervisor')
                ->orderby('branches.id', 'asc')
                ->paginate(5);
      
        return response()->json($data);
        dd(DB::getQueryLog());
           
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
            'branchname' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'branch_contact_person' => 'required',
            'branch_supervisor' => 'required',
            'isactive' => 'required',
        ]);
        $brname = Branch::where('branchname', $request['branchname'])->first();
        if(!$brname)
        {
            return Branch::create([
                'branchname' => $request['branchname'],
                'address' => $request['address'],
                'phone' => $request['phone'],
                'branch_contact_person' => $request['branch_contact_person'],
                'branch_supervisor' => $request['branch_supervisor'],
                'isactive' => $request['isactive'],
               
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
            'branchname' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'branch_contact_person' => 'required',
            'branch_supervisor' => 'required',
            'isactive' => 'required',
        ]);
        $branch = Branch::findOrFail($id);

        $branch->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->where('id', $id)->update(['isactive' => false]);
        return response()->json([
         'message' => 'Branch Inactive successfully'
        ]);
    }
}
