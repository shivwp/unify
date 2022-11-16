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

            // $page = !empty($request->pagination) ? $request->pagination : 10; 

            $contractsQuery = Contracts::with('client.client', 'freelancer', 'projectDetails', 'proposal');
            $HourlyContractsQuery = Contracts::with('client', 'freelancer', 'projectDetails', 'proposal')->where('type', 'hourly');
            $ActiveMilestoneQuery = Contracts::with('client', 'freelancer', 'projectDetails', 'proposal')->where('type', 'fixed')->where('status', 'active-milestones');
            $AwaitingMilestoneQuery = Contracts::with('client', 'freelancer', 'projectDetails', 'proposal')->where('type', 'fixed')->where('status', 'awaiting-milestones');
            $PaymentRequestQuery = PaymentRequest::with('client', 'freelancer', 'projectDetails')->where('status', 'pending')->where('client_id', $user_id);

            $order = 'DESC';
            if(isset($request->sort_by) && $request->sort_by == 'ascending') {
                $order = 'ASC';
            }

            $sortFor = $request->sort_for;
            if(isset($sortFor)  && $sortFor == 'start_date') {
                //
                $contractsQuery->orderBy('start_time', $order); 
                $HourlyContractsQuery->orderBy('start_time', $order);
                $ActiveMilestoneQuery->orderBy('start_time', $order);
                $AwaitingMilestoneQuery->orderBy('start_time', $order);
                // $PaymentRequestQuery->orderBy('start_time', $order);
            }

            if(isset($sortFor)  && $sortFor == 'end_date') {
                //
                $contractsQuery->orderBy('end_time', $order); 
                $HourlyContractsQuery->orderBy('end_time', $order);
                $ActiveMilestoneQuery->orderBy('end_time', $order);
                $AwaitingMilestoneQuery->orderBy('end_time', $order);
                // $PaymentRequestQuery->orderBy('end_time', $order);
            }

            if(isset($sortFor)  && $sortFor == 'contract_name') {
                //
                $contractsQuery->orderBy('project_title', $order); 
                $HourlyContractsQuery->orderBy('project_title', $order);
                $ActiveMilestoneQuery->orderBy('project_title', $order);
                $AwaitingMilestoneQuery->orderBy('project_title', $order);
                
                // $PaymentRequestQuery->orderBy('project_title', $order);
            }

            if(isset($request->closed_accounts) && $request->closed_accounts){

                // $contractsQuery->Where('status', 'closed'); 
                // $HourlyContractsQuery->Where('status', 'closed');
                // $ActiveMilestoneQuery->Where('status', 'closed');
                // $AwaitingMilestoneQuery->Where('status', 'closed');
            } else {
                $contractsQuery->where('status', '!=', 'closed'); 
                // $HourlyContractsQuery->where('status', '!=', 'clossed');
                // $ActiveMilestoneQuery->where('status', '!=', 'clossed');
                // $AwaitingMilestoneQuery->where('status', '!=', 'clossed');
            }
            
            $contracts = $contractsQuery->get();
            if(count($contracts) == 0) {
                return ResponseBuilder::error(__('No Contract Found'), $this->notFound);
            }

            $HourlyContracts = $HourlyContractsQuery->get();
            $ActiveMilestone = $ActiveMilestoneQuery->get();
            $AwaitingMilestone = $AwaitingMilestoneQuery->get();
            $PaymentRequest = $PaymentRequestQuery->get();
            
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