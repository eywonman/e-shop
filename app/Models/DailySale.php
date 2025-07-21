<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailySale extends Model
{
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'sale_date'; // primary key is the date
    protected $keyType = 'string';
    
    protected $fillable = ['sale_date', 'total_sales'];
}
