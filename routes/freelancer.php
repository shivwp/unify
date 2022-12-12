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
	Route::post('edit-contact-info', [App\Http\Controllers\Api\FreelancerController::class, 'contactInfo']);

	//freelancer profile delete
	Route::post('delete-portfolio-info', [App\Http\Controllers\Api\FreelancerController::class, 'delete_portfolio_info']);
	Route::post('delete-testimonial-info', [App\Http\Controllers\Api\FreelancerController::class, 'delete_testimonial_info']);
	Route::post('delete-certificate-info', [App\Http\Controllers\Api\FreelancerController::class, 'delete_certificate_info']);
	Route::post('delete-employment-info', [App\Http\Controllers\Api\FreelancerController::class, 'delete_employment_info']);
	Route::post('delete-education-info', [App\Http\Controllers\Api\FreelancerController::class, 'delete_education_info']);

	//Best Match jobs list
    Route::get('best-match-jobs-list', [App\Http\Controllers\Api\JobController::class, 'bestMatchJobsList']);

    //saved jobs
    Route::post('saved-jobs', [App\Http\Controllers\Api\JobController::class, 'savedJobs']);

    //remove saved jobs
    Route::post('remove-saved-jobs', [App\Http\Controllers\Api\JobController::class, 'removeSavedJobs']);

    // account
    Route::post('additional-account', [App\Http\Controllers\Api\AuthController::class, 'additional_account']);

    //send proposal
    Route::post('send-proposal', [App\Http\Controllers\Api\JobController::class, 'sendProposal']);

    //user specialize profile
    Route::post('user-specialize-profile', [App\Http\Controllers\Api\FreelancerController::class, 'userSpecialize']);

    //save job list
    Route::post('freelancer-saved-job', [App\Http\Controllers\Api\FreelancerJobController::class, 'saveJobList']);

    //all proposal
    Route::get('all-proposal', [App\Http\Controllers\Api\ProposalController::class, 'allProposal']);

    //dislike job
    Route::post('dislike-job', [App\Http\Controllers\Api\JobController::class, 'dislikeJob']);

    //Remove from dislike job
    Route::post('remove-dislike-job', [App\Http\Controllers\Api\JobController::class, 'removeDislikeJob']);

	// Contracts List
	Route::get('contracts', [App\Http\Controllers\Api\Freelancer\ContractsController::class, 'ContractsList']);

	// freelancer category
	Route::post('add-category', [App\Http\Controllers\Api\FreelancerController::class, 'addCategory']);

	// withdraw proposal
	Route::post('proposal-withdraw', [App\Http\Controllers\Api\ProposalController::class, 'proposalWithdraw']);

	// invite-decline
	Route::post('invite-decline', [App\Http\Controllers\Api\ProposalController::class, 'inviteDecline']);

	//update proposal 
	Route::post('update-proposal', [App\Http\Controllers\Api\ProposalController::class, 'updateProposal']);

	//accept-offer
	Route::get('accept-offer/{offer_id}', [App\Http\Controllers\Api\Freelancer\ContractsController::class, 'acceptOffer']);

	//decline proposal 
	Route::post('decline-offer', [App\Http\Controllers\Api\ProposalController::class, 'offerDecline']);
});

//freelancer List
Route::get('freelancer-list', [App\Http\Controllers\Api\FreelancerController::class, 'freelancerList']);
Route::get('single-freelancer/{id}', [App\Http\Controllers\Api\FreelancerController::class, 'freelanceSingleData']);

Route::post('get-testimonial', [App\Http\Controllers\Api\FreelancerController::class, 'getTestimonial']);
Route::post('client-testimonial', [App\Http\Controllers\Api\FreelancerController::class, 'clientTestimonial']);