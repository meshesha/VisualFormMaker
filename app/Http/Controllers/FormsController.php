<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\models\Forms;
use App\models\VisibilityType;
use App\models\UsersGroup;
use App\models\OrgTree;
use App\models\FormStatusList;
use App\models\FormTypesList;
use App\models\Role;
use App\User;

class FormsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth', ['except' => ['index', 'show']]);
        $this->middleware('auth');
    }
    
    /**
     * Generate form Identifier id.
     *
     * @return string Identifier id
     */
    private function generateIdentifier($length)
    {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$forms_configs = config('global.forms');
        $paginate = (int)config('global.forms.paginate');
        $user_id = auth()->user()->id;
        $forms = Forms::where('user_id', $user_id)
                        ->orWhere('admin_users', 'like', "%{$user_id}%")
                        ->orderBy('created_at','desc')
                        ->paginate($paginate);

        //get visibility from DB
        $visibility = VisibilityType::all();

        //get groups from DB
        $groups = UsersGroup::where('group_status', 1)->get();

        //get org departments from DB
        $deps = OrgTree::all();

        //form statuses:
        $form_statuses = FormStatusList::all();

        //form types:
        $form_types = FormTypesList::all();
        
        
        $data = array(
            'title' => 'Forms',
            'visibility_typs' => $visibility,
            'groups' => $groups,
            'deps' => $deps,
            'form_statuses' => $form_statuses,
            'form_types' => $form_types,
            'forms' => $forms
        );
        //print_r($forms);
        return view('forms.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "Create new form";
        //get visibility from DB 
        $visibility = VisibilityType::all();

        //get groups from DB
        $groups = UsersGroup::where('group_status', 1)->get();

        //get org departments from DB 
        $deps = OrgTree::all();

        //form types: - TODO
        $form_types = FormTypesList::all();

        $data  = array(
            'title' => $title,
            'visibility' => $visibility,
            'groups' => $groups,
            'deps' => $deps
        );
        return view('forms.create')->with($data);
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
            'form_name' => 'required',
            'visibility' => 'required',
            'formbuilder_json' => 'required'
        ]);

        $form = new Forms;
        $form->user_id = auth()->user()->id;
        $form->name = $request->input('form_name');

        //$form->type = $request->input('form_type'); //TODO

        $form->visibility_type = $request->input('visibility');
        if($form->visibility_type == "3"){
            //groups
            //$form->visibility_to = $request->input('group_selection');
            $form->visibility_to = implode(",", $request->input('group_selection'));
        }else if($form->visibility_type == "4"){
            //deps
            //$form->visibility_to = $request->input('deps_selection');
            $form->visibility_to = implode(",", $request->input('deps_selection'));
        }else{
            $form->visibility_to = "";
        }
        $allows_edit = (int)config('global.forms.default_allows_edit');
        $form->admin_users = auth()->user()->id;
        $form->allows_edit = $allows_edit;
        $form->identifier = $this->generateIdentifier(16);
        $form->form_builder_json = $request->input('formbuilder_json');
        $form->save();

        return redirect('/forms')->with('success', 'Form Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
        
        $is_enables_form_details_view = config('global.my_submissions.enable_form_details_view');

        $form = Forms::where(['user_id' => $user->id, 'id' => $id])
                    ->orWhere([['admin_users', 'like', "%{$user->id}%"],['id',$id]])
                    ->with('user')
                    ->firstOrFail();

        if(($form) && ($user->hasAnyRoles(['Administrator','Manager']) || $is_enables_form_details_view == "1")){
            //continue
        }else{
            return redirect()->back()->with('error', 'form details blocked!'); 
        }
        $submitted_entries = $form->submitted();
        $form->submissions_count = $submitted_entries->count();
        //get visibility from DB
        $visibility = VisibilityType::all();

        //get groups from DB
        $groups = UsersGroup::where('group_status', 1)->get();

        //get org departments from DB
        $deps = OrgTree::all();
        
        //form statuses:
        $form_statuses = FormStatusList::all();

        //form types: 
        $form_types = FormTypesList::all();

        //form admin
        $form_admin_ids = $form->admin_users;
        $form_admins = explode(',', $form_admin_ids);


        $users = User::all();
        //$roles = Role::all();
        //dd($roles);
        $data = array(
            'title' => 'Details For "'.$form->name.'"',
            'visibility_typs' => $visibility,
            'groups' => $groups,
            'deps' => $deps,
            'form' => $form,
            'form_statuses' => $form_statuses,
            'form_types' => $form_types,
            'users' => $users,
            'form_admins' => $form_admins
        );
        //print_r($forms);
        return view('forms.show')->with($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function preview($id)
    {
        $user = auth()->user();
        $form = Forms::where(['user_id' => $user->id, 'id' => $id])
                    ->orWhere([['admin_users', 'like', "%{$user->id}%"],['id',$id]])
                    ->with('user')
                    ->firstOrFail();
        
        $data = array(
            'title' => 'Preview For "'.$form->name.'"',
            'form' => $form
        );
        //print_r($forms);
        return view('forms.preview')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();

        $form = Forms::where(['user_id' => $user->id, 'id' => $id])
                    ->orWhere([['admin_users', 'like', "%{$user->id}%"],['id',$id]])
                    ->firstOrFail();
        //get visibility from DB 

        if($form->status == 1){
            $visibility = VisibilityType::all();

            //get groups from DB
            $groups = UsersGroup::where('group_status', 1)->get();

            //get org departments from DB 
            $deps = OrgTree::all();

            //form types: - TODO
            $form_types = FormTypesList::all();

            //$saveURL = route('formbuilder::forms.update', $form);

            // get the roles to use to populate the make the 'Access' section of the form builder work
            //$form_roles = Helper::getConfiguredRoles();

            //return view('formbuilder::forms.edit', compact('form', 'pageTitle', 'saveURL', 'form_roles'));
            $data = array(
                'title' => 'Edit Form',
                'visibility_typs' => $visibility,
                'groups' => $groups,
                'deps' => $deps,
                    'form_types' => $form_types,
                    'form' => $form
                );

            return view('forms.edit')->with($data);
        }else{
            return  redirect()->back()->with('error', 'unpublish the form first');
        }
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
            'form_name' => 'required',
            'visibility' => 'required',
            'formbuilder_json' => 'required'
        ]);

        $user = auth()->user();

        $form = Forms::where(['user_id' => $user->id, 'id' => $id])
                    ->orWhere([['admin_users', 'like', "%{$user->id}%"],['id',$id]])
                    ->firstOrFail();

        //$form->user_id = auth()->user()->id;

        $form->name = $request->input('form_name');

        //$form->type = $request->input('form_type'); //TODO

        $form->visibility_type = $request->input('visibility');
        if($form->visibility_type == "3"){
            //groups
            //$form->visibility_to = $request->input('group_selection');
            $form->visibility_to = implode(",", $request->input('group_selection'));
        }else if($form->visibility_type == "4"){
            //deps
            //$form->visibility_to = $request->input('deps_selection');
            $form->visibility_to = implode(",", $request->input('deps_selection'));
        }else{
            $form->visibility_to = "";
        }
        //$form->admin_users = auth()->user()->id;
        $form->form_builder_json = $request->input('formbuilder_json');
        $form->save();

        return redirect('/forms/'. $id)->with('success', 'Form Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $form = Forms::where(['user_id' => $user->id, 'id' => $id])
                    ->orWhere([['admin_users', 'like', "%{$user->id}%"],['id',$id]])
                    ->firstOrFail();
        $form->delete();
        return redirect('/forms')->with('success', "'{$form->name}' deleted.");
    } 
    /**
     * Change form status resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request, $id)
    {
        $user = auth()->user();
        $form = Forms::where(['user_id' => $user->id, 'id' => $id])
                    ->orWhere([['admin_users', 'like', "%{$user->id}%"],['id',$id]])
                    ->firstOrFail();
        $form->status = $request->input('form_status');
        try{
            $form->save();
            return redirect()->back()->with('success', 'Form status Updated');
        }catch(Exception $e){
            return  redirect()->back()->with('error', 'error updating form status');
        }
    }
    /**
     * Change form status resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function formManagers(Request $request, $id)
    {
        $user = auth()->user();
        $form = Forms::where(['user_id' => $user->id, 'id' => $id])
                    ->orWhere([['admin_users', 'like', "%{$user->id}%"],['id',$id]])
                    ->firstOrFail();
        $form->admin_users = implode(",", $request->input('form_managers'));
        try{
            $form->save();
            return redirect()->back()->with('success', 'Form mangers Updated');
        }catch(Exception $e){
            return  redirect()->back()->with('error', 'error updating form mangers');
        }
    }
    /**
     * Change form status resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function formAllowsEdit(Request $request, $id)
    {
        $user = auth()->user();
        $form = Forms::where(['user_id' => $user->id, 'id' => $id])
                    ->orWhere([['admin_users', 'like', "%{$user->id}%"],['id',$id]])
                    ->firstOrFail();
        $form->allows_edit = $request->input('form_allows_edit');
        try{
            $form->save();
            return redirect()->back()->with('success', 'Form allows edit option Updated');
        }catch(Exception $e){
            return  redirect()->back()->with('error', 'error updating form allows edit option');
        }
    }
}
