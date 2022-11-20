<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationRequest;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
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

    public function calculate(Request $request){
        $top = array();
        $distance = '(3959 * acos(cos(radians('.$request->lat.')) * cos(radians(lat)) * cos(radians(lng) - radians('.$request->lng.')) + sin(radians('.$request->lat.')) * sin(radians(lat))))';
        $interests = NotificationRequest::with('categories')->whereRaw($distance .'<='. $request->radius)->get();
        foreach($interests as $interest){
            foreach($interest->categories as $category){
                if(sizeof($top) > 0){
                    if(array_key_exists($category->title,$top)){
                        $top[$category->title]['count'] = $top[$category->title]['count'] + 1;
                    }else{
                        $new = array(
                            $category->title => array (
                                'id'=>$category->id,
                                'count' => 1,
                                'color' => $category->color,
                                'name' => $category->title
                            )
                        );
                        $top = array_merge($top,$new);
                    }
                }else{
                    $new = array(
                        $category->title => array (
                            'id'=>$category->id,
                            'count' => 1,
                            'color' => $category->color,
                            'name' => $category->title
                        )
                    );
                    $top = array_merge($top,$new);
                }
            }
        }
        $interests = array();
        foreach($top as $to)
        {
            $new = array(
                'id' => $to['id'],
                'count' => $to['count'],
                'color' => $to['color'],
                'name' => $to['name']
            );
            array_push($interests,$new);
        }

        return response()->json([
            'interests' => $interests
         ]);
    }

    public function get_interested_users(Request $request){
        $distance = '(3959 * acos(cos(radians('.$request->lat.')) * cos(radians(lat)) * cos(radians(lng) - radians('.$request->lng.')) + sin(radians('.$request->lat.')) * sin(radians(lat))))';
        $interests = NotificationRequest::with('categories')->whereRaw($distance .'<='. $request->radius);
        $users = $interests->whereHas('categories',function ($r) use ($request) {
            return $r->where('category_id',$request->id);
        })->get()->map(function($query){
            return [
                'email'=>$query->user->email
            ];
        });
        return response()->json([
            'users' => $users
         ]);
    }

}
