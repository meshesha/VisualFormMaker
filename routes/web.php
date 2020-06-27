<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
*/

Auth::routes();

Route::post('/login', [
    'uses'          => 'Auth\LoginController@login',
    'middleware'    => 'check-user-status',
]);

Route::namespace("Admin")->prefix('admin')->name('admin.')->middleware('can:manager')->group(function(){
    Route::resource('/users', 'UsersController');
    Route::get('/users/{user}', 'UsersController@changePassword')->name('users.changepass');
    Route::post('/users/{user}', 'UsersController@setPassword')->name('users.setpass');
});


//Route::resource('profile', 'ProfileController');
Route::get('/profile', 'ProfileController@edit')->name('profile.edit');
Route::get('/profile/{user}', 'ProfileController@show')->name('profile.show');
Route::post('/profile/{user}/update', 'ProfileController@update')->name('profile.update');
Route::post('/profile/{user}', 'ProfileController@setPassword')->name('profile.setpass');

Route::resource('groups', 'GroupsController');

Route::get('/org/tree', 'OrgTreeController@tree')->name('org.treeview');
Route::resource('org', 'OrgTreeController');
Route::get('/org/tree/editor', 'OrgTreeController@orgChartEditor')->name('org.editor');


Route::get('/', 'DashboardController@index')
    ->middleware('check-install')
    ->name('index');
Route::get('/dashboard', 'DashboardController@index')
    ->middleware('check-install')
    ->name('dashboard');
Route::get('/about', 'AboutController@index')->name('about');

Route::get('forms/preview/{id}', 'FormsController@preview')->name('forms.preview');
Route::post('forms/{id}', 'FormsController@status')->name('forms.status');
Route::resource('forms', 'FormsController');


/**
 * Public form url
 */
Route::get('/form/{id}', 'RenderController@render')->name('form.render');
Route::post('/form/{id}', 'RenderController@submit')->name('form.submit');
Route::get('/form/{id}/status', 'FormStatusController@status')->name('form.status');

Route::post('/form/{id}/managers', 'FormsController@formManagers')->name('form.managers');
Route::post('/form/{id}/allowsedit', 'FormsController@formAllowsEdit')->name('form.allowsedit');

/**
 * Public SUBMIT form
 */

Route::get('/submitted/data/{form_id}', 'SubmittedFormContentController@index')->name('submitted.index');
Route::get('/submitted', 'SubmittedFormContentController@usersSubmissions')->name('submitted.user.index');
Route::get('/submitted/data/{submission_id}/edit', 'SubmittedFormContentController@usersSubmissionEdit')->name('submitted.user.edit');
Route::get('/submitted/data/{form_id}/{submission_id}', 'SubmittedFormContentController@show')->name('submitted.show');
Route::post('/submitted/data/{id}', 'SubmittedFormContentController@submit')->name('submitted.update');
Route::delete('/submitted/data/{form_id}/{submission_id}/delete', 'SubmittedFormContentController@destroy')->name('submitted.destroy');

//Settings
//Route::resource('settings', 'SettingsController');
Route::get('/settings/app', 'SettingsController@appSettings')->name('settings.app');
Route::get('/settings/dashboard', 'SettingsController@dashboardSettings')->name('settings.dashboard');
Route::post('/settings/dashboard/update', 'SettingsController@updateDashboardSettings')->name('settings.dashboard.update');
Route::get('/settings/forms', 'SettingsController@formsSettings')->name('settings.forms');
Route::post('/settings/forms/update', 'SettingsController@updateFormsSettings')->name('settings.forms.update');
Route::post('/settings/mysubmissions/update', 'SettingsController@updateMySubmissionsSettings')->name('settings.my_submissions.update');
Route::get('/settings/users', 'SettingsController@usersSettings')->name('settings.users');
Route::post('/settings/users/update', 'SettingsController@updateUsersSettings')->name('settings.users.update');
Route::get('/settings/groups', 'SettingsController@groupsSettings')->name('settings.groups');
Route::post('/settings/groups/update', 'SettingsController@updateGroupsSettings')->name('settings.groups.update');
Route::get('/settings/departments', 'SettingsController@departmentsSettings')->name('settings.departments');
Route::post('/settings/departments/table/update', 'SettingsController@updateDepartmentsTableSettings')->name('settings.departments.table.update');
Route::post('/settings/departments/tree/update', 'SettingsController@updateDepartmentsTreeSettings')->name('settings.departments.tree.update');
