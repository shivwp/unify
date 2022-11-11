<?php

namespace App\Http\Controllers\Api\Freelancer;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Admin\ContractsResource;
use App\Http\Resources\Admin\ClientResource;
use App\Http\Resources\Admin\ProjectSingleResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Helper\ResponseBuilder;
use App\Models\ProjectProjectSkill;
use App\Models\ProjectCategory;
use App\Models\FreelancerSkill;
use App\Models\SendProposal;
use App\Models\DislikeJob;
use App\Models\PaymentRequest;
use App\Models\Project;
use App\Models\User;
use App\Models\Contracts;
use Illuminate\Http\Request;
use Validator;
use Exception;

class ContractsController extends Controller
{
    // Contracts List
    public function ContractsList(Request $request)
    {
        try {
            //code...
            if (Auth::guard('api')->check()) {
                $user = Auth::guard('api')->user();
                $user_id = $user->id;
            } 
            else{
                return ResponseBuilder::error(__("User not found"), $this->unauthorized);
            }

            $page = !empty($request->pagination) ? $request->pagination : 10; 

            $contracts = Contracts::with('client.client', 'freelancer', 'projectDetails', 'proposal')->get();
            $HourlyContracts = Contracts::with('client', 'freelancer', 'projectDetails', 'proposal')->where('type', 'hourly')->get();
            $ActiveMilestone = Contracts::with('client', 'freelancer', 'projectDetails', 'proposal')->where('type', 'fixed')->where('status', 'active-milestones')->get();
            $AwaitingMilestone = Contracts::with('client', 'freelancer', 'projectDetails', 'proposal')->where('type', 'fixed')->where('status', 'awaiting-milestones')->get();
            $PaymentRequest = PaymentRequest::with('client', 'freelancer', 'projectDetails')->where('status', 'pending')->where('client_id', $user_id)->get();
            if(count($contracts) == 0) {
                return ResponseBuilder::error(__('No Contract Found'), $this->notFound);
            }

            $this->response->All = new ContractsResource($contracts);
            $this->response->Hourly = new ContractsResource($HourlyContracts);
            $this->response->ActiveMilestone = new ContractsResource($ActiveMilestone);
            $this->response->AwaitingMilestone = new ContractsResource($AwaitingMilestone);
            $this->response->PaymentRequest = new ContractsResource($PaymentRequest);

            return ResponseBuilder::success($this->response, "Contracts List Data",$this->success);

        } catch(\Exception $e){
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }

}