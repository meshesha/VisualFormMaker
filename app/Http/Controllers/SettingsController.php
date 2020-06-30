<?php

namespace App\Http\Controllers;

use Gate;
use Illuminate\Http\Request;
use App\models\Setting;
use App\models\UsersGroup;

class SettingsController extends Controller
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
    public function appSettings()
    {
        if(Gate::denies('admin')){
            return redirect()->back()->with('error',"You don't have permission");
        }
        $app_configs = config('app');
        $app_name = config('app.name');
        $app_url = config('app.url');
        $app_debug = config('app.debug');
        $app_timezone = config('app.timezone');
        
        //dd($app_configs);
        /*
            "name" => "visualformmaker"
            "env" => "local"
            "debug" => true
            "url" => "http://localhost/visualformmaker"
            "asset_url" => null
            "timezone" => "UTC"
            "locale" => "en"
            "fallback_locale" => "en"
            "faker_locale" => "en_US"
            "key" => "base64:z2ONdURgniWaX1xLsfMIENTWlFESqHANSpYFxjADDHw="
            "cipher" => "AES-256-CBC"
        */
        //database
        $db_configs = config('database');
        $driver = $db_configs['default'];
        $db_settings = $db_configs['connections'][$driver];
        //dd($db_settings);
        /*
            "driver" => "mysql"
            "url" => null
            "host" => "localhost"
            "port" => "3306"
            "database" => "visualformmaker"
            "username" => "root"
            "password" => "koll34ll"
            "unix_socket" => ""
            "charset" => "utf8mb4"
            "collation" => "utf8mb4_unicode_ci"
            "prefix" => ""
            "prefix_indexes" => true
            "strict" => true
            "engine" => null
            "options" => []
        */
        //mail
        $mail_configs = config('mail');
        $mailer = $mail_configs['default'];
        $mail_from = $mail_configs['from'];
        $mail_settings = $mail_configs['mailers'][$mailer];
        //dd($mail_settings);
        /*
            "transport" => "smtp"
            "host" => "smtp.mailtrap.io"
            "port" => "2525"
            "encryption" => null
            "username" => null
            "password" => null
            "timeout" => null
            "auth_mode" => null
        */
        $data = array(
            'title' => 'General Settings',
            'app_settings' => $app_configs,
            'db_settings' => $db_settings,
            'mail_settings' => $mail_settings,
            'mail_from' =>$mail_from
        );
        //print_r($forms);
        return view('settings.app.index')->with($data);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboardSettings()
    {
        if(Gate::denies('admin')){
            return redirect()->back()->with('error',"You don't have permission");
        }
        $dashboard = config('global.dashboard');
        //dd($dashboard_configs);
        /*
            "forms" => "1"
            "users" => "1"
            "groups" => "1"
            "departments" => "1"
            "submitted" => "1"
        */
        $data = array(
            'title' => 'Dashboard Settings',
            'dashboard' => $dashboard
        );
        return view('settings.dashboard.index')->with($data);
    }

   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDashboardSettings(Request $request)
    {
        $err = array();
        $this->validate($request, [
            'is_forms_link' => 'required',
            'is_users_link' => 'required',
            'is_groups_link' => 'required',
            'is_departments_link' => 'required',
            'is_submitted_link' => 'required',
        ]);
        $set_names = [
            'forms'=>'is_forms_link',
            'users'=>'is_users_link',
            'groups'=>'is_groups_link',
            'departments'=>'is_departments_link',
            'submitted'=>'is_submitted_link',
        ];
        foreach ($set_names as $name => $value) {
            $dashboard = Setting::where(['group' => 'dashboard','name' =>$name])
                                ->update(['value' => $request->input($value)]);
            if(!$dashboard){
                $err[] = $dashboard;
            }
        }

        if(empty($err)){
            return redirect()->back()->with('success', 'Settings Updated!');
        }else{
            return redirect()->back()->with('error', 'Error Updating!: ' . implode(', ', $err));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function formsSettings()
    {
        if(Gate::denies('admin')){
            return redirect()->back()->with('error',"You don't have permission");
        }
        //$global_configs = config('global');
        $forms = config('global.forms');
        //dd($forms_configs);
        /*
            "paginate" => "10"
            "default_allows_edit" => "1"
        */
        $user_submissions = config('global.my_submissions');
        //dd($user_submissions);
        /*
            "paginate" => "10"
            "enable_form_details_view" => "1"
            "enable_delete" => "1"
        */
        $data = array(
            'title' => 'Forms',
            'forms' => $forms,
            'user_submissions' => $user_submissions
        );
        return view('settings.forms.index')->with($data);
    }

   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateFormsSettings(Request $request)
    {
        $err = array();
        $this->validate($request, [
            'forms_paginate' => 'required|numeric|min:1',
            'default_allows_edit' => 'required',
        ]);
        $set_names = [
            'paginate'=>'forms_paginate',
            'default_allows_edit'=>'default_allows_edit'
        ];
        foreach ($set_names as $name => $value) {
            $forms = Setting::where(['group' => 'forms','name' =>$name])
                                ->update(['value' => $request->input($value)]);
            if(!$forms){
                $err[] = $forms;
            }
        }

        if(empty($err)){
            return redirect()->back()->with('success', 'Settings Updated!');
        }else{
            return redirect()->back()->with('error', 'Error Updating!: ' . implode(', ', $err));
        }

    }

   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateMySubmissionsSettings(Request $request)
    {
        $err = array();
        $this->validate($request, [
            'submissions_paginate' => 'required|numeric|min:1',
            'enable_form_details_view' => 'required',
            'enable_delete' => 'required'
        ]);
        $set_names = [
            'paginate'=>'submissions_paginate',
            'enable_form_details_view'=>'enable_form_details_view',
            'enable_delete'=>'enable_delete'
        ];
        foreach ($set_names as $name => $value) {
            $my_submissions = Setting::where(['group' => 'my_submissions','name' =>$name])
                                ->update(['value' => $request->input($value)]);
            if(!$my_submissions){
                $err[] = $my_submissions;
            }
        }

        if(empty($err)){
            return redirect()->back()->with('success', 'Settings Updated!');
        }else{
            return redirect()->back()->with('error', 'Error Updating!: ' . implode(', ', $err));
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function usersSettings()
    {
        if(Gate::denies('admin')){
            return redirect()->back()->with('error',"You don't have permission");
        }
        //$global_configs = config('global');
        $users = config('global.users');
        //dd($users_configs);
        /*
            "paginate" => "10"
            "default_groups" => "3"
            "default_user_status" => "1"
            "allows_registration" => "1"
            "allow_reset_password" => "1"
            "allow_remember" => "1"
        */
        $groups = UsersGroup::where('group_status', 1)->get();

        $data = array(
            'title' => 'Users Settings',
            'users' => $users,
            'groups' =>$groups
        );
        return view('settings.users.index')->with($data);
    }

   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUsersSettings(Request $request)
    {
        $err = array();
        $this->validate($request, [
            'users_paginate' => 'required|numeric|min:1',
            'default_user_status' => 'required',
            'allows_registration' => 'required',
            'allow_reset_password' => 'required',
            'allow_remember' => 'required'
        ]);
        $set_names = [
            'paginate'=>'users_paginate',
            'default_groups'=>'group_selection',
            'default_user_status'=>'default_user_status',
            'allows_registration'=>'allows_registration',
            'allow_reset_password'=>'allow_reset_password',
            'allow_remember'=>'allow_remember'
        ];
        foreach ($set_names as $name => $value) {
            if($name != 'default_groups'){
                $users = Setting::where(['group' => 'users','name' =>$name])
                                    ->update(['value' => $request->input($value)]);
            }else{
                if($request->input('group_selection')){
                    $default_groups = implode(",", $request->input('group_selection'));
                }else{
                    $default_groups = "";
                }
                $users = Setting::where(['group' => 'users','name' =>$name])
                                    ->update(['value' => $default_groups]);
            }
            if(!$users){
                $err[] = $users;
            }
        }

        if(empty($err)){
            return redirect()->back()->with('success', 'Settings Updated!');
        }else{
            return redirect()->back()->with('error', 'Error Updating!: ' . implode(', ', $err));
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function groupsSettings()
    {
        if(Gate::denies('admin')){
            return redirect()->back()->with('error',"You don't have permission");
        }
        //$global_configs = config('global');
        $groups = config('global.groups');
        //dd($groups);
        /*
            "paginate" => "10"
        */
        $data = array(
            'title' => 'Groups/Roles settings',
            'groups' => $groups,
        );
        return view('settings.groups.index')->with($data);
    }

   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateGroupsSettings(Request $request)
    {
        $err = array();
        $this->validate($request, [
            'groups_paginate' => 'required|numeric|min:1'
        ]);
        $set_names = [
            'paginate'=>'groups_paginate'
        ];
        foreach ($set_names as $name => $value) {
            $groups = Setting::where(['group' => 'groups','name' =>$name])
                                ->update(['value' => $request->input($value)]);
            if(!$groups){
                $err[] = $groups;
            }
        }

        if(empty($err)){
            return redirect()->back()->with('success', 'Settings Updated!');
        }else{
            return redirect()->back()->with('error', 'Error Updating!: ' . implode(', ', $err));
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function departmentsSettings()
    {
        if(Gate::denies('admin')){
            return redirect()->back()->with('error',"You don't have permission");
        }
        //$global_configs = config('global');
        $table = config('global.departments_table');
        //dd($table);
        /*
            "paginate" => "10"
        */
        $tree = config('global.departments_tree');
        //dd($tree);
        /*
            "direction" => "NORTH"
            "profile_link" => "1"
            "title" => "1"
            "email" => "1"
            "image" => "1"
            "name" => "1"
        */
        $data = array(
            'title' => 'Organization settings',
            'table' => $table,
            'tree' => $tree
        );
        return view('settings.departments.index')->with($data);
    }

   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDepartmentsTableSettings(Request $request)
    {
        $err = array();
        $this->validate($request, [
            'dep_table_paginate' => 'required|numeric|min:1'
        ]);
        $set_names = [
            'paginate'=>'dep_table_paginate'
        ];
        foreach ($set_names as $name => $value) {
            $departments_tbl = Setting::where(['group' => 'departments_table','name' =>$name])
                                ->update(['value' => $request->input($value)]);
            if(!$departments_tbl){
                $err[] = $departments_tbl;
            }
        }

        if(empty($err)){
            return redirect()->back()->with('success', 'Settings Updated!');
        }else{
            return redirect()->back()->with('error', 'Error Updating!: ' . implode(', ', $err));
        }

    }

   /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDepartmentsTreeSettings(Request $request)
    {
        $err = array();
        $this->validate($request, [
            'tree_direction' => 'required',
            'profile_link' => 'required',
            'is_title' => 'required',
            'is_email' => 'required',
            'is_image' => 'required',
            'is_name' => 'required'
        ]);
        $set_names = [
            'direction'=>'tree_direction',
            'profile_link'=>'profile_link',
            'title'=>'is_title',
            'email'=>'is_email',
            'image'=>'is_image',
            'name'=>'is_name'
        ];
        foreach ($set_names as $name => $value) {
            $departments_tree = Setting::where(['group' => 'departments_tree','name' =>$name])
                                ->update(['value' => $request->input($value)]);
            if(!$departments_tree){
                $err[] = $departments_tree;
            }
        }

        if(empty($err)){
            return redirect()->back()->with('success', 'Settings Updated!');
        }else{
            return redirect()->back()->with('error', 'Error Updating!: ' . implode(', ', $err));
        }

    }

}
