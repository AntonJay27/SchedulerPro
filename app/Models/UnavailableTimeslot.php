<?php

namespace App\Models;

class UnavailableTimeslot extends Model
{
    protected $table = 'unavailable_timeslots';

    protected $guarded = ['id'];

    /**
     * Get the day this unavailable timeslot exists in
     *
     * @return App\Models\Day Day
     */
    public function day()
    {
        return $this->belongsTo(Day::class, 'day_id');
    }

    /**
     * Get the timeslot this unavailable timeslot exists in
     *
     * @return App\Models\Timeslot Timeslot
     */
    public function timeslot()
    {
        return $this->belongsTo(Timeslot::class, 'timeslot_id');
    }
}