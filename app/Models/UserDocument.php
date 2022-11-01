<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{

    public $table = 'user_documents';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'user_id',
        'type',
        'document_front',
        'document_back',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'user_id', 'id');
    }

}
