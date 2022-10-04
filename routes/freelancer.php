<?php

Route::middleware('auth:api')->group(function () {

	//freelancer profile add/edit
	Route::get('get-freelancer-profile', [App\Http\Controllers\Api\FreelancerController::class, 'get_profile_info']);
	Route::post('edit-name-info', [App\Http\Controllers\Api\FreelancerController::class, 'edit_name_info']);
	Route::post('edit-designation-info', [App\Http\Controllers\Api\FreelancerController::class, 'edit_designation_info']);
	Route::post('edit-skills-info', [App\Http\Controllers\Api\FreelancerController::class, 'edit_skills_info']);
	Route::post('edit-portfolio-info', [App\Http\Controllers\Api\FreelancerController::class, 'edit_portfolio_info']);
	Route::post('edit-testimonial-info', [App\Http\Controllers\Api\FreelancerController::class, 'edit_testimonial_info']);
	Route::post('edit-certificate-info', [App\Http\Controllers\Api\FreelancerController::class, 'edit_certificate_info']);
	Route::post('edit-experience-info', [App\Http\Controllers\Api\FreelancerController::class, 'edit_experience_info']);

	//freelancer profile delete
	Route::post('delete-portfolio-info', [App\Http\Controllers\Api\FreelancerController::class, 'delete_portfolio_info']);
	Route::post('delete-testimonial-info', [App\Http\Controllers\Api\FreelancerController::class, 'delete_testimonial_info']);
	Route::post('delete-certificate-info', [App\Http\Controllers\Api\FreelancerController::class, 'delete_certificate_info']);
	Route::post('delete-experience-info', [App\Http\Controllers\Api\FreelancerController::class, 'delete_experience_info']);
});