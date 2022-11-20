<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\FeedBackMail;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
    public function feedback(Request $request){
        $feedback = Feedback::create($request->all());
        Mail::to('heigaisapp@gmail.com')->send(new FeedBackMail([
            'name' => $request->name,
            'email'=>$request->email,
            'feedback'=>$request->feedback,
        ]));
        return response()->json(['message'=>'feedback successfully','code'=>200],200);
    }
}
