<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Timeslot extends Model
{
    protected $table = 'timeslots';

    protected $guarded = ['id'];

    /**
     * Determine whether a given period  is within
     * the period of this timeslot
     *
     * @param string $timePeriod The time period
     * @return Boolean Value of test
     */
    public function containsTimeslot($timeTimeslot)
    {
        $edgesA = self::getParts($this->time);
        $edgesB = self::getParts($timeTimeslot);

        return (($edgesB[0] >= $edgesA[0]) && $edgesB[2] <= $edgesA[2]);
    }

    /**
     * Get the beginning and end of a given time period
     *
     * @param string $timePeriod Time period
     * @return array Parts of given time period
     */
    public static function getParts($timeTimeslot)
    {
        preg_match('/(0?\d{1,2}):(\d{2})\s*\-\s*(\d{2}):(\d{2})/', $timeTimeslot, $matches);

        return array_slice($matches, 1);
    }

    /**
     * Generate a time period string
     *
     * @param string $from From section of period
     * @param string $to   To section of period
     */
    public static function createTimeTimeslot($from, $to)
    {
        return $from . ' - ' . $to;
    }

    public function loadTimeslots()
    {
        $columns = [
            'a.id',
            'a.time'
        ];
        $timeslots = DB::table('timeslots AS a')->select($columns)->get()->toArray();

        return $timeslots;
    }

    public function loadUnavailableSlot()
    {
        $columns = [
            'a.id',
            'a.professor_id',
            'a.timeslot_id',
            'a.day_id'
        ];
        $timeslots = DB::table('unavailable_timeslots AS a')
        ->select($columns)
        ->get()->toArray();

        return $timeslots;
    }
}
