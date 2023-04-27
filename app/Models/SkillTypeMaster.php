<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SkillTypeMaster extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'skill_type',
        "status",
    ];
}
