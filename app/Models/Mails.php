<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mails extends Model
{
    use HasFactory;
    protected $table = "mail_templete";

    protected $fillable = [
        'name',
        'msg_category',
        'message',
        'from_email',
        'reply_email',
        'mail_to',
        'subject',
        'user_category',
        'mail_category'
    ];

}
