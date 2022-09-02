<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SiteSetting;

class SiteSettingController extends Controller
{
    public function index(){
        $d['settings'] = SiteSetting::pluck('value','name');
        return view('admin/siteSetting/create',$d);
    }

    public function create(){

    }

    public function store(Request $request){
       
        $setting_data = '';
        foreach ($request->setting as $value) {
            $setting_data = SiteSetting::updateOrCreate([
                'name' => $value['name'],
            ],
            [
                'value' => isset($value['value']) ? $value['value'] : '' ,
            ]);
        }
        return redirect()->back()->with('message','Setting data saved Successfully');
    }
}
