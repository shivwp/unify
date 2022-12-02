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

		    	return ResponseBuilder::successMessage('Subscription Upgrade successfully',$this->success);
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
			return ResponseBuilder::error($e->getMessage(),$this->serverError);
		}
	}
	public function subscriptionPayment2(Request $request)
	{
		// try
		// {
			if(Auth::guard('api')->check())
			{
				$singleuser = Auth::guard('api')->user();
				$user_id = $singleuser->id;
				$user_email = $singleuser->email;
			}else
			{
				return ResponseBuilder::error('User not found',$this->unauthorized);
			}
			$user = User::where('email',$user_email)->first();
			
			$stripeAccount = new \Stripe\StripeClient(env('STRIPE_SECRET'));
			\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
			// dd($stripeAccount);
			if(empty($user->customer_id))
			{
		        $customer = $stripeAccount->customers->create([
		            'description' => 'customer_' . $user_id,
		            'email' => $user_email,
		            'name' => $singleuser->first_name . ' ' . $singleuser->last_name
		        ]);
		        $customer_id = $customer->id;
		        User::where('id', $user->id)->update([
		          'customer_id' => $customer_id,
		        ]);
			}
			else {
		        $customer_id = $user->customer_id;
		    }
		    $validator = Validator::make($request->all(),[
				'subscription_id' 	=> 'required|exists:plans,id',
				'stripe_token'		=> 'required',
			]);
		    if ($request->new) {
		        // 
		        $card_token = '';
		        // try 
		        // {
		          	$cardinfo = $stripeAccount->customers->createSource($customer_id,['source' => $request->stripe_token]);  //-- done
		          	$card_token = $cardinfo->id;
		        // } 
		        // catch (\Stripe\Exception\InvalidRequestException $e) 
		        // {
		        //   	return ResponseBuilder::error($e->getMessage(), $this->badRequest);
		        // }

		        $new_card = UserCard::insert([
		          	'user_id' => $user->id,
		          	'user_customer_id' => $customer_id,
		          	'card_token' => $card_token,
		        ]);
		    } 
		    else 
		    {
		        $card_token = $request->stripe_token;
		    }
		    
			if ($validator->fails()) {
	            return ResponseBuilder::error($validator->errors()->first(), $this->badRequest);
	        }
	        $subscriptionData = Plans::where('id',$request->subscription_id)->select('amount')->first();

	        print_r($subscriptionData);
			// try 
			// {
	        	$method = $stripeAccount->tokens->create([
		            'card' => [
		                'number' => '4242424242424242',
		                'exp_month' => 2,
		                'exp_year' => 2023,
		                'cvc' => '123',
		            ],
		        ]);

		        $paymentIntent = \Stripe\PaymentIntent::create([
		          	'customer' => $customer_id,
		          	'amount' => $subscriptionData->amount * 100, //$booking->price * 100,
		          	'currency' => 'usd',
		          	'payment_method_types' => ['card'],
		          	// 'payment_method' => $card_token, // 'card_1Jht6ZEUI2VlKHRnc5KrHBMF',
		          	'transfer_group' => 1,
		          	'confirm' => 'true',
		        ]);
	        	print_r($paymentIntent);die;

		    //} 
		    // catch (\Stripe\Exception\InvalidRequestException $e) 
		    // {
		    //     // Invalid parameters were supplied to Stripe's API
		    //     return ResponseBuilder::error($e->getMessage(), $this->badRequest);
		    // }
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

		    	return ResponseBuilder::successMessage('Subscription Upgrade successfully',$this->success);
		    }
		// }
		// catch(\Exception $e)
		// {
		// 	return ResponseBuilder::error($e->getMessage(),$this->serverError);
		// }
	}
}