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
use App\Models\TransactionType;
use Gate;
use DateTime;
use PDF;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
       
        abort_if(Gate::denies('transaction_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dt = new DateTime();
        
        $q = Transaction::query();

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
           }
           else{
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

        
         $d['transactions']=$q->paginate(10);
        return view('admin.transactions.index', $d);
    }

    public function create()
    {
        abort_if(Gate::denies('transaction_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $transaction_types = TransactionType::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $income_sources = IncomeSource::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $currencies = Currency::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.transactions.create', compact('projects', 'transaction_types', 'income_sources', 'currencies'));
    }

    public function store(Request $request)
    { 
       
        $transaction = new Transaction;
         $transaction->amount=$request->amount;
         $transaction->transaction_date=$request->transaction_date;
         $transaction->name=$request->name;
         $transaction->description=$request->description;
         $transaction->project_id =$request->project_id ;
         $transaction->save();
        return redirect()->route('admin.transactions.index');
    }

    public function edit(Transaction $transaction)
    {
        abort_if(Gate::denies('transaction_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $projects = Project::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $transaction_types = TransactionType::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

       

      

        $transaction->load('project', 'transaction_type');

        return view('admin.transactions.edit', compact('projects', 'transaction_types', 'transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $transaction->update($request->all());

        return redirect()->route('admin.transactions.index');
    }

    public function show(Transaction $transaction)
    {
        abort_if(Gate::denies('transaction_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $transaction->load('project',);

        return view('admin.transactions.show', compact('transaction'));
    }

    public function destroy(Transaction $transaction)
    {
        abort_if(Gate::denies('transaction_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $transaction->delete();

        return back();
    }

    public function massDestroy(MassDestroyTransactionRequest $request)
    {
        Transaction::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
    public function createPDF(){
        $transaction=Transaction::all();
        $pdf = PDF::loadView('admin/pdf/transaction', compact('transaction'));
       return $pdf->download('download.pdf');
    }
}
