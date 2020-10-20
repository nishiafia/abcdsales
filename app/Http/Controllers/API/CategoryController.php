<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $data = Category::orderby('id', 'asc')->paginate(10);

    	return response()->json($data);
    }

    public function store(Request $request)
    {   
        $this->validate($request, [
            'categoryname' => 'required',
        ]);
        $catname = Category::where('categoryname', $request['categoryname'])->first();
        if(!$catname)
        {
            return Category::create([
                'categoryname' => $request['categoryname'],
               
             ]);
        }
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {   
        $this->validate($request, [
            'categoryname' => 'required',
        ]);
        $category = Category::findOrFail($id);
       
        if($request['categoryname'] !== $category->categoryname)
        { 
            $catname = Category::where('categoryname', $request['categoryname'])->first();
            if(!$catname){
                $category->update($request->all());
                return response()->json('new');
            }
        }
        else{
            return response()->json('same');
            $category->update($request->all());
        }
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if($category->isactive == 1)
        {
            $category->where('id', $id)->update(['isactive' => false]);
        }
        else{
            $category->where('id', $id)->update(['isactive' => true]);   
        }
        return response()->json([
         'message' => 'Category deleted successfully'
        ]);
    }

    public function getbusinesscategory()
    {
        return Category::orderby('id', 'asc')->where('isactive', true)->get();
    }
}
