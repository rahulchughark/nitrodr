<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ClmController;
use App\Http\Controllers\GeneralActionController;
use App\Http\Controllers\Generatetaskcroon\GenerateCroonTaskController;
use App\Http\Controllers\NoActivityTrigger\AutomailerController;
use App\Http\Controllers\Report\TaskReportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Schools\TaskAssignSchools;
use App\Http\Controllers\Schools\OnboardSchoolsController;
use App\Http\Controllers\ChangePasswordController;


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

Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();
Route::group(['middleware' => 'auth'], function() {
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');
    Route::match(['get', 'post'],'/lead_view/{id}', [ClmController::class, 'clmLeadView']);
    Route::get('/demo/{id}', [ClmController::class, 'demo']);
    Route::get('/lead_view/{id}', [ClmController::class, 'clmLeadView']);
    Route::get('/renewal_lead_view/{id}', [ClmController::class, 'clmRenewalLeadView']);
    Route::get('report/tracker_task_wise', [ClmController::class, 'TrackerTaskWise'])->name("report/tracker_task_wise");
    Route::get('/tracker_trainer_wise/{id}', [ClmController::class, 'TrackerTrainerWise']);
    Route::get('/tracker_cumulative/{id}', [ClmController::class, 'TrackerCumulative']);
    Route::get('/dashboard', [HomeController::class, 'index'])->name('/dashboard');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/task_feedback/{id}', [ClmController::class, 'taskFeedback']);
    Route::post('/task_save', [ClmController::class, 'taskSave'])->name('task_save');
    Route::post('/update_clm', [ClmController::class, 'updateClmModelData'])->name('update_clm');
    Route::post('/clmModelData', [ClmController::class, 'clmModelData'])->name('clmModelData');

    Route::post('/activity-tracker', [ClmController::class, 'ActivityTracker'])->name('activity-tracker');
    Route::post('/activity_save', [ClmController::class, 'ActivitySave'])->name('activity_save');

    Route::post('/edit-clm-form', [ClmController::class, 'editClmForm'])->name('edit_clm_form');
    Route::get('/task', [TaskReportController::class, 'TaskReport'])->name('/task');
    Route::get('/renewal-task', [TaskReportController::class, 'TaskReport'])->name('/renewal-task');
    // Route::get('/assigned-task/{task_id}/{user_id}/{token}', [TaskReportController::class, 'TaskReport'])->name('/assigned-task');
    Route::post('/gradeTools', [ClmController::class, 'gradeTools'])->name('gradeTools');
    Route::post('report/task-data', [TaskReportController::class, 'getTaskReportusingAjax'])->name('report/task-data');
    Route::post('report/tracker-task-wise-data', [ClmController::class, 'GetTrackerTaskWiseData'])->name('report/tracker-task-wise-data');
    Route::get('report/cumulative', [TaskReportController::class, 'CumulativeReport'])->name('report/cumulative');
    Route::post('report/cumulative-report-by-ajax', [TaskReportController::class, 'FetchCumulativeReportByAjax'])->name('report/cumulative-report-by-ajax');
    Route::get('report/trainer', [TaskReportController::class, 'TrackerTrainerWiseReport'])->name('report/trainer');
    Route::post('report/trainer-report-by-ajax', [TaskReportController::class, 'GetTrainerWiseDataByAjax'])->name('report/trainer-report-by-ajax');
    Route::get('/task-for-approval', [TaskReportController::class, 'PendingAndApprovalReport'])->name("/task-for-approval");
    // Route::get('/task-assign-for-approval/{id}', [TaskReportController::class, 'PendingAndApprovalReport'])->name("/task-assign-for-approval");
    Route::post('get-task-approval-report-ajax', [TaskReportController::class, 'GetTaskForApprovalDataByAjax'])->name('get-task-approval-report-ajax');
    Route::post('get-clm-users', [TaskReportController::class, 'GetAllClmUsers'])->name('get-clm-users');
    Route::post('user-assign', [TaskReportController::class, 'GetAllClmUsers'])->name('user-assign');
    Route::any('logout', [LoginController::class, 'logout'])->name('logout');
    
    Route::get('users-list', [UsersController::class, 'usersList'])->name('users-list'); 
    Route::get('users-list-data', [UsersController::class, 'GetUsersListData'])->name('users-list-data');
    Route::POST('edit-user-model', [UsersController::class, 'editUserDataModel'])->name('edit-user-model');
    Route::POST('update_user', [UsersController::class, 'updateUser'])->name('update_user');
    Route::POST('transfer_user_data', [UsersController::class, 'transferUserData'])->name('transfer_user_data');
    
    Route::post('/edit-lead_details', [ClmController::class, 'editLeadDetails'])->name('edit_lead_details');

    ///// create new route for tracker wise data for popup
    Route::post('reports/tracker-wise-popup-data', [ClmController::class, 'GetTrackerTaskWisePopupData'])->name('reports/tracker-wise-popup-data');
    Route::post('reports/trainer-wise-popup-data', [TaskReportController::class, 'GetTrainerCountDataByAjax'])->name('reports/trainer-wise-popup-data');

    // create route for activity tracker for view only
    Route::post('reports/activity-tracker-popup-data', [ClmController::class, 'GetActivityTrackerPopupData'])->name('reports/activity-tracker-popup-data');
    Route::get('reports/activity-tracker', [TaskReportController::class, 'ActivityTrackerReports'])->name('reports/activity-tracker');
    Route::post('reports/activity-tracker-wise-data', [TaskReportController::class, 'GetTrackerActivityWiseData'])->name('reports/activity-tracker-wise-data');

    // schools report route
    Route::get('reports/all-assign-task-schools', [TaskAssignSchools::class, 'AllAssignTaskSchools'])->name('reports/all-assign-task-schools');
    Route::post('reports/schools-task-assign', [TaskAssignSchools::class, 'GetSchoolTaskAsignData'])->name('reports/schools-task-assign');
    Route::post('reports/get-assign-trainer-name-by-school', [TaskAssignSchools::class, 'GetAssignTrainerNameBySchool'])->name('reports/get-assign-trainer-name-by-school');
    Route::post('reports/get-cites-by-state', [TaskAssignSchools::class, 'GetCitesByStates'])->name('reports/get-cites-by-state');

    // school onboard
    Route::get('onboard-schools', [OnboardSchoolsController::class, 'index'])->name('onboard-schools');
    Route::post('get-onboard-schools', [OnboardSchoolsController::class, 'getOnboardSchools'])->name('get-onboard-schools');
    /// export route 
    Route::get('/export/{id?}', [ExportController::class, 'exportData'])->name("export");


    Route::post('/program-initiation/save', [GeneralActionController::class, 'saveProgramInitiation'])->name('program.initiation.save');


    /// user password change ////
    Route::get('change-password', [ChangePasswordController::class, 'index'])->name('password.change');
    Route::post('change-password', [ChangePasswordController::class, 'update'])->name('password.updated');
    Route::post('/check-current-password', [ChangePasswordController::class, 'checkCurrentPassword'])->name('check.current.password');

    ////// end section //////

});
// Add new functionality ////
Route::get('/generate-automail-task-croon', [AutomailerController::class, 'GenerateAutomail'])->name('generate-automail-task-croon');
//Route::get('/test-emails', [AutomailerController::class, 'TestEmail'])->name('test-emails');
//// end section ///////
Route::get('/generate-task-croon', [GenerateCroonTaskController::class, 'GenerateTask'])->name('generate-task-croon');
Route::get('/renewal-generate-task-croon', [GenerateCroonTaskController::class, 'renewalGenerateTask'])->name('renewal-generate-task-croon');
Route::get('/renewal-generate-task-croon-old-task', [GenerateCroonTaskController::class, 'renewalGenerateTaskForOldTask'])->name('renewal-generate-task-croon-old-task');
Route::get('/assigned-task/{task_id}/{user_id}/{token}', [TaskReportController::class, 'TaskReport'])->name('/assigned-task');
Route::get('/task-assign-for-approval/{id}/{user_id}/{token}', [TaskReportController::class, 'PendingAndApprovalReport'])->name("/task-assign-for-approval");
Route::get('/no-action-report/{type}/{task_id}', [TaskReportController::class, 'PendingAndApprovalReport'])->name("/no-action-report");
Route::get('/emails', [TaskReportController::class, 'Emailss'])->name("/emails"); 