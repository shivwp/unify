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

	//all posting
	Route::post('client-all-posting', [App\Http\Controllers\Api\ClientJobController::class, 'allPosting']);

	//all draft posting
	Route::post('client-draft-posting', [App\Http\Controllers\Api\ClientJobController::class, 'allDraftPosting']);

	//invite freelancer
	Route::post('invite-freelancer', [App\Http\Controllers\Api\ClientJobController::class, 'inviteFreelancer']);

	//invite freelancer
	Route::post('remove-job', [App\Http\Controllers\Api\ClientJobController::class, 'closeJob']);

	//edit freelancer
	Route::post('update-job', [App\Http\Controllers\Api\JobController::class, 'UpdateJob']);	

	//hire freelancer
	Route::post('hire-freelancer', [App\Http\Controllers\Api\ProposalController::class, 'hireFreelancer']);

	//invite freelancer list
	Route::post('all-invite-freelancers', [App\Http\Controllers\Api\ClientJobController::class, 'inviteFreelancerList']);

	// saved Talent
	Route::post('save-talent', [App\Http\Controllers\Api\ClientJobController::class, 'savedTalent']);

	//save talent list
	Route::post('save-talent-list', [App\Http\Controllers\Api\ClientJobController::class, 'saveTalentList']);

	//save talent list
	Route::post('job-freelancer-list', [App\Http\Controllers\Api\ClientListController::class, 'jobFreelancerList']);

	//proposal list
	Route::post('job-proposal-list', [App\Http\Controllers\Api\ClientListController::class, 'jobProposalList']);

});

//category-list
Route::get('category-list', [App\Http\Controllers\Api\JobController::class, 'categoryList']);
Route::get('sub-category-list', [App\Http\Controllers\Api\JobController::class, 'subCategoryList']);

//client List
Route::get('client-list', [App\Http\Controllers\Api\ClientController::class, 'clientList']);

Route::post('single-client', [App\Http\Controllers\Api\ClientController::class, 'singleClient']);

