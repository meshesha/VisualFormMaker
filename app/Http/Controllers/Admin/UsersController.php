<?php

namespace App\Http\Controllers\Admin;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\User;
use App\models\Role;
use App\models\OrgTree;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginate = (int)config('global.users.paginate');
        $users = User::paginate($paginate);
        $title = "Users";
        return view('admin.users.index')->with([
            'title'=>$title,
            'users'=>$users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if(Gate::denies('manager')){
            return redirect()->route('admin.users.index')->with('error',"You don't have permission");
        }

        $default_user_status = config('global.users.default_user_status');
        $default_groups = config('global.users.default_groups');
        $roles = Role::all();
        $deps = OrgTree::all();
        
        return view('admin.users.create')->with([
            'roles' => $roles,
            'deps' => $deps,
            'default_user_status' => $default_user_status,
            'default_groups' => $default_groups
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_status' => ['required'],
            'name' => ['required'],
            'email' => ['required'],
            'new_password' => ['required'],
            'confirm_password' => ['same:new_password'],
        ]);

        $user = new User();
        //$user->roles()->sync($request->roles);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->new_password);
        $user->department = $request->user_dep;
        $user->status = $request->user_status;
        $user->save();
        if($user->save()){
            //$user_id = $user->id;
            $user->roles()->sync($request->roles);
            return redirect()->back()->with('success',"User saved successfully!");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //dd($user);
        //dd($user->roles);

        if(Gate::denies('manager')){
            return redirect()->route('admin.users.index')->with('error',"You don't have permission");
        }

        $roles = Role::where(['group_status'=>1])->get();
        $deps = OrgTree::all();
        //dd($roles);
        return view('admin.users.edit')->with([
            'user' => $user,
            'roles' => $roles,
            'deps' => $deps
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'user_status' => ['required'],
            'name' => ['required'],
            'email' => ['required'],
        ]);

        $user->roles()->sync($request->roles);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->department = $request->user_dep;
        $user->status = $request->user_status;
        $user->save();
        return redirect()->back()->with('success',"updated!");
    }

    /**
     * Change user password.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function changePassword(User $user)
    {

        if(Gate::denies('manager')){
            return redirect()->route('admin.users.index')->with('error',"You don't have permission");
        }
        return view('admin.users.changepass')->with('user',$user);
    }


    /**
     * Save changed user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function setPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
        
        //$user->roles()->sync($request->roles);
        $user->password = Hash::make($request->new_password);
        $user->save();
        return redirect()->route('admin.users.index')->with('success',"Password change successfully.");
    }    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(Gate::denies('manager')){
            return redirect()->route('admin.users.index')->with('error',"You don't have permission");
        }
        if($user->image !== null && $user->image != ""){
            // Delete Image
            if($user->image != "user.png"){
                Storage::delete('public/users/'.$user->image);
            }
        }
        $user->roles()->detach();
        $user->delete();
        //Delete image - TODO
        return redirect()->route('admin.users.index')->with('success',"deleted!");
    }
}
