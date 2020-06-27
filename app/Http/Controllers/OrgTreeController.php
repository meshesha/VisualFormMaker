<?php

namespace App\Http\Controllers;

use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\models\OrgTree;
use App\User;

class OrgTreeController extends Controller
{
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
        $paginate = (int)config('global.departments_table.paginate');
        $deps = OrgTree::paginate($paginate);
        $data = array(
            'title' => 'Departments table',
            'deps' => $deps
        );
        
        return view('org.index')->with($data);
    }

    private function parseTree($tree, $root = null, $deps) {
        $return = array();
        # Traverse the tree and search for direct children of the root
        foreach($tree as $child => $parent) {
            # A direct child is found
            if($parent == $root) {
                # Remove item from tree (we don't need to traverse this again)
                unset($tree[$child]);
                # Append the child into result array and parse its children
                $children = $this->parseTree($tree, $child, $deps);
                $user_obj = new \stdClass();
                //$user = User::select('name','email','image','department')->where('id', $child)->firstOrFail();
                //$user_obj->id = $child;
                $user_text_obj = new \stdClass();
                //$user_text_obj->name = $user->name;
                if(is_string($child)){
                    $child_ary = explode(',', $child);
                    //$child_ary[0] - unknown user
                    //$child_ary[1] - dep id
                    $user_text_obj->name = $child_ary[0];
                    $user_text_obj->title = $deps->whereIn("id", $child_ary[1])->first()->name;
                    $user_obj->image = asset('storage/users')."/user.png";
                }else{
                    $user = User::select('name','email','image','department')->where('id', $child)->firstOrFail();
                    //'<a class="dropdown-item" href="{{ route(\'profile.show\',$user) }}">'.$user->name.'<\a>';
                    //$user_text_obj->name = $user->name;
                    $user_name = $user->name;
                    //$user_text_obj->title = $deps->whereIn("id", $user->department)->first()->name;
                    $user_title = $deps->whereIn("id", $user->department)->first()->name;
                    //$user_text_obj->email = $user->email;
                    $user_email = $user->email;
                    $user_img_src = "";
                    if($user->image != ""){
                        //$user_obj->image = asset('storage/users')."/". $user->image;
                        $user_img_src = asset('storage/users')."/". $user->image;
                    }else{
                        //$user_obj->image = asset('storage/users')."/user.png";
                        $user_img_src = asset('storage/users')."/user.png";
                    }
                    $profile_link = url("profile/{$child}");
                    //$user_link_obj = new \stdClass();
                    //$user_link_obj->href = url("profile/{$child}");
                    //$user_obj->link = $user_link_obj;

                    $is_show_profile_link = config('global.departments_tree.profile_link');
                    $is_show_title = config('global.departments_tree.title');
                    $is_show_email = config('global.departments_tree.email');
                    $is_show_image = config('global.departments_tree.image');
                    $is_show_name = config('global.departments_tree.name');

                    $user_innerHTML = "<div class='user-node'>";
                    if($is_show_image == "1"){
                        $user_innerHTML .= "<img src='{$user_img_src}' />";
                    }
                    if($is_show_name == "1"){
                        $user_innerHTML .= "<p class='node-name'>{$user_name}</p>";
                    }
                    if($is_show_title == "1"){
                        $user_innerHTML .= "<p class='node-title'>{$user_title}</p>";
                    }
                    if($is_show_email == "1"){
                        $user_innerHTML .= "<p class='node-email'>{$user_email}</p>";
                    }
                    if($is_show_profile_link == "1"){
                        $user_innerHTML .= "<a href='$profile_link' class='btn btn-primary btn-xs'>profile</a>";
                    }
                    $user_innerHTML .= "</div>";

                    $user_obj->innerHTML = $user_innerHTML;
                }

                $user_obj->text = $user_text_obj;
                if($children != null) {
                    $user_obj->children = $children;
                }
                $return[] = $user_obj;
            }
        }
        return empty($return) ? null : $return;    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tree()
    {
        
        $tree_direction = config('global.departments_tree.direction');
        //rootOrientation: NORTH (default) , EAST , WEST , SOUTH
        //more settings : https://fperucic.github.io/treant-js/
        
        $chart_config = new \stdClass();

        $config = new \stdClass();
        $config->container = "#my_org_tree";
        $config->rootOrientation = $tree_direction;
        $connectors = new \stdClass();
        $connectors->type = "step";
        $config->connectors = $connectors;
        $node = new \stdClass();
        $node->HTMLclass = "nodeMyOrgTree";
        
        $config->node = $node;

        $chart_config->chart = $config;
        
        $deps = OrgTree::all();

        $prnt_child_ary = array();
        foreach ($deps as $dep){
            
            if($dep->parent_id == 0){
                //$user = User::select('id','name')->where('department', $dep->id)->first();
                //if($user){
                //    $prnt_child_ary[$user->id] = null;
                //}else{
                //    $prnt_child_ary["unknown,{$dep->id}"] = null;
                //}
            }else{
                $users = User::select('id','name')->where('department', $dep->id)->get();
                if($users && $users->count() > 0){
                    foreach ($users as $user){
                        $puser = User::select('id','name')->where('department', $dep->parent_id)->first();
                        if($puser){
                            $prnt_child_ary[$user->id] = $puser->id;
                        }else{
                            //parent job not occupied
                            $prnt_child_ary[$user->id] = "unknown,{$dep->parent_id}";
                        }
                    }
                }else{
                    //user job not occupied-?
                    $puser = User::select('id','name')->where('department', $dep->parent_id)->first();
                    
                    if($puser){
                        //user job not occupied
                        $prnt_child_ary["unknown,{$dep->id}"] = $puser->id;
                    }else{
                        //parent job not occupied & user job not occupied
                        $prnt_child_ary["unknown,{$dep->id}"] ="unknown,{$dep->parent_id}";
                    }
                }
            }
        }
        //dd($prnt_child_ary);
        $root_dep = $deps->whereIn("parent_id", 0)->first();
        $root_users = User::select('id','name','email','image')->where('department', $root_dep->id)->first();
        $root_users_id = 0;
        $user_obj = new \stdClass();
        if($root_users){
            $root_users_id = $root_users->id;
            //$user_obj_text = new \stdClass();
            //$user_obj_text->name = $root_users->name;
            $user_name = $root_users->name;
            //$user_obj_text->title = $root_dep->name; //user job title - TODO:add
            $user_title = $root_dep->name;
            //$user_obj_text->email = $root_users->email;
            $user_email = $root_users->email;

            //$user_obj->text = $user_obj_text;
            $user_img_src = "";
            if($root_users->image != ""){
            // $user_obj->image =  asset('storage/users')."/".  $root_users->image;
                $user_img_src = asset('storage/users')."/".  $root_users->image;
            }else{
                //$user_obj->image =  asset('storage/users')."/user.png";
                $user_img_src = asset('storage/users')."/user.png";
            }
            //$user_link_obj = new \stdClass();
            //$user_link_obj->href = url("profile/{$root_users_id}");
            $profile_link = url("profile/{$root_users_id}");
            //$user_obj->link = $user_link_obj;
        }else{
            $user_name = "unknown";
            $user_title = $root_dep->name;
            $user_email = "";
            $user_img_src = asset('storage/users')."/user.png";
            $profile_link = "";
        }

        $is_show_profile_link = config('global.departments_tree.profile_link');
        $is_show_title = config('global.departments_tree.title');
        $is_show_email = config('global.departments_tree.email');
        $is_show_image = config('global.departments_tree.image');
        $is_show_name = config('global.departments_tree.name');

        $user_innerHTML = "<div class='user-node'>";
        if($is_show_image == "1"){
            $user_innerHTML .= "<img src='{$user_img_src}' />";
        }
        if($is_show_name == "1"){
            $user_innerHTML .= "<p class='node-name'>{$user_name}</p>";
        }
        if($is_show_title == "1"){
            $user_innerHTML .= "<p class='node-title'>{$user_title}</p>";
        }
        if($is_show_email == "1" && $user_email != ""){
            $user_innerHTML .= "<p class='node-email'>{$user_email}</p>";
        }
        if($is_show_profile_link == "1" && $profile_link != ""){
            $user_innerHTML .= "<a href='$profile_link' class='btn btn-primary btn-xs'>profile</a>";
        }
        $user_innerHTML .= "</div>";

         $user_obj->innerHTML = $user_innerHTML;


        $user_obj->children =  $this->parseTree($prnt_child_ary,$root_users_id, $deps);
        $chart_config->nodeStructure = $user_obj;
        //dd($chart_config);
        
        $data = array(
            'title' => 'Departments tree',
            'chart_config' =>json_encode($chart_config) 
        );
        return view('org.tree')->with($data);
        
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orgChartEditor()
    {
        if(Gate::denies('manager')){
            return redirect()->back()->with('error',"You don't have permission");
        }
        $deps = OrgTree::all();
        $tree_array = array();
        foreach ($deps as $dep){
            $dep_obj = new \stdClass();
            $dep_obj->id = $dep->id;
            $dep_obj->name = $dep->name;
            $dep_obj->parent = $dep->parent_id;
            $tree_array[] = $dep_obj;
        }
        //dd($tree_array);
        $data = array(
            'title' => 'Departments Editor',
            'tree_array' =>json_encode($tree_array) 
        );
        return view('org.editor')->with($data);
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
        $deps = OrgTree::all();
        $users = User::select('id','name','email','image')
            ->where('department', '')
            ->orWhere('department', null)
            ->get();
        $data = array(
            'title' => 'Department - create new',
            'deps' => $deps,
            'users' => $users
        );
        return view('org.create')->with($data);
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
            'dep_name' => 'required',
            'dep_parent' => 'required'
        ]);

        $dep = new OrgTree();
        $dep->name = $request->input('dep_name');
        $dep->parent_id = $request->input('dep_parent');
        if($dep->save()){
            $dep_id = $dep->id;
            //set id in user department 
            $dep_users_ary = $request->input('dep_users');
            if($dep_users_ary && count($dep_users_ary) > 0){
                foreach($dep_users_ary as $user_id){
                    $user = User::where('id', $user_id)->first();
                    $user->department = $dep_id;
                    $user->save();
                }
            }
            return redirect('/org')->with('success', 'Department Created');
        }else{
            return redirect('/org')->with('error', 'Error - Department NOT Created');
        }
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
        if(Gate::denies('manager')){
            return redirect()->back()->with('error',"You don't have permission");
        }
        $deps = OrgTree::all();
        $edit_dep = $deps->whereIn('id', $id)->first();
        $users = User::select('id','name','email','department','image')
            ->where('department', '')
            ->orWhere('department', null)
            ->orWhere('department', $edit_dep->id)
            ->get();
        $data = array(
            'title' => 'Department - update '.$edit_dep->name,
            'deps' => $deps,
            'edit_dep' => $edit_dep,
            'users' => $users
        );
        return view('org.edit')->with($data);
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
            'dep_name' => 'required',
            'dep_parent' => 'required'
        ]);

        $dep = OrgTree::where('id', $id)->first();
        $dep_id = $dep->id;
        $dep->name = $request->input('dep_name');
        $dep->parent_id = $request->input('dep_parent');
        if($dep->save()){
            //remove user with department = $dep_id
            $remov_dep_user = User::where('department', $dep_id)->get();
            if($remov_dep_user && count($remov_dep_user) > 0){
                foreach($remov_dep_user as $r_user_id){
                    $r_user_id->department = null;
                    $r_user_id->save();
                }
            }

            //set id in user department 
            $dep_users_ary = $request->input('dep_users');
            if($dep_users_ary && count($dep_users_ary) > 0){
                foreach($dep_users_ary as $user_id){
                    $user = User::where('id', $user_id)->first();
                    $user->department = $dep_id;
                    $user->save();
                }
            }
            return redirect('/org')->with('success', 'Department Created');
        }else{
            return redirect('/org')->with('error', 'Error - Department NOT Created');
        }
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
            return redirect()->back()->with('error',"You don't have permission");
        }
        
        $dep = OrgTree::where(['id' => $id])->first();
        //check if this dep has children - TODO
        if($dep->delete()){
            $remov_dep_user = User::where('department', $id)->get();
            if($remov_dep_user && count($remov_dep_user) > 0){
                foreach($remov_dep_user as $r_user_id){
                    $r_user_id->department = null;
                    $r_user_id->save();
                }
            }

            return redirect('/org')->with('success', 'Department deleted');
        }else{
            return redirect('/org')->with('error', 'Error deleting department!');
        }

    }
}
