<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Helper\ResponseBuilder;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\FreelancerMeta;
use App\Models\Freelancer;
use App\Models\ClientRating;
use App\Models\User;
use stdClass;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $serverError = 500;
    protected $success = 200;
    protected $badRequest = 400;
    protected $unauthorized = 401;
    protected $notFound = 404;
    protected $forbidden = 403;
    protected $upgradeRequired = 426;

    protected $response;

    public function __construct()
    {
        $this->response = new stdClass();
    }

    public function uploadProfile_image($profile_image)
    {
        $file = $profile_image;
        $name =$file->getClientOriginalName();
        $destinationPath = 'images/profile-image';
        $file->move($destinationPath, $name);
        return $name;
    }

    public function uploadUserDocument($document)
    {
        $file = $document;
        $name =$file->getClientOriginalName();
        $destinationPath = 'images/user-document';
        $file->move($destinationPath, $name);
        return $name;
    }

    public function freelancerPortfolioImage($image)
    {
        $file = $image;
        $name =$file->getClientOriginalName();
        $destinationPath = 'images/freelancer-portfolio';
        $file->move($destinationPath, $name);
        return $name;
    }

    public function proposalImage($image)
    {
        $file = $image;
        $name =$file->getClientOriginalName();
        $destinationPath = 'images/proposals';
        $file->move($destinationPath, $name);
        return $name;
    }

    public function uploadProjectImage($image)
    {
        $file = $image;
        $name = $file->getClientOriginalName();
        $destinationPath = 'images/jobs';
        $file->move($destinationPath, $name);
        return $name;
    }

    public function homepageImage($image)
    {
        $file = $image;
        $name =time() .'-'. $file->getClientOriginalName();
        $destinationPath = 'images/home';
        $file->move($destinationPath, $name);
        return $name;
    }
    public function categoryImage($image)
    {
        $file = $image;
        $name =time() .'-'. $file->getClientOriginalName();
        $destinationPath = 'images/category';
        $file->move($destinationPath, $name);
        return $name;
    }

    public function skillsImage($image)
    {
        $file = $image;
        $name = time().'-'.$file->getClientOriginalName();
        $destinationPath = 'images/skills';
        $file->move($destinationPath, $name);
        return $name;
    }

    public function updateFreelancerMeta($id, $meta_key = "", $meta_value)
    {
        try {
            FreelancerMeta::updateOrCreate(
                [
                    'user_id' => $id,
                    'meta_key' => $meta_key,
                ],
                ['meta_value' => $meta_value]
            );
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getFreelancerMeta($id, $key = "", $status = false)
    {
        if (empty($key)) {
            
            $freelancer_details_add = FreelancerMeta::where('user_id', $id)->select('meta_key', 'meta_value')
                ->pluck('meta_value', 'meta_key')
                ->toArray();
            return $freelancer_details_add;
        } else {
            if ($status) {
                
                $freelancer_details_add = FreelancerMeta::where('user_id', $id)->where('meta_key', $key)->first();
                if (!empty($freelancer_details_add))
                    return $freelancer_details_add->meta_value;
                else
                    return "";
            } else {
                $freelancer_details_add = FreelancerMeta::where('user_id', $id)->where('meta_key', $key)->select('meta_key', 'meta_value')
                    ->pluck('meta_value', 'meta_key')
                    ->toArray();
                return $freelancer_details_add;
            }
        }
    }

    public function updateFreelancerAllMeta($user_id, $meta_key_value = [])
    {
        $key_value = [];
        foreach ($meta_key_value as $key => $value) {
            FreelancerMeta::updateOrCreate(
                ['user_id' => $user_id, 'meta_key' => $key],
                ['meta_value' => ($value) ?? '']
            );
        }
        try {

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getUserInfo($id)
    {
        $userData = User::where('id',$id)->first();
        return $userData;
    }

    public function getFreelancerInfo($freelancer_id){
        $freelancerData = User::with('freelancer.freelancer_portfolio','freelancer.freelancer_testimonial','freelancer.freelancer_certificates','freelancer.freelancer_experiences','freelancer.freelancer_skills','freelancer.freelancer_meta')->where('id',$freelancer_id)->first();
        return $freelancerData;
    }

    public function getClientInfo($clientId)
    {
        $clientData = User::with('client')->where('id', $clientId)->first();
        $ClientRating = ClientRating::where('client_id', $clientId)->get();
        return $clientData;
    }

    public function referCode($name)
    {
        $capitalName = strtoupper($name);
        $randomCode = rand(1000, 9999);
        $code = $capitalName.$randomCode;
        return $code;
    }

    public function stripCustomerPayment($user_id, $stripe_token, $amount)
    {
        try{
            $stripeAccount = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $user = User::where('id',$user_id)->first();

            if(empty($user->customer_id))
            {   
                $customer = $stripeAccount->customers->create([
                    'description' => 'customer_' . $user_id,
                    'email' => $user->email,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    "source" => $stripe_token,
                ]);

                $customer_id = $customer->id;
                // Create new customer
                User::where('id', $user->id)->update(['customer_id' => $customer_id]);
            }
            else {
                $customer_id = $user->customer_id;
            }

            //payment initiate
            $paymentIntent = \Stripe\PaymentIntent::create([
                'customer' => $customer_id,
                'amount' => $amount * 100, 
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                // 'payment_method' => $card_token,//, // 'card_1Jht6ZEUI2VlKHRnc5KrHBMF',
                'transfer_group' => 1,
                'confirm' => 'true',
            ]);

            return $paymentIntent;
        }
        catch(\Stripe\Exception\InvalidRequestException $e)
        {
            return ResponseBuilder::error($e->getMessage(),$this->serverError);
        }
    }


}
