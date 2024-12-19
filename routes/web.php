<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Models\user_dept;
use App\Models\permissions;
use App\Models\role_permissions;
use App\Models\User;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RequestsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('/auth.login');
});

Route::get('/refresh-csrf', function () {
    return response()->json(['csrfToken' => csrf_token()]);
});

Route::get('/check-session', function () {
    if (Auth::check()) {
        return response()->json(['status' => 'active']);
    }
    return response()->json(['status' => 'expired'], 401);
});

Route::get('/dashboard/chart-data/{filter}', [DashboardController::class, 'fetchData']);
Route::get('/dashboard/chart-devicedata/{filter}', [DashboardController::class, 'fetchDeviceData']);
Route::get('/dashboard/chart-locationdata/{filter}', [DashboardController::class, 'fetchlocationData']);
Route::get('/dashboard/chart-routecause/{filter}', [DashboardController::class, 'fetchRouteCauseData']);
Route::get('/dashboard', [DashboardController::class, 'getSidebarData'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
   
});
// System Settings
Route::middleware('auth')->group(function () {
    //user groups
    Route::get('/setting', [SettingsController::class, 'userGroup'])->name('settings.usergroup');
    Route::post('/settings.create-usergroup', [SettingsController::class, 'createUserGroups'])->name('create-usergroup');
    Route::delete('/user-group/{id}', [SettingsController::class, 'DeleteUserGroup'])->name('delete-usergroup');
    Route::get('/settings.user-group/{id}', [SettingsController::class, 'editUserGroup'])->name('settings.editUsergroup');
    Route::put('/user-group/{id}/update', [SettingsController::class, 'updateUserGroup'])->name('settings.update');
    //permissions
    Route::get('/settings', [SettingsController::class, 'userRole'])->name('settings.userRole');
    Route::post('/settings.create-userRole', [SettingsController::class, 'createUserRole'])->name('create-userRole');
    Route::delete('/user-role/{id}', [SettingsController::class, 'DeleteUserRole'])->name('delete-userrole');
    Route::get('/settings.user-role/{id}', [SettingsController::class, 'editUserRole'])->name('settings.editUserRole');
    Route::put('/user-role/{id}/update', [SettingsController::class, 'updateUserRole'])->name('settings.updateRole');
    // User Management

    Route::get('/settings.users', [SettingsController::class, 'user'])->name('settings.user');
    Route::post('/settings.create-user', [SettingsController::class, 'createUser'])->name('create-user');
    Route::get('/settings.user/{id}', [SettingsController::class, 'editUser'])->name('settings.editUser');
    Route::post('/users/{id}/update', [SettingsController::class, 'updateUser'])->name('setting.update');
    Route::patch('/settings/{id}/deactivate', [SettingsController::class, 'deactivate'])->name('deactivateUser');
    Route::patch('/settings/{id}/activate', [SettingsController::class, 'activate'])->name('activateUser');
    // System settings
    Route::get('/settings.system-settings', [SettingsController::class, 'systemSettings'])->name('settings.systemSettings');
    Route::post('/settings.create-settings', [SettingsController::class, 'createSettings'])->name('create-settings');
    Route::delete('/system-settings/{id}', [SettingsController::class, 'DeleteSettings'])->name('delete-settings');
   
    // requests
    Route::get('/requests.systemsRequests', [RequestsController::class, 'systemsRequests'])->name('systemsRequests');
    Route::get('/filter-systems', [RequestsController::class, 'filterSystemsByRequestType'])->name('filter.systems');
    Route::post('/requests/store', [RequestsController::class, 'RequestStore'])->name('requests.store');

    Route::post('/request-types', [RequestsController::class, 'store'])->name('request-types.store');
    Route::get('/requests.requestType', [RequestsController::class, 'requestType'])->name('requestType');

    Route::get('/requests.requestedSystems', [RequestsController::class, 'requestedSystems'])->name('requestedSystems');
    Route::post('/storeRequestedSystems', [RequestsController::class, 'storeRequestedSystems'])->name('request-types.storeRequestedSystems');
    Route::get('/requests.editRequestedSystems/{id}', [RequestsController::class, 'editRequestedSystems'])->name('requests.editRequestedSystems');
    Route::PUT('/request-types-updateRequestedSystems/{id}', [RequestsController::class, 'updateRequestedSystems'])->name('request-types-update.updateRequestedSystems');

    Route::delete('/requestsDelete/{id}', [RequestsController::class, 'DeleteRquestType'])->name('deleteRquestType');
    Route::get('/requests.editRquestType/{id}', [RequestsController::class, 'editRquestType'])->name('requests.editRquestType');
    Route::post('/request-types-update/{id}', [RequestsController::class, 'update'])->name('request-types-update.update');
    Route::get('/requests.OpenRequest', [RequestsController::class, 'OpenRequest'])->name('OpenRequest');
    Route::get('/requests.edit-OpenRequest/{id}', [RequestsController::class, 'editOpenRequest'])->name('requests.editOpenRequest');
    Route::get('/attachments/download/{id}', [RequestsController::class, 'download'])->name('attachments.download');

    Route::put('/updateOpenRequest/{id}', [RequestsController::class, 'updateOpenRequest'])->name('requests.updateOpenRequest');
    Route::post('/request.requestRejection', [RequestsController::class, 'requestRejection'])->name('requestRejection');
    // Second Approvel 
    Route::get('/requests.approvedRequest', [RequestsController::class, 'ApprovedRequest'])->name('ApprovedRequest');
    Route::get('/requests.rejectedRequests', [RequestsController::class, 'rejectedRequests'])->name('rejectedRequests');
    Route::get('/requests.edit-rejectedRequest/{id}', [RequestsController::class, 'editRejectedRequest'])->name('requests.editRejectedRequest');

    Route::get('/requests.editFirstApproval/{id}', [RequestsController::class, 'editFirstApproval'])->name('requests.editFirstApproval');
    Route::put('/updateFirstApproval/{id}', [RequestsController::class, 'updateFirstApproval'])->name('requests.updateFirstApproval');
    Route::get('/requests.unassignedRequest', [RequestsController::class, 'unassignedRequest'])->name('unassignedRequest');
    Route::get('/requests.editUnassignedRequest/{id}', [RequestsController::class, 'editUnassignedRequest'])->name('requests.editUnassignedRequest');
    Route::post('/request.RequestAssignment', [RequestsController::class, 'RequestAssignment'])->name('RequestAssignment');
    Route::get('/requests.assignedRequest', [RequestsController::class, 'assignedRequest'])->name('assignedRequest');
    Route::get('/requests.editAssignedRequest/{id}', [RequestsController::class, 'editAssignedRequest'])->name('requests.editAssignedRequest');
    Route::post('/updateAssignedRequests', [RequestsController::class, 'updateAssignedRequests'])->name('updateAssignedRequests');
    Route::get('/requests.allRequest', [RequestsController::class, 'allRequest'])->name('allRequest');
    Route::get('/requests.mainreport', [RequestsController::class, 'mainreport'])->name('mainreport');
    Route::get('/requests.editAllRequest/{id}', [RequestsController::class, 'editAllRequest'])->name('requests.editAllRequest');

    Route::patch('/access/{id}/deactivate', [RequestsController::class, 'deactivate'])->name('deactivateAccess');

// Route for activating access
Route::patch('/access/{id}/activate', [RequestsController::class, 'activate'])->name('activateAccess');
});


require __DIR__.'/auth.php';
