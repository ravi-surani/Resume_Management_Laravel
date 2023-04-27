<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        "candidate_master_id",
        "coumpany_name",
        "from",
        "to",
        "status",
    ];
    public  function CandidateExperience()
    {
        return $this->belongsTo(CandidateExperience::class, 'candidate_master_id', 'id');
    }
}
