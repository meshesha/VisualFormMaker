<?php

namespace App\Http\Controllers;

use Gate;
use Illuminate\Http\Request;
use App\models\UsersGroup;
use App\models\Role;

class GroupsController extends Controller
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
        
        if(Gate::denies('manager')){
            return redirect()->back()->with('error',"You don't have permission");
        } 
        $paginate = (int)config('global.groups.paginate');
        $groups = UsersGroup::paginate($paginate);
        $data = array(
            'title' => 'Groups/Roles',
            'groups' => $groups
        );
        //print_r($forms);
        return view('groups.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('manager')){
            return redirect()->back()->with('error',"You don't have permission");
        } 
        $data = array(
            'title' => 'Groups/Roles'
        );
        //print_r($forms);
        return view('groups.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'group_status' => 'required',
            'group_name' => 'required'
        ]);

        $group = new UsersGroup();
        $group->name = $request->input('group_name');
        $group->group_status = $request->input('group_status');
        $group->save();
        return redirect('/groups')->with('success', 'Group Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Gate::denies('manager')){
            return redirect()->back()->with('error',"You don't have permission");
        } 
        $group = UsersGroup::where(['id' => $id])->firstOrFail();
        $users = $group->users()->get();
        $stt = ["","Activev","Disabled"];
        $data = array(
            'title' => 'Edit group/Role',
            'group' => $group,
            'status' => $stt[$group->group_status],
            'users' => $users
        );
        //dd($data);
        return view('groups.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Gate::denies('manager')){
            return redirect()->back()->with('error',"You don't have permission");
        } 
        $group = UsersGroup::where(['id' => $id])->firstOrFail();
        //get groups from DB
        $data = array(
            'title' => 'Edit group/Role',
            'group' => $group
        );
        //dd($data);
        return view('groups.edit')->with($data);
        
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
        $this->validate($request, [
            'group_status' => 'required',
            'group_name' => 'required'
        ]);

        $group = UsersGroup::where(['id' => $id])->firstOrFail();
        $group->name = $request->input('group_name');
        $group->group_status = $request->input('group_status');
        $group->save();
        return redirect('/groups')->with('success', 'Group Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        if(Gate::denies('manager')){
            return redirect()->back()->with('error',"You don't have permission.");
        }

        $group = UsersGroup::where(['id' => $id])->firstOrFail();
        $group->delete();
        return redirect('/groups')->with('success', 'Group deleted');
    }
}
