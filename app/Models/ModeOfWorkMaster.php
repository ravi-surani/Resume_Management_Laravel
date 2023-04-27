<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeOfWorkMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'mode_of_work',
        "status",
    ];
}
