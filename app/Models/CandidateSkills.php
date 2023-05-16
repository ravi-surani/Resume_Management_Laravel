<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateSkills extends Model
{
    use HasFactory;
    protected $fillable = [
        'candidate_master_id',
        'skill_master_id',
        'experience',
        'self_rating',
        'theory_rating',
        'practical_rating',

    ];
    public function skillMaster() 
    {
        return $this->belongsTo(SkillMaster::class, 'skill_master_id', 'id');
}

}
