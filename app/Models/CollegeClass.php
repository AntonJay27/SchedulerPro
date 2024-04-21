<?php

namespace App\Models;

class CollegeClass extends Model
{
    /**
     * The DB table used by this model
     *
     * @var string
     */
    protected $table = 'classes';

    protected $guarded = ['id'];

    protected $relations = ['subjects'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subjects_classes', 'class_id', 'subject_id')
            ->withPivot(['units','academic_period_id']);
    }

    /**
     * Get classes with no subjects set up for them
     */
    public function scopeHavingNoSubjects($query)
    {
        return $query->has('subjects', '<', 1);
    }
}