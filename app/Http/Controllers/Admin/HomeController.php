<?php

namespace App\Http\Controllers\Admin;
use Auth;
use App\Notification;
use Illuminate\Http\Request;
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
    public function notification(Request $request){

       
        $Notification=Notification::paginate(10);
        if($request->search){
          
            $Notification = Notification::where('name', 'like', "%$request->search%")->Orwhere('email', 'like', "%$request->search%")->paginate(10);
        }
        $Noti=Notification::where('status','0')->get();
        foreach($Noti as $item){
            $item->status='1';
            $item->save();
        }
        return view('admin.notification',compact('Notification'));
        
    }
}




