<?php

namespace App\Http\Middleware;
//use Illuminate\Auth\Middleware\Authenticate;
use Closure;
use App\models\Forms;
use App\User;

class FormAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id = $request->route('id');
        $form = Forms::where('id', $id)->firstOrFail();
        //$type = $form->type;
        $visibility = $form->visibility_type;
        $form_status = $form->status;
        if($form_status != "2"){
            return redirect()
                        ->route('form.status',$id)
                        ->with('error', "The Form is unpublished.");
        }
        if($visibility != "1"){
            //if not public requires auth
            if (!auth()->check()) {

                return redirect()
                        ->route('login')
                        ->with('error', "Form '{$form->name}' requires you to login before you can access it.");
                         
            }else{
                $visibileTo = $form->visibility_to;

                $user = auth()->user();

                if($visibility == "3"){
                    if(!$user->hasAnyRoleId(explode(",",$visibileTo))){
                        return redirect()
                                ->route('form.status', $id)
                                ->with('error', "Sorry you're not allowed to access this form.(role/group restrict)");
                    }
                }else if($visibility == "4"){
                    //Check if user is in form dep 
                    if(!in_array($user->department, explode(",",$visibileTo))){
                        return redirect()
                                ->route('form.status', $id)
                                ->with('error', "Sorry you're not allowed to access this form.(department restrict)");
                    }
                }
            }
        }

        return $next($request);
    }

    public function authenticate(Request $request, Closure $next)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended('dashboard');
            //return $next($request);
        }
    }
}
