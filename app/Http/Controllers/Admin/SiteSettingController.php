<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;

class SiteSettingController extends Controller
{
    public function index(){
        $d['settings'] = SiteSetting::pluck('value','name');
      
        return view('admin/siteSetting/create',$d);
    }

    public function create(){

    }

    public function store(Request $request)
    {
      
        if($request->hasfile('business_logo1'))
        {
            $file = $request->file('business_logo1');
            $extention = $file->getClientOriginalExtension();
            $filename = time().'.'.$extention;
            $file->move('images/logo/', $filename);

            $setting_data = SiteSetting::updateOrCreate([
                'name' => 'business_logo1',
            ],
            [
                'value' => $filename,
            ]);
        }
    
        if ($request->hasfile('business_logo2')) 
        {
            $file2 = $request->file('business_logo2');
            $extention2 = $file2->getClientOriginalExtension();
            $filename2 = time().'.'.$extention2;
            $file2->move('images/logo/', $filename2);

            $setting_data = SiteSetting::updateOrCreate([
                'name' => 'business_logo2',
            ],
            [
                'value' => $filename2,
            ]);
        }
 
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
