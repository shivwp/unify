<?php

Route::middleware('auth:api')->group(function () {
	Route::post('edit-client-info', [App\Http\Controllers\Api\ClientController::class, 'edit_info']);
	Route::get('get-client-info', [App\Http\Controllers\Api\ClientController::class, 'get_info']);
	Route::post('freelancer-rating', [App\Http\Controllers\Api\ClientController::class, 'freelancer_rating']);

	//close account
	Route::post('close-account', [App\Http\Controllers\Api\ClientController::class, 'close_account']);

	//post A Job
	Route::post('post-job', [App\Http\Controllers\Api\JobController::class, 'post_job']);

});

//category-list
Route::get('category-list', [App\Http\Controllers\Api\JobController::class, 'categoryList']);
Route::post('sub-category-list', [App\Http\Controllers\Api\JobController::class, 'subCategoryList']);