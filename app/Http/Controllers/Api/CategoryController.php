<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    //index api
    public function index(){
        //get all category
        $categories = Category::all();
        return response()->json([
            'status' => 'success',
            'data' => $categories
        ],200);
    }
}
