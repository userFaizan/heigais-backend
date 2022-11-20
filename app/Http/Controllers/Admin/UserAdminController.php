<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateUserRequest;
use App\Models\User;
use App\Models\Walet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserAdminController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.user_admin.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user_admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        // dd($request->all());
        $request['password']=Hash::make($request['password']);
        $admin = User::create(['name'=>$request->name, 'location'=>$request->location, 'email'=>$request->email, 'password'=>$request->password]);
        if($admin){
            $admin->attachRole('subadmin');
            if(isset($request->dashboard)){
                $admin->attachPermission('manage-dashboard');
            }
            if(isset($request->user)){
                $admin->attachPermission('manage-events');
            }
            if(isset($request->event)){
                $admin->attachPermission('manage-users');
            }
            if(isset($request->category)){
                $admin->attachPermission('manage-categories');
            }
        }
        return redirect('admin/admins');
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
        $edit_admin = User::with('walets')->where('id',$id)->first();
        return view('admin.user_admin.create', compact('edit_admin'));
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
        // dd($request->all());

        $admin = User::where('id',$id)->first();
        $admin->update(['name'=>$request->name, 'location'=>$request->location]);
        if(isset($request->dashboard)){
            $admin->syncPermissionsWithoutDetaching(['manage-dashboard']);
        }else{
            $admin->detachPermission('manage-dashboard');
        }
        if(isset($request->event)){
            $admin->syncPermissionsWithoutDetaching(['manage-events']);
        }else{
            $admin->detachPermission('manage-events');
        }
        if(isset($request->user)){
            $admin->syncPermissionsWithoutDetaching(['manage-users']);
        }else{
            $admin->detachPermission('manage-users');
        }
        if(isset($request->category)){
            $admin->syncPermissionsWithoutDetaching(['manage-categories']);
        }else{
            $admin->detachPermission('manage-categories');
        }
        return redirect('admin/admins');
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

    public function get_all_admins()
    {
        $admins = User::select('id','name','email','location','status','created_at')
            ->whereHas('roles', function($q){
                $q->where('name', 'subadmin');
            })->orderBy('created_at','DESC')->get();
            return DataTables::of($admins)
                ->addIndexColumn()
                // ->addColumn('user', function(User $admin){
                //     foreach($admin->walets as $walet){
                //         return $walet->credit;
                //     }
                // })
                // ->addColumn('event_count', function(User $admin){
                //         return $admin->events->count();
                // })
                ->addColumn('action', function(User $admin){
                    return view('admin.user_admin.action',compact('admin'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function delete_admin(Request $request){
        $admin = User::where('id',$request->id)->first();
        $admin->delete();
        return response()->json([
            'message'=>'Admin delete successfully!',
            'code'=>200
         ]);
    }

    public function block_admin(Request $request){
        $admin = User::where('id',$request->id)->first();
        $admin->update(['status'=>0]);
        return response()->json([
            'message'=>'Admin block successfully!',
            'code'=>200
         ]);
    }

    public function activate_admin(Request $request){
        $admin = User::where('id',$request->id)->first();
        $admin->update(['status'=>1]);
        return response()->json([
            'message'=>'Admin activated successfully!',
            'code'=>200
         ]);
    }
}
