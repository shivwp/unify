<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    use HasFactory;
	protected $table	= 'subscription_payment';

	protected $fillable =[
		'user_id',
		'subscription_id',
		'transaction_id',
		'amount',
		'status',
	];
	public $timestamps=true;

   
}