<?php

Route::middleware('auth:api')->group(function () {
	Route::post('edit-client-info', [App\Http\Controllers\Api\ClientController::class, 'edit_info']);
	Route::get('get-client-info', [App\Http\Controllers\Api\ClientController::class, 'get_info']);

	//close account
	Route::post('close-account', [App\Http\Controllers\Api\ClientController::class, 'close_account']);

});