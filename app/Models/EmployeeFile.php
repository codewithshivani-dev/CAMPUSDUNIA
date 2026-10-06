<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class EmployeeFile extends Model
{
     use HasFactory;

    protected $table = 'employee_files';
   
    protected $guarded = [];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}