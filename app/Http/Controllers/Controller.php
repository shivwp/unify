<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\FreelancerMeta;
use App\Models\Freelancer;
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

    public function freelancerPortfolioImage($image)
    {
        $file = $image;
        $name =$file->getClientOriginalName();
        $destinationPath = 'images/freelancer-portfolio';
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
        $freelancerData = User::with('freelancer.freelancer_portfolio','freelancer.freelancer_testimonial','freelancer.freelancer_certificates','freelancer.freelancer_experiences','freelancer.freelancer_skills')->where('id',$freelancer_id)->first();
        return $freelancerData;
    }

    public function getClientInfo($clientId)
    {
        $clientData = User::with('client')->where('id',$clientId)->first();
        return $clientData;
    }


}
