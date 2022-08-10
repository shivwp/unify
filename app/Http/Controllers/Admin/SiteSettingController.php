<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SiteSetting;

class SiteSettingController extends Controller
{
    public function index(){
        $d['setting_data'] = SiteSetting::pluck('name','value');
        return view('admin/siteSetting/create');
    }

    public function create(){

    }

    public function store(Request $request){
        
    }
}
