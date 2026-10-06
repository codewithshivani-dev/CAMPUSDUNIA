<?php
// app/Models/AdmissionRegistration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionRegistration extends Model
{
    use HasFactory;
    protected $table = "admission_registrations"; 
    protected $guarded = [];
} 