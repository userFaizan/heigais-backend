<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\RegisterRequest;
use App\Http\Requests\Api\AuthRequest;
use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\OtpRequest;
use App\Http\Requests\Api\ResetPasswordRequest;
use App\Http\Requests\Api\SendMailRequest;
use App\Mail\OtpMail;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SocialAuthRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\Walet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login(AuthRequest $request){
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if($user->status == 1){
                    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                    $response = ['token' => $token,'user'=>$user];
                    $user->update([
                        'ip'=>$request->ip,
                        'device'=>$request->device,
                        'location'=>$request->location,
                    ]);
                    UserDevice::create(['user_id'=>$user->id,'onesignel_user_id'=>$request->onesignel_user_id]) ;
                    return response()->json($response,200);
                }else{
                    $response = ["message" => "you are block, please contect to admin!"];
                    return response($response, 422);
                }
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }

    public function register(RegisterRequest $request){
        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $onesignel_user_id = $request->onesignel_user_id;
        unset($request['onesignel_user_id']);
        unset($request['password_confirmation']);
        $user = User::create($request->toArray());
        $user = User::where('email', $request->email)->first();
        $user->attachRole('user');
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $response = ['token' => $token,'user'=>$user];
        Walet::create(['user_id'=>$user->id,'credit'=>0]);
        UserDevice::create(['user_id'=>$user->id,'onesignel_user_id'=>$onesignel_user_id]);
        return response()->json($response, 200);
    }

    public function update_user(Request $request){
        if($request->file('photo')){
            $safeName = uniqid().'-'.uniqid().'.png';
            Storage::disk('public')->put('uploads/'.$safeName, $request->file('photo'));
            $request['profile_photo'] = 'storage/uploads/'.$safeName;
        }
        auth()->user()->update($request->except('photo'));
        $response = ['user'=>auth()->user()];
        return response()->json($response, 200);
    }

    public function get_user(){
        return User::with('language','unit')->where('id',auth()->id())->first();
    }

    public function social_auth(SocialAuthRequest $request){
        $user = User::whereEmail($request->email)->first();
        if($user){
            if($user->social_type == $request->social_type && $user->is_social == 1){
                if($user->status == 1){
                    $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                    $user->update([
                        'social_token'=> $request->social_token,
                        'ip'=>$request->ip,
                        'device'=>$request->device,
                        'location'=>$request->location
                    ]);
                    UserDevice::create(['user_id'=>$user->id,'onesignel_user_id'=>$request->onesignel_user_id]) ;
                    $response = ['token' => $token,'user'=>$user];
                    return response()->json($response,200);
                }else{
                    $response = ["message" => "you are block, please contect to admin!"];
                    return response($response, 422);
                }
            }else{
                return response()->json(['message'=>'User already exist, You can not login with '.$request->social_type.'!'],400);
            }
        }else{
            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'is_social'=>1,
                'social_type'=>$request->social_type,
                'social_token'=>$request->social_token,
                'ip'=>$request->ip,
                'device'=>$request->device,
                'location'=>$request->location
            ]);
            $user->attachRole('user');
            UserDevice::create(['user_id'=>$user->id,'onesignel_user_id'=>$request->onesignel_user_id]) ;
            Walet::create(['user_id'=>$user->id,'credit'=>0]);
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            $response = ['token' => $token,'user'=>$user];
            return response()->json($response,200);
        }
    }

    public function update_password(ChangePasswordRequest $request)
    {
        if (Hash::check($request->old_password, auth()->user()->getAuthPassword())) {
            auth()->user()->update(['password'=>Hash::make($request->password)]);
            return response()->json(['message'=>'Password Updated!'],200);
        }else{
            return response()->json(['message'=>'Wrong Password! Try again'],400);
        }
    }

    public function send_mail(SendMailRequest $request)
    {
        $user = User::where('email','like',$request->email)->first();
        if($user!=null)
        {
            $code  = rand(1000,9999);
            $code = 1234 ;
            $user->update(['otp'=>$code]);
            // Mail::to($request->email)->send(new OtpMail($code));
            return [
                'message'=>'code generated',
            ];
        }
        else
        {
            return response()->json(['message'=>'user not exist'],400);
        }
    }

    public function match_otp(OtpRequest $request)
    {
        $user = User::where('email',$request->email)->first();
        if($user != null){
            if($user->otp == $request->otp)
            {
                $user->update(['otp'=>null]);
                $token = $user->createToken('Laravel Password Grant User')->accessToken;
                $response = [
                    'token' => $token
                ];
                return response($response, 200);
            }else{
                return response()->json(['message'=>'otp mismatch'],400);
            }
        }else{
            return response()->json(['message'=>'user does not exist!'],400);
        }
    }

    public function reset_password(ResetPasswordRequest $request){
        auth()->user()->update(['password'=>Hash::make($request->password)]);
        auth()->user()->token()->revoke();
        return response()->json(['message'=>'Password Updated!'],200);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $device = UserDevice::where('user_id',auth()->id())->where('onesignel_user_id',$request->onesignel_user_id)->first();
            if($device){
                $device->delete();
            }
            auth()->user()->token()->revoke();
        }
        return response()->json(["message"=>"logout successfully!"],200);
    }
    public function delete($id)
    {
  
    $data = User::where('id', $id)->delete();
        if ($data) {
            return response()->json([
                'message' => 'User Account Deleted successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'No User found against this id',
            ]);
        }
    }

}
