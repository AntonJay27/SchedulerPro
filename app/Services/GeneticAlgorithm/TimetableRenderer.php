<?php

namespace App\Services\GeneticAlgorithm;

use Storage;

use App\Models\Day as DayModel;
use App\Models\Room as RoomModel;
use App\Models\Subject as SubjectModel;
use App\Models\Timeslot as TimeslotModel;
use App\Models\CollegeClass as CollegeClassModel;
use App\Models\Professor as ProfessorModel;

class TimetableRenderer
{
    /**
     * Create a new instance of this class
     *
     * @param App\Models\Timetable Timetable whose data we are rendering
     */
    public function __construct($timetable)
    {
        $this->timetable = $timetable;
    }

    /**
     * Generate HTML layout files out of the timetable data
     *
     * Chromosome interpretation is as follows
     * Timeslot, Room, Professor
     *
     */
    public function render()
    {
        $chromosome = explode(",", $this->timetable->chromosome);
        $scheme = explode(",", $this->timetable->scheme);
        $data = $this->generateData($chromosome, $scheme);
        $days = $this->timetable->days()->orderBy('id', 'ASC')->get();
        $timeslots = TimeslotModel::orderBy('id', 'ASC')->get();
        $classes = CollegeClassModel::all();

        $tableTemplate = '<h3 class="text-center">{TITLE}</h3>
                         <div style="page-break-after: always">
                            <table class="table table-bordered">
                                <thead>
                                    {HEADING}
                                </thead>
                                <tbody>
                                    {BODY}
                                </tbody>
                            </table>
                        </div>';
        $content = "";

        foreach ($classes as $class) {
            $header = "<tr class='table-head'>";
            $header .= "<td>Time</td>";

            foreach ($days as $day) {
                $header .= "<td>" . strtoupper($day->short_name) . "</td>";
            }
            $header .= "</tr>";

            $body = "";

            foreach ($timeslots as $timeslot) {
                $body .= "<tr>\t<td>" . $timeslot->time . "</td>";
                foreach ($days as $day) {
                    if (isset($data[$class->id][$day->name][$timeslot->time])) {
                        $body .= "<td class='text-center'>";
                        $slotData = $data[$class->id][$day->name][$timeslot->time];
                        $subjectName = $slotData['subject_name'];
                        $subjectCode = $slotData['subject_code'];
                        $professor = $slotData['professor'];
                        $room = $slotData['room'];
                        
                        $body .= "<span class='subject_name'>{$subjectName}</span><br />";
                        $body .= "<span class='subject_code'>({$subjectCode})</span><br />";
                        $body .= "<span class='room pull-left'>{$room}</span>";
                        $body .= "<span class='professor pull-right'>{$professor}</span>";
                        $body .= "</td>";

                    } else {
                        $body .= "<td></td>";
                    }
                }
                $body .= "</tr>";
            }

            $title = $class->name;
            $content .= str_replace(['{TITLE}', '{HEADING}', '{BODY}'], [$title, $header, $body], $tableTemplate);
        }

        $path = 'public/timetables/timetable_' . $this->timetable->id . '.html';
        Storage::put($path, $content);

        $this->timetable->update([
            'file_url' => $path
        ]);
    }

    /**
     * Get an associative array with data for constructing timetable
     *
     * @param array $chromosome Timetable chromosome
     * @param array $scheme Mapping for reading chromosome
     * @return array Timetable data
     */
    public function generateData($chromosome, $scheme)
    {
        $data = [];
        $schemeIndex = 0;
        $chromosomeIndex = 0;
        $groupId = null;

        while ($chromosomeIndex < count($chromosome)) {
            while ($scheme[$schemeIndex][0] == 'G') {
                $groupId = substr($scheme[$schemeIndex], 1);
                $schemeIndex += 1;
            }

            $subjectId = $scheme[$schemeIndex];

            $class = CollegeClassModel::find($groupId);
            $subject = SubjectModel::find($subjectId);

            $timeslotGene = $chromosome[$chromosomeIndex];
            $roomGene = $chromosome[$chromosomeIndex + 1];
            $professorGene = $chromosome[$chromosomeIndex + 2];

            $matches = [];
            preg_match('/D(\d*)T(\d*)/', $timeslotGene, $matches);

            $dayId = $matches[1];
            $timeslotId = $matches[2];

            $day = DayModel::find($dayId);
            $timeslot = TimeslotModel::find($timeslotId);
            $professor = ProfessorModel::find($professorGene);
            $room = RoomModel::find($roomGene);

            if (!isset($data[$groupId])) {
                $data[$groupId] = [];
            }

            if (!isset($data[$groupId][$day->name])) {
                $data[$groupId][$day->name] = [];
            }

            if (!isset($data[$groupId][$day->name][$timeslot->time])) {
                $data[$groupId][$day->name][$timeslot->time] = [];
            }

            $data[$groupId][$day->name][$timeslot->time]['subject_name'] = $subject->name;
            $data[$groupId][$day->name][$timeslot->time]['subject_code'] = $subject->subject_code;
            $data[$groupId][$day->name][$timeslot->time]['room'] = $room->name;
            $data[$groupId][$day->name][$timeslot->time]['professor'] = $professor->name;

            $schemeIndex++;
            $chromosomeIndex += 3;
        }

        return $data;
    }
}