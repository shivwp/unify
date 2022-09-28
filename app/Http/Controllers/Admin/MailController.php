<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mails;

class MailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $d['title'] = "Mails";
        $d['buton_name'] = "ADD NEW";
        $d['all_msg'] = Mails::all();
         $d['pagination']="10";
        if(isset($_GET['paginate'])){
            $pagination=$_GET['paginate'];
        }
         $q=Mails::select('*');
            if($request->search){
                $q->where('subject', 'like', "%$request->search%");  
            }
             $d['all_msg']=$q->get();
        
        return view('admin/mail/index',$d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $d['title'] = "Create Mail";
        $d['buton_name'] = "ADD NEW";
        return view('admin/mail/create',$d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
  
                 $category = Mails::updateOrCreate(['id' => $request->id],
            [
 
            'name'     => $request->input('title'),
            'subject'     => $request->input('subject'),
            'message'     => $request->input('message'),
            'from_email'     => $request->input('from_mail'),
            'reply_email'     => $request->input('reply_from_mail'),
            'user_category'     => $request->input('user_category'),
            'mail_category'     => $request->input('mail_category')

            
        ]);
       
        return redirect('/admin/mail')->with('status', 'your data is updated');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $d['title'] = "Edit Mail";
        $d['mail'] = Mails::findorfail($id);
        return view('admin/mail/create',$d);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $MAIL = Mails::findOrFail($id);
        $MAIL->delete();
        return back();
    }
}
