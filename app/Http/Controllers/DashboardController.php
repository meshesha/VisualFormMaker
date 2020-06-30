<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('check-install');
        $filename = storage_path("installed");
        if (file_exists($filename)) {
            $this->middleware('auth');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //$user = auth()->user();
        $dashboard_settings = config('global.dashboard');
        $data = array(
            'title' => 'Dashboard',
            'dashboard_settings'=>$dashboard_settings
        );
        return view('dashboard')->with($data);
    }
}
