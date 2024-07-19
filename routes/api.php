<?php

use App\Http\Controllers\Admin\GeneralSettingController;
use App\Http\Controllers\Admin\HowWorkController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TicketsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SwitchLanguageController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/getlangdir', [SwitchLanguageController::class, 'getLanguage']);
Route::get('/switchLang/{lang}', [SwitchLanguageController::class, 'switchLang'])->name('switch.language');

Route::post('login', [LoginController::class, 'login']);

//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//Route::post('register', 'Auth\RegisterController@register');

//TODO: add routes for guest users

//Route::middleware('auth:api')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboard']);

    Route::get('count', [NotificationController::class, 'count']);
    Route::get('countup/{id}', [NotificationController::class, 'countUp']);
    Route::get('notifications', [NotificationController::class, 'allNotification']);
    Route::get('notification-read/{id}', [NotificationController::class, 'notificationRead']);

    //tickets route
    Route::get('add-new-ticket', [TicketsController::class, 'create']);
    Route::post('new-ticket-store', [TicketsController::class, 'store']);
    Route::get('ticket/{ticket_id}', [TicketsController::class, 'show']);
    Route::get('tickets', [TicketsController::class, 'index']);
    Route::get('opened-tickets', [TicketsController::class, 'openedTickets']);
    Route::get('closed-tickets', [TicketsController::class, 'ClosedTickets']);
    Route::post('close_ticket/{ticket_id}', [TicketsController::class, 'close']);
    Route::post('reopen/{ticket_id}', [TicketsController::class, 'reOpen']);
    Route::get('get-ticket-data', [TicketsController::class, 'getTicketData']);
    Route::get('ticket-assign-to/{id}', [TicketsController::class, 'assignTo']);
    Route::post('ticket-assigned/{id}', [TicketsController::class, 'assignToDepartment']);

    Route::post('comment', [CommentsController::class, 'postComment']);

    //staff route
    Route::get('staffs', [AdminController::class, 'staffList']);
    Route::get('users', [AdminController::class, 'userList']);
    Route::get('staff-edit/{id}', [AdminController::class, 'staffEdit']);
    Route::post('staff-update/{id}', [AdminController::class, 'staffUpdate']);
    Route::get('add-staff', [AdminController::class, 'createStaff']);
    Route::post('save-staff', [AdminController::class, 'saveStaff']);
    Route::post('staff-status/{id}', [AdminController::class, 'action']);

    //department route
    Route::get('/departments', [DepartmentsController::class, 'index']);
    Route::get('get-departments-data', [DepartmentsController::class, 'getDepartmentData']);
    Route::get('department/{id}', [DepartmentsController::class, 'departmentTickets']);
    Route::post('department-save', [DepartmentsController::class, 'store']);
    Route::post('department-update/{id}', [DepartmentsController::class, 'update']);
    Route::delete('department-delete/{id}', [DepartmentsController::class, 'destroy']);

    //route knowledge
    Route::get('knowledge-base', [KnowledgeBaseController::class, 'index']);
    Route::post('kb-store', [KnowledgeBaseController::class, 'store']);
    Route::get('knowledge-base-edit/{id}', [KnowledgeBaseController::class, 'show']);
    Route::post('kb-update/{id}', [KnowledgeBaseController::class, 'update']);
    Route::delete('kb-destroy/{id}', [KnowledgeBaseController::class, 'destroy']);

    //profile route
    Route::get('profile', [ProfileController::class, 'index']);
    Route::put('profile-update',  [ProfileController::class, 'profileUpdate']);
    Route::post('changed-password',  [ProfileController::class, 'changedPassword']);

    //roles route
    Route::get('roles', [RoleController::class, 'index']);
    Route::post('role-save', [RoleController::class, 'store']);
    Route::get('role-edit/{id}', [RoleController::class, 'editPermission']);
    Route::post('role-update/{id}', [RoleController::class, 'update']);
    Route::delete('role-delete/{id}', [RoleController::class, 'delete']);

    //settings route
    Route::get('app-settings', [SettingController::class, 'settingIndex']);
    Route::put('app-settings/{setting}', [SettingController::class, 'appSettingUpdate']);
    Route::get('email-settings', [SettingController::class, 'emailSetting']);
    Route::put('email-settings/{setting}', [SettingController::class, 'emailSettingUpdate']);

    Route::resource('testimonial', TestimonialController::class);
    Route::put('testimonial', [TestimonialController::class, 'testimonialUpdate']);

    Route::resource('service', ServiceController::class);
    Route::put('service-up', [ServiceController::class, 'servicesUpdate']);
    //how-we-work
    Route::resource('how-we-work', HowWorkController::class);
    Route::put('how-we-work', [HowWorkController::class, 'howWorkUpdate']);

    //logo icon settings
    Route::get('social-link', [GeneralSettingController::class, 'social']);
    Route::post('social-link', [GeneralSettingController::class, 'socialAdd']);
    Route::put('social-link/{social}', [GeneralSettingController::class, 'socialUpdate']);
    Route::delete('social-link-delete/{id}', [GeneralSettingController::class, 'socialDestroy']);
    Route::get('header-text', [GeneralSettingController::class, 'headerTextSetting']);
    Route::put('header-text/{setting}', [GeneralSettingController::class, 'headerTextSettingUpdate']);
    Route::get('footer-setting', [GeneralSettingController::class, 'footer']);
    Route::put('footer-setting', [GeneralSettingController::class, 'updateFooter']);
    Route::get('aboutus', [GeneralSettingController::class, 'aboutus']);
    Route::put('aboutus', [GeneralSettingController::class, 'updateAboutUs']);
    Route::get('counter', [GeneralSettingController::class, 'counter']);
    Route::put('counter/{setting}', [GeneralSettingController::class, 'updateCounter']);

    //custom fields
    Route::get('get-custom-field-data',  [App\Http\Controllers\Admin\CustomFieldController::class, 'getCustomFieldData']);
    Route::post('custom-field-store', [App\Http\Controllers\Admin\CustomFieldController::class, 'store']);
    Route::get('custom-field/{id}/edit', [App\Http\Controllers\Admin\CustomFieldController::class, 'edit']);
    Route::post('custom-field-update/{id}', [App\Http\Controllers\Admin\CustomFieldController::class, 'update']);
    Route::delete('custom-field-delete/{id}', [App\Http\Controllers\Admin\CustomFieldController::class, 'destroy']);
    Route::get('custom-fields/{id}/options', [App\Http\Controllers\Admin\CustomFieldController::class, 'fieldOptions']);
    Route::get('get-options-field-data/{id}', [App\Http\Controllers\Admin\CustomFieldController::class, 'fieldOptionsData']);
    Route::get('option-field/{id}/edit', [App\Http\Controllers\Admin\CustomFieldController::class, 'optionEdit']);
    Route::post('custom-field-store/{id}/option', [App\Http\Controllers\Admin\CustomFieldController::class, 'storeOption']);
    Route::post('custom-field-update-option/{id}', [App\Http\Controllers\Admin\CustomFieldController::class, 'updateOption']);

    //user create
    Route::get('inbox', [App\Http\Controllers\Admin\AdminController::class, 'contactMessage']);
    Route::get('read-messgae/{contact}', [App\Http\Controllers\Admin\AdminController::class, 'readMessage']);
    Route::delete('delete-messgae/{contact}',  [App\Http\Controllers\Admin\AdminController::class, 'destroy']);
    Route::post('user-store', [App\Http\Controllers\Admin\AdminController::class, 'saveUser']);
    Route::get('user/{id}/edit', [App\Http\Controllers\Admin\AdminController::class, 'userEdit']);
    Route::put('user-update/{id}', [App\Http\Controllers\Admin\AdminController::class, 'userUpdate']);
//});

