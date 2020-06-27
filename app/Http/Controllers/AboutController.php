<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $app_ver = "1.0.0";
        $data = array(
            'title' => 'no-title-header',
            'app_ver'=> $app_ver
        );
        return view('pages.about')->with($data);
    }
}
