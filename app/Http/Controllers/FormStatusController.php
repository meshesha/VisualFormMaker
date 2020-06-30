<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Forms;

class FormStatusController extends Controller
{
    /**
     * Display a status page
     *
     * @param string id//$identifier
     * @return Response
     */
    public function status($id)
    {
        //$form = Forms::where('id', $id)->firstOrFail();

        //$pageTitle = "Form Submitted!";

        return view('render.status');//, compact('form', 'pageTitle'));
    }
}
