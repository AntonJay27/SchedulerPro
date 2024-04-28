<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Subject;
use App\Models\Professor;
use App\Models\CollegeClass;


class DashboardService extends AbstractService
{
    /**
     * Get data for display on the dashboard
     *
     * @return array Data
     */
    public function getData()
    {
        $roomsCount = Room::count();
        $subjectsCount = Subject::count();
        $professorsCount = Professor::count();
        $classesCount = CollegeClass::count();

        $data = [
            'cards' => [
                [
                    'title' => 'Classes',
                    'icon' => 'bi bi-calendar2-week',
                    'value' => $classesCount
                ],
                [
                    'title' => 'Professors',
                    'icon' => 'bi bi-person-badge',
                    'value' => $professorsCount
                ],
                [
                    'title' => 'Subjects',
                    'icon' => 'bi bi-journal-text',
                    'value' => $subjectsCount
                ],
                [
                    'title' => 'Rooms',
                    'icon' => 'bi bi-house',
                    'value' => $roomsCount
                ]
            ]
        ];

        return $data;
    }
}