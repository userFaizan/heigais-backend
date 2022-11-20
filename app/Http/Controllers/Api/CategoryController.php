<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::with('children')->where('parent_id',null)->get();
        return response()->json($categories,200);
    }
    public function parent_categories(){
        $categories = Category::where('parent_id',null)->get();
        return response()->json($categories,200);
    }

    public function getById($id){
        $categories = Category::where('parent_id',$id)->get();
        return response()->json($categories,200);
    }
}
