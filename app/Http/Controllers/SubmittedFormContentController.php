<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\models\Forms;
use App\models\SubmittedForms;
use App\User;

class SubmittedFormContentController extends Controller
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
     * Display a listing of the resource.
     *
     * @param integer $form_id
     * @return \Illuminate\Http\Response
     */
    public function index($form_id)
    {
        $user = auth()->user();

        $form = Forms::where(['user_id' => $user->id, 'id' => $form_id])
                    ->orWhere([['admin_users', 'like', "%{$user->id}%"],['id',$form_id]])
                    ->firstOrFail();
        
        //form admin
        $form_admin_ids = $form->admin_users;
        //$form_admin_ids_ary = explode(',', $form_admin_ids);
        $form_admins = explode(',', $form_admin_ids);
        
        if($user->hasAnyRoles(['Administrator','Manager']) || in_array($user->id,$form_admins)){
            $conditions = ['form_id' => $form_id];
        }else{
            $conditions = ['user_id' => $user->id, 'form_id' => $form_id];
        }
        
        $is_enabled_delete = config('global.my_submissions.enable_delete');
        $paginate = (int)config('global.my_submissions.paginate');
        $submitted_entries = SubmittedForms::where($conditions)
                            ->paginate($paginate);

        // get the header for the entries in the form
        $form_headers = $this->getEntriesHeader($form);

        $pageTitle = "Submitted data for '{$form->name}' ({$submitted_entries->count()})";
        $data = array(
            'title' => $pageTitle,
            'submitted_entries' => $submitted_entries,
            'form_headers' => $form_headers,
            'form' => $form,
            'is_enabled_delete' => $is_enabled_delete
            );
        return view('submitted.index')->with($data);
    }
    /**
     * Display a listing of the resource.
     *
     * @param integer $form_id
     * @return \Illuminate\Http\Response
     */
    public function usersSubmissions()
    {
        $user = auth()->user();

        $paginate = (int)config('global.my_submissions.paginate');
        $submissions = SubmittedForms::where('user_id', $user->id)
                        ->with('forms')
                        ->latest()
                        ->paginate($paginate);
        //dd($submissions);
        $is_enabled_delete = config('global.my_submissions.enable_delete');

        $pageTitle = "My Submissions ({$submissions->count()})";

        //return view('submitted.user', compact('submissions', 'pageTitle'));
        $data = array(
            'title' => $pageTitle,
            'submissions' => $submissions,
            'is_enabled_delete' => $is_enabled_delete
        );
        return view('submitted.user.index')->with($data);
        
    }

    /**
     * Edit the specified resource.
     *
     * @param integer $submission_id
     * @return \Illuminate\Http\Response
     */
    public function usersSubmissionEdit($submission_id)
    {

        $user = auth()->user();
        $submission = SubmittedForms::where(['user_id' => $user->id, 'id' => $submission_id])
                            ->with('forms')
                            ->firstOrFail();

        
        // load up my current submissions into the form json data so that the
        // form is pre-filled with the previous submission we are trying to edit.
        $form_builder_array = $this->submissionIntoFormJson($submission);
        $data = array(
            'title' => 'Edit user Submission data',
            'submission' => $submission,
            'form_builder_array'=>$form_builder_array
            );
        return view('submitted.user.edit')->with($data);

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
        $submitForm = SubmittedForms::where('id', $id)->firstOrFail();
        $form = Forms::where('id', $submitForm->form_id)->firstOrFail();
        $files_to_del = array();
        foreach ($submitForm->content as $key => $value) {
            $key_ary = explode('-', $key);
            if($key_ary[0] == "file"){
                $files_to_del[$key] = $value;
            }
        }
        try {
            $input = $request->except('_token');
            //delete old image id uploaded new image - TODO
            // check if files were uploaded and process them
            $uploadedFiles = $request->allFiles();
            
            foreach ($uploadedFiles as $key => $file) {
                // store the file and set it's path to the value of the key holding it
                if ($file->isValid()) {
                    if (array_key_exists($key, $files_to_del)) {
                        //delete $files_to_del[$key]
                        Storage::delete('public/'.$files_to_del[$key]);
                    }
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
    

    /**
     * Load the values the user provided in this submission into the json of the form
     * so that when we render the form, the user's previous values are pre-filled
     *
     * @return form_builder_array
     */
    public function submissionIntoFormJson($submission)
    {
        $submission_content = $submission->content;
        
        $n = collect(json_decode($submission->forms->form_builder_json,true))
                ->map(function ($entry) use ($submission_content) {
                    if (! empty($entry['name']) &&
                        array_key_exists($entry['name'], $submission_content)
                        ) {
                        // the field has a 'name' which means it is not a header or paragraph
                        // and the user previously have an entry for that field in the $submission_content
                        $current_submitted_val = $submission_content[$entry['name']] ?? '';

                        if ((empty($entry['value']) && empty($entry['values']))) {
                            // for input types that do not get their values from a 'values' array
                            // set the staight 'value' string and move on
                            $entry['value'] = $current_submitted_val;
                        } else if (! empty($entry['values'])) {
                            // this will hold what will think is the value of the 'other' input
                            // in a checkbox-group that allows the 'other' option
                            $otherInputVal = null;

                            // manipulate the values array so we can preselect the entries that
                            // were chosen in the submission we have on file.
                            if (is_array($current_submitted_val)) {
                                $entry['values'] = collect($entry['values'])
                                                ->map(function ($v) use ($current_submitted_val) {
                                                    // if this value in the 'values' array is in the
                                                    // previous selection made by the user in their
                                                    // submission, we will add the selected and checked
                                                    // flag to the value so that it will be pre-selected
                                                    // when we render the form
                                                    if (in_array($v['value'], $current_submitted_val)) {
                                                        $v['selected'] = true;
                                                        $v['checked'] = 'checked';
                                                    }

                                                    return $v;
                                                })
                                                ->toArray();
                            }

                            // check if the 'other' input option is available
                            if (! empty($entry['other']) && $entry['other'] === true) {
                                // let's attempt to get the value that was provided via the
                                // 'other' input field of a checkbox-group
                                // get the submitted value that is not part of the 'values'
                                // array for this entry
                                $values_names = collect($entry['values'])
                                            ->map(function ($v) {
                                                return $v['value'];
                                            })
                                            ->toArray();

                                $other = collect($current_submitted_val)
                                            ->filter(function ($sv) use ($values_names) {
                                                return ! in_array($sv, $values_names);
                                            })
                                            ->values();

                                $otherInputVal = $other[0] ?? null;
                            }

                            // still set the value on the entry as we have it
                            $entry['value'] = $otherInputVal ?? $current_submitted_val;
                        }
                    }

                    return $entry;
                });

        return $n;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $form_id
     * @param integer $submission_id
     * @return \Illuminate\Http\Response
     */
    public function show($form_id, $submission_id)
    {
        $user = auth()->user();
        $submission = SubmittedForms::where([
                                'form_id' => $form_id,
                                'id' => $submission_id,
                            ])
                            ->firstOrFail();

        $form = Forms::where(['id' => $form_id])->firstOrFail();
        
        $is_enabled_delete = config('global.my_submissions.enable_delete');
        $is_enables_form_details_view = config('global.my_submissions.enable_form_details_view');

        // get the header for the entries in the form
        $form_headers = $this->getEntriesHeader($form);
        //dd($form_headers);
        $data = array(
            'title' => "Viewing details for submitted data {$submission->id} <h5>'{$form->name}'<h5>",
            'submission' => $submission,
            'form_headers' => $form_headers,
            'form' => $form,
            'is_enabled_delete' => $is_enabled_delete,
            'is_enables_form_details_view' => $is_enables_form_details_view
        );
        return view('submitted.show')->with($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $form_id
     * @param int $submission_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($form_id, $submission_id)
    {
        
        $submission = SubmittedForms::where(['form_id' => $form_id, 'id' => $submission_id])->firstOrFail();
        //dd($submission);
        $files_to_del = array();
        foreach ($submission->content as $key => $value) {
            $key_ary = explode('-', $key);
            if($key_ary[0] == "file"){
                $files_to_del[] = $value;
            }
        }
        if($submission->delete()){
            if(!empty($files_to_del)){
                foreach($files_to_del as $file){
                    //$file = 'uploads/n1jl6X9twCtgL60zxyIGb0iEGTsEeTQ8vBRA6BqE.jpeg'
                     Storage::delete('public/'.$file);
                }
            }
            return redirect()
                        ->route('form.status', $submission_id)
                        ->with('success', 'The submitted form data deleted successfully.');
        }else{
            return redirect()
                        /*->route('form.status', $submission_id)*/
                        ->back()
                        ->with('error', 'Error deleting submitted form data!');
        }

    }

    /**
     * Get an array containing the name of the fields in the form and their label
     *
     * @return Illuminate\Support\Collection
     */
    public function getEntriesHeader($form)
    {  
        $form_builder_array = json_decode($form->form_builder_json, true);
        return collect($form_builder_array)
                    ->filter(function ($entry) {
                        return ! empty($entry['name']);
                    })
                    ->map(function ($entry) {
                        return [
                            'name' => $entry['name'],
                            'label' => $entry['label'] ?? null,
                            'type' => $entry['type'] ?? null,
                        ];
                    });
    }
}
