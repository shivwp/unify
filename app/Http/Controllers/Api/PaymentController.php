<?php

namespace App\Http\Controllers\Api;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionPayment;
use App\Helper\ResponseBuilder;
use Illuminate\Http\Request;
use App\Models\UserCard;
use App\Models\Plans;
use App\Models\User;
use Validator;
use Stripe;
use DB;

class PaymentController extends Controller
{
	public function subscriptionPayment(Request $request)
	{
		try
		{
			if(Auth::guard('api')->check())
			{
				$singleuser = Auth::guard('api')->user();
				$user_id = $singleuser->id;
				$user_email = $singleuser->email;
			}else
			{
				return ResponseBuilder::error('User not found',$this->unauthorized);
			}

			$validator = Validator::make($request->all(),[
				'subscription_id' 	=> 'required|exists:plans,id',
				'stripe_token'		=> 'required',
			]);

			if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }

			$stripeAccount = new \Stripe\StripeClient(env('STRIPE_SECRET'));
			\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

			$user = User::where('email',$user_email)->first();

			if(empty($user->customer_id))
			{	
		        $customer = $stripeAccount->customers->create([
		            'description' => 'customer_' . $user_id,
		            'email' => $user_email,
		            'name' => $singleuser->first_name . ' ' . $singleuser->last_name,
		            "source" => $request->stripe_token,
		        ]);

		        $customer_id = $customer->id;
		        // Create new customer
		        User::where('id', $user->id)->update(['customer_id' => $customer_id]);
			}
			else {
		        $customer_id = $user->customer_id;
		    }

		    // Subscription plan 
		    $subscriptionData = Plans::where('id',$request->subscription_id)->select('amount')->first();

		    // Initiate payment
		    $paymentIntent = \Stripe\PaymentIntent::create([
	          	'customer' => $customer_id,
	          	'amount' => $subscriptionData->amount * 100, //$booking->price * 100,//$subscriptionData->amount * 
	          	'currency' => 'usd',
	          	'payment_method_types' => ['card'],
	          	// 'payment_method' => $card_token,//, // 'card_1Jht6ZEUI2VlKHRnc5KrHBMF',
	          	'transfer_group' => 1,
	          	'confirm' => 'true',
	        ]);


	        if ($paymentIntent->status == 'succeeded') {
		    	$user = User::where('id',$user_id)->first();
		    	$user->plan_id = $request->subscription_id;
		    	$user->save();

		    	$subscription = new SubscriptionPayment;
		    	$subscription->user_id = $user_id;
		    	$subscription->subscription_id = $request->subscription_id;
		    	$subscription->transaction_id = $paymentIntent->id;
		    	$subscription->status = $paymentIntent->status;
		    	$subscription->save();

		    	return ResponseBuilder::successMessage('Subscription upgrade successfully',$this->success);
		    }else
		    {
		    	$subscription = new SubscriptionPayment;
		    	$subscription->user_id = $user_id;
		    	$subscription->subscription_id = $request->subscription_id;
		    	$subscription->transaction_id = $paymentIntent->id;
		    	$subscription->status = $paymentIntent->status;
		    	$subscription->save();
		    	return ResponseBuilder::error('Subscription is '.$paymentIntent->status,$this->badRequest);
		    }
		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error("Oops! Something went wrong.",$this->serverError);
		}
	}
	
	public function addCard(Request $request)
	{
		try
		{
			try{
			if (Auth::guard('api')->check()) {
            	$singleuser = Auth::guard('api')->user();
            	$user_id = $singleuser->id;
         	} 
         	else{
            	return ResponseBuilder::error(__("User not found"), $this->unauthorized);
         	}
         	$validator = Validator::make($request->all(), [
            	'stripe_token'       => 'required',
         	]);
         	if ($validator->fails()) {
            	return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
         	}
         	$stripeAccount = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

         	$user = User::where('id', $user_id)->first();

         	if(empty($user->customer_id)){

         		$customer = $stripeAccount->customers->create([
		            'description' 	=> 'customer_' . $user->id,
		            'email' 		=> $user->email,
		            'name' 			=> $user->first_name . ' ' . $user->last_name,
		            "source" 		=> $request->stripe_token,
		        ]);

		        $customer_id = $customer->id;

		        // Create new customer
		        User::where('id', $user->id)->update(['customer_id' => $customer_id]);
         	}
         	else{
         		$customer_id = $user->customer_id;
         	}

         	$cardinfo = $stripeAccount->customers->createSource($customer_id,['source' => $request->stripe_token]);
         	if (!empty($user) && ($user->default_card == null)) {

                $user->default_card = $cardinfo->id;
                $user->payment_verified = 1;
                $user->save();
            }
           	if (empty($cardinfo)) {
                return ResponseBuilder::error("Failed to add card!",$this->badRequest);
           	} 
           	else {
           		$userCardData = new UserCard;
           		$userCardData->user_id = $user_id;
           		$userCardData->user_customer_id = $cardinfo->customer;
           		$userCardData->card_token = $cardinfo->id;
           		$userCardData->last4 = $cardinfo->last4;
           		$userCardData->expiry_month = $cardinfo->exp_month;
           		$userCardData->expiry_year = $cardinfo->exp_year;
           		$userCardData->card_type = $cardinfo->funding;
           		$userCardData->save();


                return ResponseBuilder::success($cardinfo,"Card added successfully!");
           	}
           }
           	catch(\Stripe\Exception\InvalidRequestException $e)
			{
				return ResponseBuilder::error($e->getMessage(),$this->success);
			}
		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error($e->getMessage(),$this->serverError);
		}
	}

	public function allCardList()
	{
		try
		{
			if(!Auth::guard('api')->check()){
				return ResponseBuilder::error("User not found", $this->badRequest);
			}
			$singleuser = Auth::guard('api')->user();
            $user_id = $singleuser->id;

            $user = User::where('id', $user_id)->first();
          	if(!empty($user->customer_id))
          	{
          		$customer_id = $user->customer_id;
          	}
          	else
          	{
               return ResponseBuilder::error("Customer id is null",$this->badRequest);
          	}
          	
          	$stripeAccount = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $cards = [];
            if (isset($customer_id)) 
            {
                $cards = $stripeAccount->customers->allSources($customer_id,['object' => 'card', 'limit' => 10]);
            }

            if (!empty($cards->data)) {
            	$this->response = $cards->data;
                return ResponseBuilder::success($this->response,"Payment cards list");
           	} else {
                return ResponseBuilder::error("No Card found",$this->badRequest);
           	}

		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error("Oops! Something went wrong.", $this->serverError);
		}
	}

	public function deleteCard(Request $request)
	{
		try{
			try
			{
				if (Auth::guard('api')->check()) {
	            	$singleuser = Auth::guard('api')->user();
	            	$user_id = $singleuser->id;
	         	} 
	         	else{
	            	return ResponseBuilder::error(__("User not found"), $this->unauthorized);
	         	}
	         	$validator = Validator::make($request->all(), [
	            	'card_id'       => 'required',
	         	]);
	         	if ($validator->fails()) {
	            	return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	         	}
	         	$user = User::where('id', $user_id)->first();
	         	$customer_id = $user->customer_id;
	         	$stripeAccount = new \Stripe\StripeClient(env('STRIPE_SECRET'));

	         	$cardDlt = $stripeAccount->customers->deleteSource($customer_id,$request->card_id,[]);

	            if ($cardDlt->deleted) {
		         	$user = UserCard::where('card_token', $request->card_id)->first();
		         	$user->delete();
	                return ResponseBuilder::successMessage("Card deleted Successfully",$this->success); 
	           	} 
	           	else 
	           	{
	                return ResponseBuilder::error("Failed to delete card", $this->badRequest);
	           	}

			}
			catch(\Stripe\Exception\InvalidRequestException $e)
			{
				return ResponseBuilder::error($e->getMessage(),$this->serverError);
			}
		}
		catch(\Exception $e)
		{
			return ResponseBuilder::error($e->getMessage(),$this->serverError);
		}
	}
}