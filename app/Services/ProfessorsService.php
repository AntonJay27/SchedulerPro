<?php

namespace App\Services;

use App\Models\Professor;

class ProfessorsService extends AbstractService
{
    /*
     * The model to be used by this service.
     *
     * @var \App\Models\Professor
     */
    protected $model = Professor::class;

    /**
     * Show resources with their relations.
     *
     * @var bool
     */
    protected $showWithRelations = true;

    /**
     * Store a new professor in the DB
     *
     * @param array $data Data for creating professor
     */
    public function store($data = [])
    {
        $professor = Professor::create([
            'name' => $data['name'],
        ]);

        if (!$professor) {
            return null;
        }

        if (isset($data['subject_ids'])) {
            $professor->subjects()->sync($data['subject_ids']);
        }

        if (isset($data['unavailable_timeslots'])) {
            foreach ($data['unavailable_timeslots'] as $timeslot) {
                $parts = explode("," , $timeslot);
                $dayId = $parts[0];
                $timeslotId = $parts[1];

                $professor->unavailable_timeslots()->create([
                    'day_id' => $dayId,
                    'timeslot_id' => $timeslotId
                ]);
            }
        }

        return $professor;
    }

    /**
     * Get the professor with the given id
     *
     * @param int $id The Id of the professor
     */
    public function show($id)
    {
        $professor = Professor::find($id);
        $subjectIds = [];
        $timeslots = [];

        if (!$professor) {
            return null;
        }

        foreach ($professor->subjects as $subject) {
            $subjectIds[] = $subject->id;
        }

        foreach ($professor->unavailable_timeslots as $timeslot) {
            $timeslots[] = implode(",", [$timeslot->day_id, $timeslot->timeslot_id]);
        }

        $professor->subject_ids = $subjectIds;
        $professor->timeslots = $timeslots;

        return $professor;
    }

    /**
     * Update the professor with the given id
     * with new data
     *
     * @param int $id The id of the professor
     * @param array $data Data for update
     */
    public function update($id, $data = [])
    {
        $professor = Professor::find($id);

        if (!$professor) {
            return null;
        }

        $professor->update([
            'name' => $data['name'],
        ]);

        if (!isset($data['subject_ids'])) {
            $data['subject_ids'] = [];
        }

        $professor->subjects()->sync($data['subject_ids']);

        if (isset($data['unavailable_timeslots'])) {
            foreach ($data['unavailable_timeslots'] as $timeslot) {
                $parts = explode("," , $timeslot);
                $dayId = $parts[0];
                $timeslotId = $parts[1];

                $existing = $professor->unavailable_timeslots()
                    ->where('day_id', $dayId)
                    ->where('timeslot_id', $timeslotId)
                    ->first();

                if (!$existing) {
                    $professor->unavailable_timeslots()->create([
                        'day_id' => $dayId,
                        'timeslot_id' => $timeslotId
                    ]);
                }
            }

            foreach ($professor->unavailable_timeslots as $timeslot) {
                if ($timeslot->day && $timeslot->timeslot) {
                    $timeslotString = implode("," , [$timeslot->day->id, $timeslot->timeslot->id]);
                }

                if (!isset($data['unavailable_timeslots']) || !in_array($timeslotString, $data['unavailable_timeslots'])) {
                    $timeslot->delete();
                }
            }
        } else {
            foreach ($professor->unavailable_timeslots as $timeslot) {
                $timeslot->delete();
            }
        }

        return $professor;
    }
}