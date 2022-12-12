<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    public $table = 'transactions';

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'transaction_date',
    ];

    protected $fillable = [
        'user_id',
        'transaction_id',
        'amount',
        'transaction_date',
        'name',
        'description',
        'transaction_type_id',
        'capture_method',
        'client_secret',
        'confirmation_method',
        'currency',
        'customer_id',
        'source',
        'status',
        'complete_response',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function transaction_type()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
    }

    // public function income_source()
    // {
    //     return $this->belongsTo(IncomeSource::class, 'income_source_id');
    // }

    // public function currency()
    // {
    //     return $this->belongsTo(Currency::class, 'currency_id');
    // }

    public function getTransactionDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setTransactionDateAttribute($value)
    {
        $this->attributes['transaction_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
}
