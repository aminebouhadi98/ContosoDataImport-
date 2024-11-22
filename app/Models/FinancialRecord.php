<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialRecord extends Model
{

    protected $table = 'financial_records';


    protected $fillable = ['branch_id', 'date', 'balance'];


    public $timestamps = true;
}
