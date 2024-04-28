<?php

namespace App\Models;

use DB;
class Subject extends Model
{
    /**
     * The DB table used by this model
     *
     * @var string
     */
    protected $table = 'subjects';

    /**
     * The fields that should not be mass assigned
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * Relations of this model
     *
     * @var array
     */
    protected $relations = ['professors', 'classes'];

    /**
     * Fields that a keyword search should be carried on
     *
     * @var array
     */
    protected $searchFields = ['name', 'subject_code'];

    /**
     * Declare a relationship between this subject and the
     * professors that teach it
     *
     * @return Illuminate\Database\Eloquent
     */
    public function professors()
    {
        return $this->belongsToMany(Professor::class, 'subjects_professors', 'subject_id', 'professor_id');
    }

    /**
     * Declare a relationship between this subject and the classes
     * that offer it
     *
     * @return Illuminate\Database\Eloquent
     */
    public function classes()
    {
        return $this->belongsToMany(CollegeClass::class, 'subjects_classes', 'subject_id', 'class_id');
    }

    /**
     * Get subjects with no professors set up for them
     *
     */
    public function scopeHavingNoProfessors($query)
    {
        return $query->has('professors', '<', 1);
    }


    public function loadSubjects($academicPeriodId)
    {
        $columns = [
            'a.id',
            'a.subject_id',
            'a.class_id',
            'a.units',
            'b.subject_code',
            'b.name as subject_name',
            'b.lab',
            'c.name',
            'e.id as prof'
        ];
        $subjects = DB::table('subjects_classes AS a')
                    ->join('subjects AS b', 'a.subject_id', '=', 'b.id')
                    ->join('classes AS c', 'a.class_id', '=', 'c.id')
                    ->join('subjects_professors AS d', 'a.subject_id', '=', 'd.subject_id')
                    ->join('professors AS e', 'd.professor_id', '=', 'e.id')
                    ->select($columns)
                    ->where('a.academic_period_id','=',$academicPeriodId)
                    ->get()
                    ->toArray();

        return $subjects;
    }
}
