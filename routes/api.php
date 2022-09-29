<?php

    //register api
    Route::post('signup', [App\Http\Controllers\Api\AuthController::class, 'signup']);
    Route::post('verifysignup', [App\Http\Controllers\Api\AuthController::class, 'verifysignup']);
    Route::post('resend-otp', [App\Http\Controllers\Api\AuthController::class, 'ResendOtp']);
    Route::post('verify-forgot-otp', [App\Http\Controllers\Api\AuthController::class, 'verifyForgotPasswordOtp']);

    //login api
    Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);

    //country list
    Route::get('coutrylist', [App\Http\Controllers\Api\CommonController::class, 'countrylist']);

    //skill list
    Route::get('skill-list', [App\Http\Controllers\Api\CommonController::class, 'skillList']);

    //forget password
    Route::post('forget-password', [App\Http\Controllers\Api\AuthController::class, 'forget_password_otp']);
    Route::post('reset-password', [App\Http\Controllers\Api\AuthController::class, 'reset_password']);

    Route::get('category_list', [App\Http\Controllers\Api\CommonController::class, 'categorylist']);
    Route::get('timezone_list', [App\Http\Controllers\Api\CommonController::class, 'TimeZone']);
    Route::get('subcategory_list', [App\Http\Controllers\Api\CommonController::class, 'subcategorylist'] );

//Route::middleware('auth:api')->group(function () {
Route::post('change-password', [App\Http\Controllers\Api\AuthController::class, 'changepassword']);
//});




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
