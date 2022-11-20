<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function update_setting(Request $request){
        if(isset($request->language_id)){
            auth()->user()->update(['language_id'=>$request->language_id]);
        }
        if(isset($request->unit)){
            auth()->user()->update(['unit'=>$request->unit]);
        }
        return response()->json(['message'=>'updated added successfully','code'=>200],200);
    }
}
