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
	Route::post('edit-employment-info', [App\Http\Controllers\Api\FreelancerController::class, 'edit_employment_info']);
	Route::post('edit-education-info', [App\Http\Controllers\Api\FreelancerController::class, 'edit_education_info']);
	Route::post('edit-video', [App\Http\Controllers\Api\FreelancerController::class, 'edit_video']);
	Route::post('edit-language', [App\Http\Controllers\Api\FreelancerController::class, 'edit_language']);
	Route::post('set-visibility', [App\Http\Controllers\Api\FreelancerController::class, 'set_visibility']);
	Route::post('edit-experience-level', [App\Http\Controllers\Api\FreelancerController::class, 'edit_experience_level']);
	Route::post('edit-other-experience', [App\Http\Controllers\Api\FreelancerController::class, 'edit_other_experience']);
	Route::post('edit-location', [App\Http\Controllers\Api\FreelancerController::class, 'edit_location']);
	Route::post('edit-hours-per-week', [App\Http\Controllers\Api\FreelancerController::class, 'hours_per_week']);

	//freelancer profile delete
	Route::post('delete-portfolio-info', [App\Http\Controllers\Api\FreelancerController::class, 'delete_portfolio_info']);
	Route::post('delete-testimonial-info', [App\Http\Controllers\Api\FreelancerController::class, 'delete_testimonial_info']);
	Route::post('delete-certificate-info', [App\Http\Controllers\Api\FreelancerController::class, 'delete_certificate_info']);
	Route::post('delete-employment-info', [App\Http\Controllers\Api\FreelancerController::class, 'delete_employment_info']);
});