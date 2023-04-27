<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewerMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "email",
        "contect_no",
        "status",
    ];
}
