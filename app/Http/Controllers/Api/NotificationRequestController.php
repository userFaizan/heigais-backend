<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateNotificationRequest;
use App\Http\Requests\Api\UpdateNotificationRequest;
use App\Models\NotificationRequest;
use Illuminate\Http\Request;

class NotificationRequestController extends Controller
{
    public function add_notification_request(CreateNotificationRequest $request){
        $request['user_id']=auth()->id();
        $notification_request = NotificationRequest::create($request->except('category_ids'));
        $notification_request->categories()->attach($request->category_ids);
        return response()->json(['message'=>'Notification request created successfully!','code'=>200]);
    }

    public function update_notification_request(Request $request,$id){
        $notification_request = NotificationRequest::where('id',$id)->where('user_id',auth()->id())->first();
        if($notification_request){
            if(isset($request->category_id)){
                $notification_request->update(['category_id'=>$request->category_id]);
                $notification_request->categories()->detach();
                if(isset($request->category_ids)){
                    $notification_request->categories()->attach($request->category_ids);
                }
            }
            if(isset($request->date_from)){
                $notification_request->update(['date_from'=>$request->date_from]);
            }
            if(isset($request->date_to)){
                $notification_request->update(['date_to'=>$request->date_to]);
            }
            if(isset($request->notification_status)){
                $notification_request->update(['notification_status'=>$request->notification_status]);
                if($request->notification_status == 1){
                    $notification_request->update(['date_to'=>null]);
                    $notification_request->update(['date_from'=>null]);
                }

            }
            if(isset($request->lat)){
                $notification_request->update(['lat'=>$request->lat]);
            }
            if(isset($request->lng)){
                $notification_request->update(['lng'=>$request->lng]);
            }
            if(isset($request->radius)){
                $notification_request->update(['radius'=>$request->radius]);
            }
            return response()->json(['message'=>'updated successfully','code'=>200],200);
        }else{
            return response()->json(['message'=>'not found','code'=>404],404);
        }
    }

    public function show_notification_requests(){
        $notification_request = NotificationRequest::with('category','categories')->where('user_id',auth()->id())->get();
        return response()->json($notification_request);
    }

    public function delete_notification_request($id){
        $notification_request = NotificationRequest::where('id',$id)->where('user_id',auth()->id())->first();
        if($notification_request){
            $notification_request->delete();
            $notification_request->categories()->detach();
            return response()->json(['message'=>'request deleted successfully!','code'=>200],200);
        }else{
            return response()->json(['message'=>'request not found','code'=>404],404);
        }
    }
}
