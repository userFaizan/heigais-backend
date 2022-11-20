<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppText;
use App\Models\Category;
use \Cviebrock\EloquentSluggable\Services\SlugService as SlugService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = category::where('parent_id',null)->get();
        foreach($categories as $category){
            $app_text = AppText::where('text_key',$category->title)->where('language_id',1)->first();
            $category['title']=$app_text->text;
        }
        return view('admin.category.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $slug = str::slug($request->english,'_');
        $flag = Category::where('title',$slug)->first();
        if($flag){
            $slug = $slug.uniqid();
        }
        if($request->parent_id == 0){
            Category::create(['title'=>$slug,'color'=>$request->color]);
        }else{
            Category::create(['title'=>$slug,'color'=>$request->color,'parent_id'=>$request->parent_id]);
        }
        AppText::create(['text_key'=>$slug, 'text'=>$request->english, 'language_id' => 1, 'status' => 1]);
        AppText::create(['text_key'=>$slug, 'text'=>$request->finish, 'language_id' => 2, 'status' => 1]);
        AppText::create(['text_key'=>$slug, 'text'=>$request->swidish, 'language_id' => 3, 'status' => 1]);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit_category = Category::where('id',$id)->first();
        $english = AppText::where('text_key',$edit_category->title)->where('language_id',1)->first();
        $finish = AppText::where('text_key',$edit_category->title)->where('language_id',2)->first();
        $swidish = AppText::where('text_key',$edit_category->title)->where('language_id',3)->first();
        $categories = Category::where('parent_id',null)->get();
        foreach($categories as $category){
            $app_text = AppText::where('text_key',$category->title)->where('language_id',1)->first();
            $category['title']=$app_text->text;
        }
        return view('admin.category.create',compact('edit_category','categories','english','finish','swidish'));
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

        $category = Category::where('id',$id)->where('title',$request->category_title)->first();
        $english_text=AppText::where('text_key',$request->category_title)->where('language_id',1)->first();
        $finish_text=AppText::where('text_key',$request->category_title)->where('language_id',2)->first();
        $swidish_text=AppText::where('text_key',$request->category_title)->where('language_id',3)->first();
        $slug = str::slug($request->english,'_');

        $flag = Category::where('title',$slug)->where('id','!=',$id)->first();

        if($flag){
            $slug = $slug.uniqid();
        }

        if($request->parent_id == 0){
                $parent_id=null;
                $category->update(['title'=>$slug,'color'=>$request->color,'parent_id'=>$parent_id]);
        }else{
            $category->update(['title'=>$slug,'color'=>$request->color,'parent_id'=>$request->parent_id]);
        }

        $english_text->update(['text_key'=>$slug,'text'=>$request->english]);
        $finish_text->update(['text_key'=>$slug,'text'=>$request->finish]);
        $swidish_text->update(['text_key'=>$slug,'text'=>$request->swidish]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function get_all_categories()
    {
        $categories = Category::select('id','title','parent_id','color')->with('parent')->orderBy('id','ASC')->get();
            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('title', function(Category $category){
                    $category_name = $category?AppText::where('text_key',$category->title)->where('language_id',1)->first():false;
                    return $category_name?$category_name->text:'';
                })
                ->addColumn('color', function(Category $category){
                    return '<span class="badge badge-pill" style="background-color:'.$category->color.'">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span>';
                })
                ->addColumn('parent', function(Category $category){
                    $category_name = $category->parent?AppText::where('text_key',$category->parent->title)->where('language_id',1)->first():false;
                    return $category_name?$category_name->text:'';
                })
                ->addColumn('action', function(Category $category){
                    return view('admin.category.action',compact('category'))->render();
                })
                ->rawColumns(['action','color'])
                ->make(true);

    }
}
