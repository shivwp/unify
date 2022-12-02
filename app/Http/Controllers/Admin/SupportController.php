<?php

namespace App\Http\Controllers\Admin;

use App\Currency;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTransactionRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\IncomeSource;
use App\Models\Project;
use App\Models\Transaction;
use Intervention\Image\ImageManagerStatic as Image;

use App\Models\Support;
use Gate;
use DateTime;
use PDF;
use app\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('transaction_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dt = new DateTime();
        
        $q = Support::query()->join('users', 'support.user_id', '=', 'users.id')->select('support.*','users.name','users.email');

        $d['year']=$dt->format('Y');
        $d['month']=$dt->format('m');
        $d['day']=$dt->format('d');

        if (isset($request->day) || isset($request->month) || isset($request->year)) {

           if ($request->day=='all' && $request->month=='all') {
            $q->whereYear('created_at', '=', $request->year);
            $d['day']='all';
            $d['month']='all';
            $d['year']=$request->year;

           }elseif($request->day=='all'){

            $q->whereYear('created_at', '=', $request->year)->whereMonth('created_at', '=', $request->month);
            $d['day']='all';
            $d['month']=$request->month;
            $d['year']=$request->year;
           }else{
            $q->whereDate('created_at', '=', date(''.$request->year.'-'.$request->month.'-'.$request->day.''));
            $d['day']=$request->day;
            $d['month']=$request->month;
            $d['year']=$request->year;
           }


        }elseif(isset($request->start_date) && isset($request->end_date)){
            $q->whereBetween('created_at',[$request->start_date,$request->end_date]);
        }elseif(isset($request->start_date)){
                $q->where('created_at', 'like', '%' . $request->start_date . '%');
                 
        }
        if(!empty($request->keyword)) {
          $q->where('email', 'LIKE', '%'.$request->keyword.'%')->orwhere('name', 'LIKE', '%'.$request->keyword.'%')->get();
        }

       if(isset($request->project_status_filter)){
        // dd($request->project_status_filter);
            $q->where('support.status', '=', $request->project_status_filter);
        }
        if(!empty($request->pagination)){
            $n = $request->pagination;
        }
        else{
            $n = 10;
        }

        $d['pagination']= $n;

        $d['support']=$q->paginate($d['pagination']);
        return view('admin.support.index', $d);
    }

    public function create()
    {
        abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $user=User::all();
       

        return view('admin.support.create', compact('projects','user'));
    }

    public function store(Request $request)
    {  $random = $number = random_int(100,999999);
        $ticket='TCKT'.$random;
        
         if ($request->hasfile('image')) {
            $file1 = $request->file('image');
            $image_name = [];
            foreach ($file1 as $image) {
              
              $name = $image->getClientOriginalName();
              $filename = time() . '_' . $name;
              $image_resize = Image::make($image->getRealPath());
              $image_resize->save('support-image/' . $filename);
              $image_name[] =  $filename;
            }
          }
          $result = [];
          $varimg = json_decode($request->image1);
  
          if (!empty($image_name) && !empty($varimg)) {
  
            $result = array_merge($image_name, $varimg);
          } else if (!empty($image_name)) {
  
            $result = $image_name;
          } else {
            $result = $varimg;
          }

        $Support = new Support;
         // $Support->project_id=$request->project_id;
         $Support->user_id=$request->user_id;
         // $Support->source=$request->source;
         $Support->job_link = $request->jobLink;
         $Support->status=$request->status;
         // $Support->ticket ='#'.$ticket;
         $Support->description =$request->description ;
         $Support->image =json_encode($result);
         $Support->save();

        return redirect()->route('admin.support.index')->with('added', 'Added Successfully');
    }

    public function edit($id)
    {
       
        $projects = Project::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $user=User::get()->pluck('name', 'id');

        $support=Support::where('id',$id)->first();
        return view('admin.support.edit', compact('projects', 'user', 'support'));
    }

    public function update(Request $request)
    {
        $Support=Support::where('id',$request->support_id)->first();
        if(!empty($Support)){
                            
                    if ($request->hasfile('image')) {
                        $file1 = $request->file('image');
                        $image_name = [];
                        foreach ($file1 as $image) {
                        
                        $name = $image->getClientOriginalName();
                        $filename = time() . '_' . $name;
                        $image_resize = Image::make($image->getRealPath());
                        $image_resize->save('support-image/' . $filename);
                        $image_name[] =  $filename;
                        }
                    }
                    $result = [];
                    $varimg = json_decode($request->image1);

                    if (!empty($image_name) && !empty($varimg)) {

                        $result = array_merge($image_name, $varimg);
                    } else if (!empty($image_name)) {

                        $result = $image_name;
                    } else {
                        $result = $varimg;
                    }
                        
                    
                    $Support->user_id=$request->user_id;
                    $Support->job_link=$request->jobLink;
                    $Support->status=$request->status;
                    $Support->description =$request->description ;
                    $Support->image =json_encode($result);
                    $Support->save();
                
                
                }
        

        return redirect()->route('admin.support.index')->with('update','Updated Successfully');
    }

    public function show($id)
    {
         $support=Support::where('id',$id)->first();

       $user = User::find($support->user_id);

        return view('admin.support.show', compact('support','user'));
    }

    public function destroy($id)
    {
        
       $Support=Support::where('id',$id)->first();

        $Support->delete();

        return back()->with('delete', 'Deleted Successfully');
    }

    public function massDestroy(MassDestroyTransactionRequest $request)
    {
        Transaction::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function createPDF(){
        $Support=Support::all();
        $pdf = PDF::loadView('admin/pdf/support', compact('Support'));
       return $pdf->download('download.pdf');
    }
    public function ticket_close($id){
        $support=Support::where('id',$id)->first();
        $support->status='closed';
        $support->save();
        return back();
    }
}
