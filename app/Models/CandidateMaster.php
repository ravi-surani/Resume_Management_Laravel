<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Event\TestSuite\Skipped;

class CandidateMaster extends Model
{
    use HasFactory;


    protected $fillable = [
        "name",
        "email",
        "contect_no",
        "dob",
        "mode_of_work_id",
        "degree_id",
        "passing_year",
        "passing_grade",
        "total_experience",
        "current_salary",
        "expected_salary",
        "is_negotiable",
        "notice_period",
        "address",
        "city",
        "state",
        "countary",
        "resume_id",
        "remarks",
        "recruitment_status_id",
        "source_id",
        "status",
    ];

    public function Skills()
    {
        return $this->belongsToMany(SkillMaster::class, 'candidate_skills');
    }

    public function candidateSkills() 
    {
        return $this->hasMany(CandidateSkills::class, 'candidate_master_id', 'id');
    }

    public  function Source()
    {
        return $this->belongsTo(SourceMaster::class, 'source_id', 'id');
    }
    public  function Recruitment_Status()
    {
        return $this->belongsTo(RecruitmentStatusMaster::class, 'recruitment_status_id', 'id');
    }

    public  function Degree()
    {
        return $this->belongsTo(DegreeMaster::class, 'degree_id', 'id');
    }

    public  function ModeOfWork()
    {
        return $this->belongsTo(ModeOfWorkMaster::class, 'mode_of_work_id', 'id');
    }

    public  function CandidateExperience()
    {
        return $this->hasMany(CandidateExperience::class, 'candidate_master_id', 'id');
    }

    public  function Interviews()
    {
        return $this->hasMany(Interviews::class, 'candidate_master_id', 'id');
    }
}
