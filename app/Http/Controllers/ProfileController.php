<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Rules\MatchOldPassword;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //$user = auth()->user();
        $user = User::where(['id' => $id])->firstOrFail();
        return view('profile.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = auth()->user();
        //dd($user);
        //$user = User::where(['id' => $id])->firstOrFail();
        return view('profile.show')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  User $user)
    {
        $this->validate($request, [
            'user_name' => 'required',
            'user_email' => 'required',
            'user_image' => 'image|nullable|max:1999'
        ]);
        
        // Handle File Upload
        if($request->hasFile('user_image')){
             // Delete old image file if exists
            if($user->image !== null && $user->image != ""){
                Storage::delete('public/users/'.$user->image);
            }
            // Get filename with the extension
            $filenameWithExt = $request->file('user_image')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('user_image')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('user_image')->storeAs('public/users', $fileNameToStore);
        } else {
            $fileNameToStore = '';
        }

        $user->name = $request->user_name;
        $user->email = $request->user_email;

         if($request->hasFile('user_image')){
            $user->image = $fileNameToStore;
        }
        
        $user->save();
        return redirect()->back()->with('success',"Updated successfully.");

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
            'current_password' => ['required',new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        //$user->roles()->sync($request->roles);
        $user->password = Hash::make($request->new_password);
        $user->save();
        return redirect()->back()->with('success',"Password change successfully.");
    }    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
