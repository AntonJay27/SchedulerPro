<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;

class Professor extends Model
{
    use Notifiable;

    /**
     * DB table this model uses
     *
     * @var string
     */
    protected $table = 'professors';

    /**
     * Non-mass assignable fields
     */
    protected $guarded = ['id'];

    /**
     * Declare relationship between a professor and the subjects
     * he or she teaches
     *
     * @return Illuminate\Database\Eloquent
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subjects_professors', 'professor_id', 'subject_id');
    }

    /**
     * Declare relationship between a professor and the timeslots that he or she
     * is not available
     *
     * @return Illuminate\Database\Eloquent
     */
    public function unavailable_timeslots()
    {
        return $this->hasMany(UnavailableTimeslot::class, 'professor_id');
    }
}