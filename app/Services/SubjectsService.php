<?php

namespace App\Services;

use DB;
use App\Models\Subject;

class SubjectsService extends AbstractService
{
    /*
     * The model to be used by this service.
     *
     * @var \App\Models\Subject
     */
    protected $model = Subject::class;

    /**
     * Show resources with their relations.
     *
     * @var bool
     */
    protected $showWithRelations = true;

    protected $customFilters = [
        'no_professor' => 'getSubjectsWithNoProfessors'
    ];

    /**
     * Save a new subject in the db
     *
     * @param array $data Data for creating a new subject
     */
    public function store($data = [])
    {
        $subject = Subject::create([
            'name' => $data['name'],
            'subject_code' => $data['subject_code'],
            'lab' => $data['lab']
        ]);

        if (!$subject) {
            return null;
        }

        if (!isset($data['professor_ids'])) {
            $data['professor_ids'] = [];
        }

        $subject->professors()->sync($data['professor_ids']);

        return $subject;
    }

    /**
     * Get the subject with the given id loaded with necessary data
     *
     * @param int $id Id of professor
     * @return App\Models\Subject Newly created subject
     */
    public function show($id)
    {
        $subject = Subject::find($id);
        $professorIds = [];

        if (!$subject) {
            return null;
        }

        foreach ($subject->professors as $professor) {
            $professorIds[] = $professor->id;
        }

        $subject->professor_ids = $professorIds;

        return $subject;
    }

    /**
     * Update the subject with the given data
     *
     * @param int $id Id of subject
     * @param array $data Data for updating subject
     * @return App\Models\Subject The updated subject
     */
    public function update($id, $data = [])
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return null;
        }

        $subject->update([
            'name' => $data['name'],
            'subject_code' => $data['subject_code'],
            'lab' => $data['lab']
        ]);

        if (!isset($data['professor_ids'])) {
            $data['professor_ids'] = [];
        }


        $subject->professors()->sync($data['professor_ids']);

        return $subject;
    }

    /**
     * Return query with filter applied to select subjects with no professor added for them
     */
    public function getSubjectsWithNoProfessors($query)
    {
        return $query->havingNoProfessors();
    }
}