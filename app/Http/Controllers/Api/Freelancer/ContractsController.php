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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Exception;

class ContractsController extends Controller
{
    // freelancer Contracts List
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

            $contractsQuery = Contracts::with('client.client', 'freelancer', 'projectDetails', 'proposal')->where('freelancer_id',$user_id);
            $HourlyContractsQuery = Contracts::with('client', 'freelancer', 'projectDetails', 'proposal')->where('freelancer_id',$user_id)->where('budget_type', 'hourly');
            $ActiveMilestoneQuery =Contracts::with('client', 'freelancer', 'projectDetails', 'proposal')
                                            ->where('freelancer_id',$user_id)
                                            ->where('budget_type', 'fixed')
                                            ->where('status', 'active');
            $AwaitingMilestoneQuery = Contracts::with('client', 'freelancer', 'projectDetails', 'proposal')->where('budget_type', 'fixed')->where('freelancer_id',$user_id)->where('status', 'awaiting-milestones');
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
                return ResponseBuilder::success([],'No Contract Found');
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

        } 
        catch(\Exception $e){
            return ResponseBuilder::error(__($e->getMessage()), $this->serverError);
        }
    }

    public function acceptOffer($offer_id)
    {
        try
        {
            if(!Auth::guard('api')->check())
            {
                return ResponseBuilder::error("User not found", $this->unauthorized);
            }
            $user_id = Auth::guard('api')->user()->id;

            $offerData = SendProposal::where('id',$offer_id)->where('type','offer')->first();
            if($offerData)
            {
                Contracts::updateOrCreate([
                   'proposal_id'    => $offer_id,
                ],
                [
                    'budget_type'   => $offerData->budget_type,
                    'project_id'    => $offerData->project_id,
                    'weekly_limit'  => $offerData->weekly_limit,
                    'proposal_id'   => $offerData->id,
                    'start_time'    => Carbon::now(),
                    'client_id'     => $offerData->client_id,
                    'freelancer_id' => $offerData->freelancer_id,
                    'amount'        => $offerData->amount,
                    'status'        => "active",
                ]);

                $offerStatus = SendProposal::where('id',$offer_id)->first();
                $offerStatus->status = "active";
                $offerStatus->additional_status = "contract";
                $offerStatus->save();
                return ResponseBuilder::successMessage("Contract created successfully", $this->success);
            }else{
                return ResponseBuilder::error("You don't have an offer",$this->notFound);
            }
        }
        catch(\Exception $e)
        {
            return ResponseBuilder::error("Oops! Something went wrong.", $this->serverError);
        }
    }


}