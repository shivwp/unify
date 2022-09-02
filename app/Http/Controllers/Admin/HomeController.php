<?php

namespace App\Http\Controllers\Admin;
use Auth;
class HomeController
{
    public function index()
    {
        return view('admin.index');
    }
    public function dashboard()
    {
        return view('admin.index');
    }
    public function logout()
    {   Auth::logout();

        return redirect('/');
    }
}




