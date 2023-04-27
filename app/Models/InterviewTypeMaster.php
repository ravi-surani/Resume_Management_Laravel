<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewTypeMaster extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "interview_type",
        "status",
    ];
}
