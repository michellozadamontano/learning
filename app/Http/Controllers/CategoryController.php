<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function index(){
        return Category::latest()->get();
    }
    public function store(Request $request)
    {

        $this->validate($request,[
            'name' => 'required|',
            'description' => 'required',            
        ]);

        return Category::Create(            
            [
                'name' => $request['name'],
                'description' => $request['description'],                        
            ]
        );

    }
    public function update(Request $request, $id)
    {

        $category = Category::findOrFail($id);

        $this->validate($request,[
            'name' => 'required',
            'description' => 'required',            
        ]);

        $category->update($request->all());
        return ['message' => 'Categoria Actualizada'];
    }
    public function destroy($id)
    {   

        $category = Category::findOrFail($id);

        $category->delete();

        return ['message' => 'Categoria Eliminada'];
    }
}
