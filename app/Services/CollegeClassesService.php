<?php

namespace App\Services;

use DB;
use App\Models\CollegeClass;

class CollegeClassesService extends AbstractService
{
    /*
     * The model to be used by this service.
     *
     * @var \App\Models\CollegeClass
     */
    protected $model = CollegeClass::class;

    /**
     * Show resources with their relations.
     *
     * @var bool
     */
    protected $showWithRelations = true;

    protected $customFilters = [
        'no_subject' => 'getClassesWithNoSubject'
    ];

    /**
     * Get a listing of college classes with necessary filtering
     * applied
     *
     */
    public function all($data = [])
    {
        $classes = parent::all($data);

        return $classes;
    }

    /**
     * Add a new college class
     *
     * @param array $data Data for creating a new college class
     * @return App\Models\CollegeClass Newly created class
     */
    public function store($data = [])
    {
        $class = CollegeClass::create([
            'name' => $data['name'],
        ]);

        if (!$class) {
            return null;
        }

        $class->subjects()->sync($data['subjects']);

        return $class;
    }

    /**
     * Update the class with the given id
     *
     * @param int $id The ID of the class
     * @param array $data Data
     */
    public function update($id, $data = [])
    {
        $class = CollegeClass::find($id);

        if (!$class) {
            return null;
        }

        $class->update([
            'name' => $data['name'],
        ]);

        if (!isset($data['subjects'])) {
            $data['subjects'] = [];
        }
        
        $class->subjects()->sync($data['subjects']);

        return $class;
    }

    /**
     * Return query with filter applied to select classes with no subject added for them
     */
    public function getClassesWithNoSubject($query)
    {
        return $query->havingNoSubjects();
    }
}