<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewModeMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'interview_mode',
        "status",
    ];
}
