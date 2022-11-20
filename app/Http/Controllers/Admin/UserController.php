<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateUserRequest;
use App\Models\User;
use App\Models\Walet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $request['password']=Hash::make($request['password']);
        $user = User::create($request->except('_token','password_confirmation'));
        if($user){
            Walet::create(['user_id'=>$user->id,'credit'=>0]);
            $user->attachRole('user');
        }
        return redirect('admin/users');
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
        $edit_user = User::with('walets')->where('id',$id)->first();
        return view('admin.user.create', compact('edit_user'));
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

        $user = User::where('id',$id)->first();
        $user->update($request->except('_token','password_confirmation','credit'));
        if(isset($request->credit)){
            Walet::where('user_id',$user->id)->update(['credit'=>$request->credit]);
        }
        return redirect('admin/users');
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

    public function get_all_users()
    {
        $users = User::select('id','name','email','location','status','created_at')->with('walets')->whereHas(
            'roles', function($q){
              $q->where('name', 'user');
          })->orderBy('created_at','DESC')->get();
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('user', function(User $user){
                    foreach($user->walets as $walet){
                        return $walet->credit;
                    }
                })
                ->addColumn('event_count', function(User $user){
                        return $user->events->count();
                })
                ->addColumn('action', function(User $user){
                    return view('admin.user.action',compact('user'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function delete_user(Request $request){
        $user = User::where('id',$request->id)->first();
        $user->delete();
        return response()->json([
            'message'=>'User delete successfully!',
            'code'=>200
         ]);
    }

    public function block_user(Request $request){
        $user = User::where('id',$request->id)->first();
        $user->update(['status'=>0]);
        return response()->json([
            'message'=>'User block successfully!',
            'code'=>200
         ]);
    }

    public function activate_user(Request $request){
        $user = User::where('id',$request->id)->first();
        $user->update(['status'=>1]);
        return response()->json([
            'message'=>'User activated successfully!',
            'code'=>200
         ]);
    }
}
