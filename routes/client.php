<?php

Route::middleware('auth:api')->group(function () {
	Route::post('edit-client-info', [App\Http\Controllers\Api\ClientController::class, 'edit_info']);
	Route::get('get-client-info', [App\Http\Controllers\Api\ClientController::class, 'get_info']);
	Route::post('freelancer-rating', [App\Http\Controllers\Api\ClientController::class, 'freelancer_rating']);

	//close account
	Route::post('close-account', [App\Http\Controllers\Api\ClientController::class, 'close_account']);

	//post A Job
	Route::post('post-job', [App\Http\Controllers\Api\JobController::class, 'post_job']);

	//FreelanceList list as per skills
	Route::post('skills-freelance-list', [App\Http\Controllers\Api\ClientController::class, 'skillsFreelanceList']);

	//user document verify
	Route::post('user-document-verify', [App\Http\Controllers\Api\AuthController::class, 'userDocumentVerify']);
});

//category-list
Route::get('category-list', [App\Http\Controllers\Api\JobController::class, 'categoryList']);
Route::get('sub-category-list', [App\Http\Controllers\Api\JobController::class, 'subCategoryList']);

//client List
Route::get('client-list', [App\Http\Controllers\Api\ClientController::class, 'clientList']);
