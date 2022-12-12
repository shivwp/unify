<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    use HasFactory;
	protected $table	= 'user_cards';

	protected $fillable =[
		'user_id',
		'user_customer_id',
		'card_token',
		'last4',
		'expiry_month',
		'expiry_year',
		'card_type',
		'default_card',
		'card_id'
	];
	public $timestamps=true;

   
}