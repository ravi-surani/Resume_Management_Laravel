<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interviews extends Model
{
    use HasFactory;
    protected $fillable = [
        "candidate_master_id",
        "interview_type_id",
        "interviewer_id",
        "interview_mode_id",
        "date",
        "remarks",
        "location_link",
        "status",
        'total_rating'
    ];

    public  function Candidate()
    {
        return $this->belongsTo(CandidateMaster::class, 'candidate_master_id', 'id');
    }

    public  function Interview_type()
    {
        return $this->belongsTo(InterviewTypeMaster::class, 'interview_type_id', 'id');
    }

    public  function Interviewer_id()
    {
        return $this->belongsTo(InterviewerMaster::class, 'interviewer_id', 'id');
    }

    public  function Interview_mode()
    {
        return $this->belongsTo(InterviewModeMaster::class, 'interview_mode_id', 'id');
    }
}
