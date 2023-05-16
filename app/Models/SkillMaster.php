<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SkillMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        "skill",
        "skill_type_id",
        "status",
    ];
    public function skillType()
    {
        return $this->belongsTo(SkillTypeMaster::class, 'skill_type_id', 'id');
    }

    public function Candidates()
    {
        return $this->belongsToMany(CandidateMaster::class, 'candidate_skills');
    }
    // public function Candidates()
    // {
    //     return $this->belongsToMany(CandidateMaster::class, 'candidate_skills', 'skill_master_id', 'candidate_master_id');
    // }
}
