<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
     //index
     public function index(){
        $categories = Category::paginate(10);
        return view('pages.categories.index', compact('categories'));
    }
    //create
    public function create(){
        $categories = DB::table('categories')->get();
        return view('pages.categories.create', compact('categories'));
    }
    //store
    public function store(Request $request){
        //description tidak wajib
        $request -> validate([
            'name'=> 'required',
            'image'=>'required|image|mimes:png,jpg,jpeg,svg,gif|max:2048',
        ]);

         //store the request
         $category = new category();
         $category->name = $request -> name;
         $category->description = $request -> description;

         $category-> save();

         //save image
         if($request->hasFile('image')){
            $image = $request->file('image');
            $image->storeAs('public/categories', $category->id . '.' . $image->getClientOriginalExtension());
            $category->image = 'storage/categories/' . $category->id . '.' . $image->getClientOriginalExtension();
            $category-> save();
         }

         return redirect()->route('categories.index')->with('success','Category created successfully');

    }

    public function show($id){

        return view('pages.categories.show');

    }
    //edit
    public function edit($id){
        $category = Category::find($id);
        //compact used to view the query result
        return view('pages.categories.edit', compact('category'));

    }
    //update
    public function update(Request $request,$id){

        $request -> validate([
            'name'=> 'required',
            //'image'=>'required|image|mimes:png,jpg,jpeg,svg,gif|max:2048',
        ]);

        //update the request
        $category = Category::find($id);
        $category->name = $request -> name;
        $category->description = $request -> description;
        $category-> save();

        //save image
        // ada file ga, kalau ga ada nanti bakal dilewatin
        if($request->hasFile('image')){
            $image = $request->file('image');
            $image->storeAs('public/categories', $category->id . '.' . $image->getClientOriginalExtension());
            $category->image = 'storage/categories/' . $category->id . '.' . $image->getClientOriginalExtension();
            $category-> save();
         }

        return redirect()->route('categories.index')->with('success','Category updated successfully');

    }
    //destroy
    public function destroy($id){
        //delete the request
        $category = Category::find($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success','Category deleted successfully');

    }
}
