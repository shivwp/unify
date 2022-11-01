<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSpecialize extends Model
{

    public $table = 'user_specialize';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'pay_rate'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'user_id', 'id');
    }

}
