<?php

namespace App\Services;

use App\Models\Timetable;
use App\Models\CollegeClass;
use Illuminate\Support\Facades\DB;

class TimetableService extends AbstractService
{
     /*
     * The model to be used by this service.
     *
     * @var \App\Models\Room
     */
    protected $model = Timetable::class;

    /**
     * Show resources with their relations.
     *
     * @var bool
     */
    protected $showWithRelations = true;
    
    /**
     * Check that everything is intact to create a timetable set
     * Return errors from check
     *
     * @return array Errors from check
     */
    public function checkCreationConditions()
    {
        $errors = [];

        $baseUrl = config('app.url');

        $subjectsQuery = 'SELECT id FROM subjects WHERE id NOT IN (SELECT DISTINCT subject_id FROM subjects_professors)';
        $subjectIds = DB::select($subjectsQuery);

        if (count($subjectIds)) {
            $errors[] = <<<EOD
            Some subjects don't have professors.<a href="{$baseUrl}subjects?filter=no_professor" target="_blank">Click here to review them</a>
            EOD;
        }

        if (!CollegeClass::count()) {
            $errors[] = "No classes have been added";
        }

        $classesQuery = 'SELECT id FROM classes WHERE id NOT IN (SELECT DISTINCT class_id FROM subjects_classes)';
        $classIds = DB::select($classesQuery);

        if (count($classIds)) {
            $errors[] = <<<EOD
            Some classes don't have any subject set up.<a href="{$baseUrl}classes?filter=no_subject" target="_blank">Click here to review them</a>
            EOD;
        }

        return $errors;
    }
}