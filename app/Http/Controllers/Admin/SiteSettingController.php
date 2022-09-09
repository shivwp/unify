<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SiteSetting;
use Intervention\Image\ImageManagerStatic as Image;

class SiteSettingController extends Controller
{
    public function index(){
        $d['settings'] = SiteSetting::pluck('value','name');
      
        return view('admin/siteSetting/create',$d);
    }

    public function create(){

    }

    public function store(Request $request){
      
       if ($request->hasfile('business_logo1')) {
        $file1 = $request->file('business_logo1');
        $name = $file1->getClientOriginalName();
          $filename = time() . '_' . $name;
          $image_resize = Image::make($file1->getRealPath());
        
          $image_resize->save('images/logo/' . $filename);
          
          $setting_data = SiteSetting::updateOrCreate([
            'name' => 'business_logo1',
        ],
        [
            'value' => $filename,
        ]);
      }
      if ($request->hasfile('business_logo2')) {
        $file = $request->file('business_logo2');
        $name2 = $file->getClientOriginalName();
          $filename1 = time() . '_' . $name2;
          $image_resize = Image::make($file->getRealPath());
          $image_resize->save('images/logo/' . $filename1);
          
          $setting_data = SiteSetting::updateOrCreate([
            'name' => 'business_logo2',
        ],
        [
            'value' => $filename1,
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
