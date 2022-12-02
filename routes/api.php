<?php

    //register api
    Route::post('signup', [App\Http\Controllers\Api\AuthController::class, 'signup']);
    Route::post('verifysignup', [App\Http\Controllers\Api\AuthController::class, 'verifysignup']);
    Route::post('resend-otp', [App\Http\Controllers\Api\AuthController::class, 'ResendOtp']);
    Route::post('verify-forgot-otp', [App\Http\Controllers\Api\AuthController::class, 'verifyForgotPasswordOtp']);

    //login api
    Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);

    //Google login 
    Route::post('social-login', [App\Http\Controllers\Api\AuthController::class, 'social']);

    //Online status 
    Route::post('online-status', [App\Http\Controllers\Api\AuthController::class, 'onlineStatus']);

    //country list
    Route::get('coutrylist', [App\Http\Controllers\Api\CommonController::class, 'countrylist']);

    //languages list
    Route::get('languages-list', [App\Http\Controllers\Api\CommonController::class, 'languageslist']);

    //close account reason list
    Route::get('close-account-reason-list', [App\Http\Controllers\Api\CommonController::class, 'accountCloseReasonList']);

    //job close reason list
    Route::get('close-job-reason-list', [App\Http\Controllers\Api\CommonController::class, 'jobCloseReasonList']);

    //hoursPerWeek
    Route::get('hours-per-week', [App\Http\Controllers\Api\CommonController::class, 'hoursPerWeek']);

    //skill list
    Route::post('skill-list', [App\Http\Controllers\Api\CommonController::class, 'skillList']);

    //degree list
    Route::get('degree-list', [App\Http\Controllers\Api\CommonController::class, 'degreelist']);

    //job list
    Route::post('jobs-list', [App\Http\Controllers\Api\JobController::class, 'jobsList']);

    //Recent job list
    Route::get('recent-jobs-list', [App\Http\Controllers\Api\JobController::class, 'recentJobsList']);

    //industry list
    Route::get('industries-list', [App\Http\Controllers\Api\CommonController::class, 'industriesList']); 
    
    //forget password
    Route::post('forget-password', [App\Http\Controllers\Api\AuthController::class, 'forget_password_otp']);
    //Page data
    Route::get('page/{slug}', [App\Http\Controllers\Api\CommonController::class, 'page']);

    Route::post('reset-password', [App\Http\Controllers\Api\AuthController::class, 'reset_password']);

    Route::get('timezone_list', [App\Http\Controllers\Api\CommonController::class, 'TimeZone']);
    Route::get('dislike-reasons', [App\Http\Controllers\Api\CommonController::class, 'dislike_reasons']);
    Route::get('subcategory_list', [App\Http\Controllers\Api\CommonController::class, 'subcategorylist'] );
    Route::post('specialization-list', [App\Http\Controllers\Api\CommonController::class, 'specializationlist']);
    Route::get('certificate-list', [App\Http\Controllers\Api\CommonController::class, 'certificatelist']);
    Route::post('change-password', [App\Http\Controllers\Api\AuthController::class, 'changepassword']);
    Route::post('single-job', [App\Http\Controllers\Api\JobController::class, 'singleJob']);
    Route::get('home-data', [App\Http\Controllers\Api\CommonController::class, 'homeData']);
    Route::post('category-skills', [App\Http\Controllers\Api\CommonController::class, 'categorySkills']);
    Route::post('skill-freelancer', [App\Http\Controllers\Api\CommonController::class, 'skillfreelaner']);
    Route::get('subscription-list', [App\Http\Controllers\Api\CommonController::class, 'subscriptionList']);

Route::middleware('auth:api')->group(function () {
    Route::get('connected-service', [App\Http\Controllers\Api\AuthController::class, 'connected_service']);
    Route::get('submit-profile', [App\Http\Controllers\Api\AuthController::class, 'submitProfile']);
    Route::post('subscription-payment', [App\Http\Controllers\Api\PaymentController::class, 'subscriptionPayment']);
    
    Route::post('save-archive', [App\Http\Controllers\Api\ClientJobController::class, 'savetoArchive']);
    Route::get('arvhive-list/{job_id}',[App\Http\Controllers\Api\ClientJobController::class,'archiveFreelancer']);
    Route::post('remove-archive',[App\Http\Controllers\Api\ClientJobController::class,'removeArchiveFreelancer']);

    Route::post('save-shortlist',[App\Http\Controllers\Api\ClientJobController::class,'addInShortList']);
    Route::post('remove-shortlist',[App\Http\Controllers\Api\ClientJobController::class,'removeFromShortList']);
    Route::get('shortlist-list/{job_id}',[App\Http\Controllers\Api\ClientJobController::class,'shortListDetail']);
});

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::apiResource('users', 'UsersApiController');

    // Currencies
    Route::apiResource('currencies', 'CurrencyApiController');

    // Transactiontypes
    Route::apiResource('transaction-types', 'TransactionTypeApiController');

    // Incomesources
    Route::apiResource('income-sources', 'IncomeSourceApiController');

    // Clientstatuses
    Route::apiResource('client-statuses', 'ClientStatusApiController');

    // Projectstatuses
    Route::apiResource('project-statuses', 'ProjectStatusApiController');

    // Clients
    Route::apiResource('clients', 'ClientApiController');

    // Projects
    Route::apiResource('projects', 'ProjectApiController');

    // Notes
    Route::apiResource('notes', 'NoteApiController');

    // Documents
    Route::post('documents/media', 'DocumentApiController@storeMedia')->name('documents.storeMedia');
    Route::apiResource('documents', 'DocumentApiController');

    // Transactions
    Route::apiResource('transactions', 'TransactionApiController');

    // Clientreports
    Route::apiResource('client-reports', 'ClientReportApiController');
});
