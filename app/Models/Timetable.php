<?php


namespace App\Models;

use Illuminate\Support\Facades\DB;

class Timetable extends Model
{
    /**
     * Table used by this model
     *
     * @var string
     */
    protected $table = 'timetables';

    /**
     * Non mass assignable fields
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Days used by this timetable
     *
     * @return App\Models\Day
     */
    public function days()
    {
        return $this->belongsToMany(Day::class, 'timetable_days', 'timetable_id', 'day_id');
    }

    /**
     * Schedules for professors created out of this timetable
     */
    public function schedules()
    {
        return $this->hasMany(ProfessorSchedule::class, 'timetable_id');
    }

    public function addSchedule($arrData)
    {
        $id = DB::table('timetables')->insertGetId($arrData);
        return $id;
    }

    public function loadSchedules()
    {
        $columns = [
            'a.id',
            'a.name',
            'a.days',
            'a.schedules',
            'a.user_id',
            'a.academic_period_id',
            'b.name as academic_period'
        ];
        $schedules = DB::table('timetables AS a')
                    ->join('academic_periods AS b', 'a.academic_period_id', '=', 'b.id')
                    ->select($columns)
                    ->get()
                    ->toArray();

        return $schedules;
    }

    public function selectSchedule($scheduleId)
    {
        $columns = [
            'a.id',
            'a.name',
            'a.days',
            'a.schedules',
            'a.user_id',
            'a.academic_period_id',
            'b.name as academic_period'
        ];
        $schedules = DB::table('timetables AS a')
                    ->join('academic_periods AS b', 'a.academic_period_id', '=', 'b.id')
                    ->select($columns)
                    ->where('a.id','=',$scheduleId)
                    ->get()
                    ->toArray();

        return $schedules;
    }

    public function deleteSchedule($scheduleId)
    {
        $result = DB::table('timetables')->where('id', $scheduleId)->delete();

        return $result;
    }
}
