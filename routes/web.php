<?php

Route::redirect('/', '/login');
Route::redirect('/home', '/admin');
Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('logout', 'HomeController@logout')->name('logout');
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');
    Route::get('category-replace', 'ProjectCategoryController@category_replace')->name('category');
    Route::POST('category-delete-replace', 'ProjectCategoryController@category_delete_replace')->name('replace');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');
    Route::get('user/statusupdate/{id}', 'UsersController@statusupdate');

    // Currencies
    Route::delete('currencies/destroy', 'CurrencyController@massDestroy')->name('currencies.massDestroy');
    Route::resource('currencies', 'CurrencyController');

    // Transactiontypes
    Route::delete('transaction-types/destroy', 'TransactionTypeController@massDestroy')->name('transaction-types.massDestroy');
    Route::resource('transaction-types', 'TransactionTypeController');

    // Incomesources
    Route::delete('income-sources/destroy', 'IncomeSourceController@massDestroy')->name('income-sources.massDestroy');
    Route::resource('income-sources', 'IncomeSourceController');

    // Clientstatuses
    Route::delete('client-statuses/destroy', 'ClientStatusController@massDestroy')->name('client-statuses.massDestroy');
    Route::resource('client-statuses', 'ClientStatusController');

    // Projectstatuses
    Route::delete('project-statuses/destroy', 'ProjectStatusController@massDestroy')->name('project-statuses.massDestroy');
    Route::resource('project-statuses', 'ProjectStatusController');
    
    //Mail
    Route::resource('mail', 'MailController');

    //Close Reason
    Route::resource('close-reason', 'CloseReasonController');

    // Project Close Reason
    Route::resource('project-close-reason', 'ProjectCloseReasonController');

    //Dislike Reason
    Route::resource('dislike-reason', 'DislikeReasonController');

    //hoursPerWeek
    Route::resource('hours-per-week', 'HoursController');

    // Projectscategory
    Route::delete('project-category/destroy', 'ProjectCategoryController@massDestroy')->name('project-category.massDestroy');
    Route::resource('project-category', 'ProjectCategoryController');

    // Projectskill
    Route::delete('project-skill/destroy', 'ProjectSkillController@massDestroy')->name('project-skill.massDestroy');
    Route::resource('project-skill', 'ProjectSkillController');

    // ProjectListingType
    Route::delete('project-listing-type/destroy', 'ProjectListingTypeController@massDestroy')->name('project-listing-type.massDestroy');
    Route::resource('project-listing-type', 'ProjectListingTypeController');

    // site setting
    Route::delete('site-setting/destroy', 'SiteSettingController@massDestroy')->name('site-setting.massDestroy');
    Route::resource('site-setting', 'SiteSettingController');

    // Clients
    Route::delete('clients/destroy', 'ClientController@massDestroy')->name('clients.massDestroy');
    Route::resource('clients', 'ClientController');
    Route::get('clients-show/{id}','ClientController@show')->name('client');

    //Freelancer
    Route::resource('freelancer', 'FreelancerController');

    // Projects
    Route::delete('projects/destroy', 'ProjectController@massDestroy')->name('projects.massDestroy');
    Route::resource('projects', 'ProjectController');
    Route::get('/projects-pdf', 'ProjectController@createPDF');
    Route::get('/projects-excel', 'ProjectController@export_in_excel');
    Route::get('/project-delete/{id}', 'ProjectController@destroy');
    Route::POST('/project-multi-delete', 'ProjectController@project_multi_delete');

    Route::get('project-proposal/{id}', 'ProjectController@project_proposal')->name('project-proposal');

    // Contracts
    Route::resource('contracts', 'ContractController');

    //Jobs
    Route::resource('jobs', 'JobController');
    Route::get('/jobs-delete/{id}', 'JobController@destroy');

      
    // Business
    Route::resource('business_size', 'Business_sizeController');
    Route::get('/business-delete/{id}', 'Business_sizeController@destroy');
    
    // Industry
    Route::resource('industry', 'IndustryController');

    // Certificate
    Route::resource('certificate', 'CertificateController');

    // Degree
    Route::resource('degree', 'DegreeController');

    // Specialization
    Route::resource('specialization', 'SpecializationController');

    // Pages
    Route::resource('page', 'PageController');

    //subscription
    Route::post('service/{id}', 'ServicesController@destroy')->name('service');
    Route::get('service-update/{id}', 'ServicesController@edit')->name('service');
    Route::resource('service', 'ServicesController');

    // Notes
    Route::post('plan/{id}', 'SubscriptionPlansController@destroy')->name('plan');
    Route::get('plan-update/{id}', 'SubscriptionPlansController@edit')->name('plan');
    Route::resource('plan', 'SubscriptionPlansController');

    //proposals
    Route::post('proposal/{id}', 'ProposalController@destroy')->name('proposal');
    Route::get('proposal-update/{id}', 'ProposalController@edit')->name('proposal');
    Route::get('proposal-show/{id}', 'ProposalController@show')->name('proposal');    
    Route::resource('proposal', 'ProposalController');

    //Notes
    Route::delete('notes/destroy', 'NoteController@massDestroy')->name('notes.massDestroy');
    Route::resource('notes', 'NoteController');

    // Documents
    Route::delete('documents/destroy', 'DocumentController@massDestroy')->name('documents.massDestroy');
    Route::post('documents/media', 'DocumentController@storeMedia')->name('documents.storeMedia');
    Route::resource('documents', 'DocumentController');

    // Transactions
    Route::delete('transactions/destroy', 'TransactionController@massDestroy')->name('transactions.massDestroy');
    Route::resource('transactions', 'TransactionController');
    Route::get('/transaction-pdf', 'TransactionController@createPDF');

    //Support
    Route::get('support-edit/{id}','SupportController@edit')->name('support');
    Route::get('support-closed/{id}', 'SupportController@ticket_close')->name('support');
    Route::get('support-delete/{id}', 'SupportController@destroy')->name('support');
    Route::resource('support', 'SupportController'); 
    Route::get('/support-pdf', 'SupportController@createPDF');

    //Notification
    Route::get('/notification', 'HomeController@notification');

    // Clientreports
    Route::delete('client-reports/destroy', 'ClientReportController@massDestroy')->name('client-reports.massDestroy');
    Route::resource('client-reports', 'ClientReportController');

    //ckeditor
    Route::post('', [App\Http\Controllers\HomeController::class, 'upload'])->name('ckeditor.upload');
});
