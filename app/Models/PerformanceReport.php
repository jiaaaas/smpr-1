<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceReport extends Model
{
    protected $fillable = [
        'report_type',
        'year',
        'month',
        'employee_ids',
    ];
}