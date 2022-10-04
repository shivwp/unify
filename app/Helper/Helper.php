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
    public static function validateGoogle($token)
    {
        try {
            $user = [];

            $payload = json_decode($token, true);
            if ($payload && $payload->sub) {
                $user['name'] = $payload->name ?? 'Social User';
                $user['email'] = $payload->email ?? null;
                $user['id'] = $payload->sub;
            }

            return $user;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }

    public static function validateApple($token)
    {
        try {
            $user = [];

            $payload = json_decode($token, true);
            if ($payload && $payload->sub) {
                $user['name'] = 'Social User';
                $user['id'] = $payload->sub;
                $user['email'] = $payload->email ?? null;
            }

            return $user;
        } catch (\Exception $e) {
            Log::error($e);
            return false;
        }
    }
    
    
}
