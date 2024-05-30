<?php

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
Route::get('/getlangdir', 'SwitchLanguageController@getLanguage');
Route::get('/switchLang/{lang}', 'SwitchLanguageController@switchLang')->name('switch.language');

Route::group(['middleware' => ['installChecker', 'locale']], function () {
    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');

    // Registration Routes...
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');
});

Route::group(['middleware' => ['installChecker', 'locale','prevent-back-history']], function () {
    Route::get('/', function(){
       return redirect()->route('KnowledgeBaseIndex');
    })->name('homePage');
    Route::get('contact-us', 'ContactController@index')->name('contactPage');
    Route::post('contact-store', 'ContactController@store')->name('contactStore');

    Route::get('/knowledge', 'KnowledgeBaseController@KnowledgeBaseIndex')->name('KnowledgeBaseIndex');
    Route::get('/knowledge/{id}/details', 'KnowledgeBaseController@viewArticle')->name('Knowledge.viewArticle');
    Route::post('/knowledge-search', 'KnowledgeBaseController@searchArticles')->name('Knowledge.searchArticles');
    Route::get('/knowledge-pinned/{id}', 'KnowledgeBaseController@pinnedArticle')->name('Knowledge.pinnedArticle');
    Route::get('/category/{category}', 'KnowledgeBaseController@categoryPost')->name('Knowledge.categoryPost');
    Route::post('/kb-vote/{id}', 'VoteController@KBvoteYes')->name('KBvoteYes');

    //Auth::routes();
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@resetForm');

    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('passwordRequest');
    Route::post('reset-password', 'Auth\ForgotPasswordController@recoverResetLinkEmail')->name('passwordReset');

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/about-us', 'HomeController@aboutusPage')->name('aboutusPage');

    // Password Reset Routes...
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.request');


    Route::resource('email-template', 'Admin\EmailTemplateController');
});

Route::group(['middleware' => ['auth', 'installChecker', 'locale']], function () {
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
});

Route::group(['middleware' => ['auth', 'installChecker', 'locale', 'prevent-back-history']], function () {

    //dashboard
    Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');

    Route::get('count', 'NotificationController@count');
    Route::get('countup/{id}', 'NotificationController@countUp');

    //tickets route
    Route::get('add-new-ticket', 'TicketsController@create')->name('submit-new-ticket.create');
    Route::post('new-ticket-store', 'TicketsController@store')->name('new-ticket-store.store');
    Route::get('ticket/{ticket_id}', 'TicketsController@show')->name('ticket.show');
    Route::get('tickets', 'TicketsController@index')->name('tickets.index');
    Route::get('opened-tickets', 'TicketsController@openedTickets')->name('opened-tickets.openedTickets');
    Route::get('closed-tickets', 'TicketsController@ClosedTickets')->name('closed-tickets.ClosedTickets');
    Route::post('close_ticket/{ticket_id}', 'TicketsController@close')->name('close_ticket.close');
    Route::post('reopen/{ticket_id}', 'TicketsController@reOpen')->name('ticketReOpen');
    Route::get('notifications', 'NotificationController@allNotification')->name('allNotification');
    //ticket data
    Route::get('get-ticket-data', 'TicketsController@getTicketData')->name('getTicketData');
    Route::get('ticket-assign-to/{id}', 'TicketsController@assignTo')->name('assignTo');
    Route::post('ticket-assigned/{id}', 'TicketsController@assignToDepartment')->name('assignToDepartment');

    Route::post('comment', 'CommentsController@postComment')->name('comment.postComment');
    //staff route
    Route::get('staffs', 'AdminController@staffList')->name('staffs.staffList');
    Route::get('users', 'AdminController@userList')->name('users.userList');
    Route::get('staff-edit/{id}', 'AdminController@staffEdit')->name('staff-edit.staffEdit');
    Route::post('staff-update/{id}', 'AdminController@staffUpdate')->name('staff-update.staffUpdate');
    Route::get('add-staff', 'AdminController@createStaff')->name('add-staff.createStaff');
    Route::post('save-staff', 'AdminController@saveStaff')->name('save-staff.saveStaff');
    Route::post('staff-status/{id}', 'AdminController@action')->name('staff-status.action');

    //department route
    Route::get('/departments', 'DepartmentsController@index')->name('departments.index');
    Route::get('get-departments-data', 'DepartmentsController@getDepartmentData')->name('getDepartmentData');
    Route::get('/department-create', 'DepartmentsController@create')->name('department-create.create');
    Route::get('/department/{id}/edit', 'DepartmentsController@edit')->name('department-edit');

    Route::get('department', 'DepartmentsController@index')->name('department.index');
    Route::get('department/{id}', 'DepartmentsController@departmentTickets')->name('departmentTickets');

    Route::post('department-save', 'DepartmentsController@store')->name('department-save.store');
    Route::get('department-edit/{id}', 'DepartmentsController@edit')->name('department-edit.edit');
    Route::post('department-update/{id}', 'DepartmentsController@update')->name('department-update.update');
    Route::delete('department-delete/{id}', 'DepartmentsController@destroy')->name('department-delete.destroy');

    //route knowledge
    Route::get('knowledge-base', 'KnowledgeBaseController@index')->name('knowledge-base.index');
    Route::get('knowledge-base-create', 'KnowledgeBaseController@create')->name('knowledge-base-create.create');
    Route::post('kb-store', 'KnowledgeBaseController@store')->name('kb.store');
    Route::get('knowledge-base-edit/{id}', 'KnowledgeBaseController@edit')->name('knowledge-base-edit.edit');
    Route::post('kb-update/{id}', 'KnowledgeBaseController@update')->name('kb.update');
    Route::delete('kb-destroy/{id}', 'KnowledgeBaseController@destroy')->name('kb.destroy');

    //profile route
    Route::get('profile', 'ProfileController@index')->name('profile.index');
    Route::put('profile-update', 'ProfileController@profileUpdate')->name('profileUpdate');
    Route::post('changed-password', 'ProfileController@changedPassword')->name('changed-password.changedPassword');

    //roles route
    Route::get('roles', 'RoleController@index')->name('roles.index');
    Route::get('role-create', 'RoleController@create')->name('role-create.create');
    Route::post('role-save', 'RoleController@store')->name('role-save.store');
    Route::get('role-edit/{id}', 'RoleController@editPermission')->name('role-edit.editPermission');
    Route::post('role-update/{id}', 'RoleController@update')->name('role-update.update');
    Route::delete('role-delete/{id}', 'RoleController@delete')->name('role-delete.delete');

    //settings route
    Route::get('app-settings', 'SettingController@settingIndex')->name('app-settings.settingIndex');
    Route::put('app-settings/{setting}', 'SettingController@appSettingUpdate')->name('appSettingUpdate');
    Route::get('email-settings', 'SettingController@emailSetting')->name('emailSetting');
    Route::put('email-settings/{setting}', 'SettingController@emailSettingUpdate')->name('emailSettingUpdate');

    Route::resource('testimonial', 'Admin\TestimonialController');
    Route::put('testimonial', 'Admin\TestimonialController@testimonialUpdate')->name('setting.testimonialUpdate');

    Route::resource('service', 'Admin\ServiceController');
    Route::put('service-up', 'Admin\ServiceController@servicesUpdate')->name('setting.servicesUpdate');
    //how-we-work
    Route::resource('how-we-work', 'Admin\HowWorkController');
    Route::put('how-we-work', 'Admin\HowWorkController@howWorkUpdate')->name('setting.howWorkUpdate');

    //logo icon settings
    Route::get('logo-icon', 'Admin\GeneralSettingController@logoIcon')->name('logoIcon.Setting');
    Route::put('logo-icon', 'Admin\GeneralSettingController@logoIconUpdate')->name('logoIconUpdate.Setting');
    Route::get('social-link', 'Admin\GeneralSettingController@social')->name('social.Setting');
    Route::post('social-link', 'Admin\GeneralSettingController@socialAdd')->name('socialAdd.Setting');
    Route::put('social-link/{social}', 'Admin\GeneralSettingController@socialUpdate')->name('socialUpdate.Setting');
    Route::delete('social-link-delete/{id}', 'Admin\GeneralSettingController@socialDestroy')->name('socialDestroy.Setting');

    Route::get('header-text', 'Admin\GeneralSettingController@headerTextSetting')->name('headerTextSetting');
    Route::put('header-text/{setting}', 'Admin\GeneralSettingController@headerTextSettingUpdate')->name('headerTextUpSetting');
    Route::get('footer-setting', 'Admin\GeneralSettingController@footer')->name('footer.Setting');
    Route::put('footer-setting', 'Admin\GeneralSettingController@updateFooter')->name('updateFooter.Setting');

    Route::get('aboutus', 'Admin\GeneralSettingController@aboutus')->name('aboutus.Setting');
    Route::put('aboutus', 'Admin\GeneralSettingController@updateAboutUs')->name('updateAboutUs.Setting');
    Route::get('counter', 'Admin\GeneralSettingController@counter')->name('counter.Setting');
    Route::put('counter/{setting}', 'Admin\GeneralSettingController@updateCounter')->name('updateCounter.Setting');
    //inbox
    Route::get('inbox', 'Admin\AdminController@contactMessage')->name('contactMessage');
    Route::get('read-messgae/{contact}', 'Admin\AdminController@readMessage')->name('readMessage');
    Route::delete('delete-messgae/{contact}', 'Admin\AdminController@destroy')->name('message.destroy');
    //custom fields
    Route::get('custom-fields', 'Admin\CustomFieldController@index')->name('CustomFields');
    Route::get('get-custom-field-data', 'Admin\CustomFieldController@getCustomFieldData')->name('getCustomFieldData');
    Route::post('custom-field-store', 'Admin\CustomFieldController@store')->name('CustomFieldStore');
    Route::get('custom-field/{id}/edit', 'Admin\CustomFieldController@edit')->name('CustomFieldEdit');
    Route::post('custom-field-update/{id}', 'Admin\CustomFieldController@update')->name('CustomFieldUpdate');
    Route::delete('custom-field-delete/{id}', 'Admin\CustomFieldController@destroy')->name('CustomFieldDelete');
    Route::get('custom-fields/{id}/options', 'Admin\CustomFieldController@fieldOptions')->name('CustomFieldOptions');
    Route::get('get-options-field-data/{id}', 'Admin\CustomFieldController@fieldOptionsData')->name('fieldOptionsData');
    Route::get('option-field/{id}/edit', 'Admin\CustomFieldController@optionEdit')->name('optionEdit');
    Route::post('custom-field-store/{id}/option', 'Admin\CustomFieldController@storeOption')->name('CustomFieldOptionStore');
    Route::post('custom-field-update-option/{id}', 'Admin\CustomFieldController@updateOption')->name('updateOption');

    //user create
    Route::get('users/create', 'Admin\AdminController@createUser')->name('createUser');
    Route::post('user-store', 'Admin\AdminController@saveUser')->name('saveUser');
    Route::get('user/{id}/edit', 'Admin\AdminController@userEdit')->name('userEdit');
    Route::put('user-update/{id}', 'Admin\AdminController@userUpdate')->name('userUpdate');

    //notification read
    Route::get('notification-read/{id}', 'NotificationController@notificationRead')->name('notificationRead');
});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
