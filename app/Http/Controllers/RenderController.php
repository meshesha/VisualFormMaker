<?php

namespace App\Http\Controllers;

//use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\models\Forms;
use App\models\SubmittedForms;

use App\User;

class RenderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->middleware('form-access');
    }

    /**
     * Render the form so a user can fill it
     *
     * @param int $id
     * @return Response
     */
    public function render($id)
    {
        $form = Forms::where('id', $id)->firstOrFail();

        $pageTitle = "{$form->name}";

        return view('render.index', compact('form', 'pageTitle'));
    }

    /**
     * Process the form submission
     *
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function submit(Request $request, $id)
    {
        $form = Forms::where('id', $id)->firstOrFail();
        $submitForm = new SubmittedForms;

        try {
            $input = $request->except('_token');

            // check if files were uploaded and process them
            $uploadedFiles = $request->allFiles();
            foreach ($uploadedFiles as $key => $file) {
                // store the file and set it's path to the value of the key holding it
                if ($file->isValid()) {
                    $input[$key] = $file->store('uploads', 'public');
                    
                    // Get filename with the extension
                    //$filenameWithExt = $file->getClientOriginalName();
                    // Get just filename
                    //$filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    // Get just ext
                    //$extension = $file->getClientOriginalExtension();
                    // Filename to store
                    //$fileNameToStore= $filename.'_'.time().'.'.$extension;
                    // Upload Image
                   //$input[$key] = $file->storeAs('public/uploads', $fileNameToStore);
                }
            }

            $user_id = auth()->user()->id ?? null;

            $submitForm->form_id = $form->id;
            $submitForm->user_id = $user_id;
            $submitForm->content = $input;//json_encode($input, true);
            $submitForm->save();
            return redirect()
                    ->route('form.status', $id)
                    ->with('success', 'Form submitted successfully.');
        } catch (Throwable $e) {
            info($e);
            return back()->withInput()->with('error', "There was an error when processing your request.");
        }
    }
}
