<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Walet;
use Illuminate\Http\Request;

class WaletController extends Controller
{
    public function my_walet(){
        $walet = Walet::where('user_id',auth()->id())->first();
        return response()->json($walet);
    }
    public function my_transections(){
        $walet = Walet::with('transections')->where('user_id',auth()->id())->get();
        return response()->json($walet);
    }
}
