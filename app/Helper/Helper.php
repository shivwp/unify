<?php

namespace App\Helper;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Helper
{

    public static function generateOtp(): int
    {
        $otp = rand(100000, 999999);
        return $otp;
    }
    
    
}
