<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardService;
use App\Models\Day;
use App\Models\Timetable;
use App\Models\AcademicPeriod;

use App\Models\Timeslot;
use App\Models\Room;
use App\Models\Subject;
// use App\Models\Timetable;

use Illuminate\Support\Facades\Auth;

use PDF;

class DashboardController extends Controller
{
    /**
     * Create a new instance of this controller
     *
     */
    public function __construct(DashboardService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
        $this->middleware('activated');
    }

    protected $service;

    /**
     * Show the application's dashboard
     */
    public function index()
    {
        $data = $this->service->getData();
        $timetables = Timetable::orderBy('created_at', 'DESC')->paginate(10);
        $days = Day::all();
        $academicPeriods = AcademicPeriod::all();
        return view('dashboard.index', compact('data', 'timetables', 'days', 'academicPeriods'));
    }

    function createEmptyFields($days, $times, $courseYearSection)
    {
        $timeTable = [];

        foreach ($courseYearSection as $key => $value) 
        {
            $arrTemp = [];
            for ($x=0; $x < count($days); $x++) 
            { 
                $slots = [];
                for ($y=0; $y < count($times); $y++) 
                { 
                    $slots[] = ["","","",""];
                }
                $arrTemp[$days[$x]] = $slots;
            }
            $timeTable[$key] = $arrTemp;
        }   

        return $timeTable;
    }
    
    function getMaxUnit($arrData)
    {
        $maxUnit = 0;
        $i = 0;

        $arrKeys = [];
        foreach ($arrData as $key => $value) 
        {
            if(!in_array($key, $arrKeys))
            {
                $i = 0;
            }
            $arrKeys[] = $key;
            if($arrData[$key][$i]['units'] > $maxUnit)
            {
                $maxUnit = $arrData[$key][$i]['units'];
            }
            $i++;
        }
        return $maxUnit;
    }

    function setSchedule($days, $times, $timeTable, $rooms, $section, $subject)
    {
        $arrRooms = [];
        // $arrProfs = [];
        for ($x=0; $x < count($days); $x++) 
        { 
            for ($y=0; $y < count($times); $y++) 
            { 
                $day = $days[$x];
                $time = $times[$y];

                $arrRooms = [];
                // $arrProfs = [];
                foreach ($timeTable as $key => $value) 
                {
                    if($key != $section)
                    {
                        $arrRooms[] = $timeTable[$key][$day][$time-1][1];
                        // $arrProfs[] = $timeTable[$key][$day][$time-1][3];
                    }
                }

                foreach ($rooms as $key => $value) 
                {
                    if(!in_array($value['room'], $arrRooms) && $timeTable[$section][$day][$time-1][1] == "")
                    {
                        // if(!in_array($subject['prof'], $arrProfs) && $timeTable[$section][$day][$time-1][3] == "")
                        // {
                            if($subject['lab'] == 1)
                            {
                                if($value['lab'] == 1)
                                {
                                    return [$day, $time-1, $value['room']];
                                }
                            }   
                            else
                            {
                                if($value['lab'] == 0)
                                {
                                    return [$day, $time-1, $value['room']];
                                }
                            }


                        // }               
                    }       
                }
            }
        }
    }

    public function generateSchedule(Request $request)
    {
        $days = json_decode($request['arrDays'],true);
        // $days = ["mon","tue","wed","thu","fri","sat"];

        $timeslots = new Timeslot();
        $arrTimeSlots = $timeslots->loadTimeslots();
        $arrTimeSlotsCount = count($arrTimeSlots);
        $times = [];
        for ($a=1; $a <= ($arrTimeSlotsCount); $a++) 
        { 
            $times[] = $a;
        } 

        $roomss = new Room();
        $arrRooms = $roomss->loadRooms();

        $rooms = json_decode(json_encode($arrRooms),true);

        $academicPeriod = 1;
        $subjects = new Subject();
        $arrSubjects = $subjects->loadSubjects($academicPeriod); 

        $arrClasses = [];
        foreach (json_decode(json_encode($arrSubjects),true) as $key => $value) 
        {
            $value['name'] = str_replace(' ', '_', $value['name']);
            if(!in_array($value['name'], $arrClasses))
            {
                $arrClasses[] = $value['name'];
            }
        }

        $arrData = [];
        for ($i=0; $i < count($arrClasses); $i++) 
        { 
            foreach (json_decode(json_encode($arrSubjects),true) as $key => $value) 
            {
                $value['name'] = str_replace(' ', '_', $value['name']);
                if($arrClasses[$i] == $value['name'])
                {
                    $arrData[$arrClasses[$i]][] = [
                        'subject'   => $value['subject_code'],
                        'units'     => $value['units'],
                        'lab'       => $value['lab'],
                        'prof'      => $value['prof']
                    ]; 
                }
            }
        }


        $timeTable = $this->createEmptyFields($days, $times, $arrData);

        $maxUnit = $this->getMaxUnit($arrData);

        for ($numUnits=$maxUnit; $numUnits > 0; $numUnits--) 
        { 
            foreach ($arrData as $key => $value) 
            {
                $arrDays = $days;
                $section = $key;
                for ($x=0; $x < count($value); $x++) 
                { 
                    if($value[$x]['units'] == $numUnits)
                    {
                        for ($y=0; $y < $value[$x]['units']; $y++) 
                        { 
                            if($value[$x]['lab'] == 0)
                            {
                                $result = $this->setSchedule($arrDays, $times, $timeTable, $rooms, $section, $value[$x]);

                                $timeTable[$section][$result[0]][$result[1]][0] = $value[$x]['subject'];
                                $timeTable[$section][$result[0]][$result[1]][1] = $result[2];
                                $timeTable[$section][$result[0]][$result[1]][2] = '0';
                                $timeTable[$section][$result[0]][$result[1]][3] = $value[$x]['prof'];

                                $arrDays = array_reverse($arrDays);
                                $arrDaysCount = count($arrDays);
                                array_splice($arrDays, $arrDaysCount - 1);
                                $arrDays = array_reverse($arrDays);

                                if(count($arrDays) == 0)
                                {
                                    $arrDays = $days;
                                }
                            }
                        }       
                    }   
                }
            }
        }

        $arrDays = $days;
        for ($numUnits=$maxUnit; $numUnits > 0; $numUnits--) 
        { 
            foreach ($arrData as $key => $value) 
            {
                $section = $key;
                for ($x=0; $x < count($value); $x++) 
                { 
                    if($value[$x]['units'] == $numUnits)
                    {
                        $tf = true;
                        $arrTempData = [];
                        while ($tf == true) 
                        {
                            $day = "";
                            $time = 0;
                            $arr = [];
                            for ($y=0; $y < $value[$x]['units']; $y++) 
                            { 
                                if($value[$x]['lab'] == 1)
                                {
                                    $result = $this->setSchedule($days, $times, $timeTable, $rooms, $section, $value[$x]);

                                    if($result == null)
                                    {
                                        return response()->json(['Error']);
                                    }

                                    $timeTable[$section][$result[0]][$result[1]][0] = $value[$x]['subject'];
                                    $timeTable[$section][$result[0]][$result[1]][1] = $result[2];
                                    $timeTable[$section][$result[0]][$result[1]][2] = '1';
                                    $timeTable[$section][$result[0]][$result[1]][3] = $value[$x]['prof'];

                                    $arr[] = [$result[0], $result[1]];
                                    $arrTempData[] = [$result[0], $result[1]];

                                    if($y == 0)
                                    {
                                        $day = $result[0];
                                        $time = $result[1];
                                    }
                                }
                            }

                            $arrChecking = [];
                            for ($i=0; $i < count($arr); $i++) 
                            { 
                                if($arr[$i][0] == $day && $arr[$i][1] == $time)
                                {
                                    $arrChecking[] = 1;
                                }
                                else
                                {
                                    $arrChecking[] = 0;
                                }
                                $time++;
                            }

                            if(!in_array(0, $arrChecking) || count($arrTempData) == 0)
                            {
                                $tf = false;
                            }
                        }

                        $arrLen = count($arrTempData);
                        $index = $arrLen - $numUnits;
                        array_splice($arrTempData, $index);

                        for ($a=0; $a < count($arrTempData); $a++) 
                        { 
                            $timeTable[$section][$arrTempData[$a][0]][$arrTempData[$a][1]][0] = ""; 
                            $timeTable[$section][$arrTempData[$a][0]][$arrTempData[$a][1]][1] = ""; 
                            $timeTable[$section][$arrTempData[$a][0]][$arrTempData[$a][1]][2] = ""; 
                            $timeTable[$section][$arrTempData[$a][0]][$arrTempData[$a][1]][3] = ""; 
                        }   

                        $arrDays = array_reverse($arrDays);
                        $arrDaysCount = count($arrDays);
                        array_splice($arrDays, $arrDaysCount - 1);
                        $arrDays = array_reverse($arrDays);

                        if(count($arrDays) == 0)
                        {
                            $arrDays = $days;
                        }            
                    }               
                }
            }
        }

        $timetables = new Timetable();

        $arrData = [
            'name'                  => $request['txt_timeTableHeader'],
            'days'                  => json_encode($days),
            'schedules'             => json_encode($timeTable),
            'user_id'               => Auth::user()->id,
            'academic_period_id'    => $request['slc_academicPeriod'],
            'created_at'            => date('Y-m-d H:i:s')
        ];

        $result = $timetables->addSchedule($arrData);



        return response()->json($result);      

        
    }

    public function loadSchedules()
    {
        $timetables = new Timetable();
        $arrSchedules = $timetables->loadSchedules();
        return response()->json($arrSchedules);      
    }

    public function printSchedule($scheduleId)
    {
        $timetables = new Timetable();
        $arrData = $timetables->selectSchedule($scheduleId);

        $timeTable = json_decode($arrData[0]->schedules,true);
        $days = json_decode($arrData[0]->days,true);

        $timeslots = new Timeslot();
        $arrTimeSlots = $timeslots->loadTimeslots();
        $arrTimeSlotsCount = count($arrTimeSlots);
        $times = [];
        for ($a=1; $a <= ($arrTimeSlotsCount); $a++) 
        { 
            $times[] = $a;
        }

        PDF::SetTitle($arrData[0]->name);

        foreach ($timeTable as $key1 => $value1) 
        {
            PDF::AddPage();

            PDF::Write(0, str_replace('_',' ',$key1), '', false, 'C');

            PDF::Ln();

            if(count($days) <= 5)
            {
                $htmlContent = "<br><br><div><small><b>TIME</b></small></div>";
                PDF::writeHTMLCell(25, 25, 15, 25, $htmlContent, 1, 0, false, true, 'C');

                $dayCount = 40;
                for ($i=0; $i < count($days); $i++) 
                { 
                    $day = strtoupper($days[$i]);
                    $htmlContent = "<br><br><div><small><b>$day</b></small></div>";
                    PDF::writeHTMLCell(30, 25, $dayCount, 25, $htmlContent, 1, 0, false, true, 'C');
                    $dayCount += 30;
                }

                $timeCount = 50;
                foreach ($arrTimeSlots as $key2 => $value2) 
                {
                    $time = $value2->time;
                    $htmlContent = "<br><br><div><small><b>$time</b></small></div>";
                    PDF::writeHTMLCell(25, 25, 15, $timeCount, $htmlContent, 1, 0, false, true, 'C');
                    $timeCount += 25;
                }
                
                $dayCount = 40;
                foreach ($timeTable[$key1] as $key2 => $value2) 
                {
                    $timeCount = 50;
                    for ($i=0; $i < count($timeTable[$key1][$key2]); $i++) 
                    { 
                        $subject = ($timeTable[$key1][$key2][$i][0] == "")? "-" : $timeTable[$key1][$key2][$i][0];
                        $room = ($timeTable[$key1][$key2][$i][1] == "")? "-" : $timeTable[$key1][$key2][$i][1];
                        $color = ($timeTable[$key1][$key2][$i][2] == "1")? "red" : "black";
                        $htmlContent = <<<EOD
                        <br><div style="color:$color"><small><b>$subject</b></small> <br> <small>$room</small></div>
                        EOD;
                        PDF::writeHTMLCell(30, 25, $dayCount, $timeCount, $htmlContent, 1, 0, false, true, 'C');
                        $timeCount += 25;
                    }
                    $dayCount += 30;
                }
            }
            else if(count($days) == 6)
            {
                $htmlContent = "<br><br><div><small><b>TIME</b></small></div>";
                PDF::writeHTMLCell(25, 25, 15, 25, $htmlContent, 1, 0, false, true, 'C');

                $dayCount = 40;
                for ($i=0; $i < count($days); $i++) 
                { 
                    $day = strtoupper($days[$i]);
                    $htmlContent = "<br><br><div><small><b>$day</b></small></div>";
                    PDF::writeHTMLCell(25, 25, $dayCount, 25, $htmlContent, 1, 0, false, true, 'C');
                    $dayCount += 25;
                }

                $timeCount = 50;
                foreach ($arrTimeSlots as $key2 => $value2) 
                {
                    $time = $value2->time;
                    $htmlContent = "<br><br><div><small><b>$time</b></small></div>";
                    PDF::writeHTMLCell(25, 25, 15, $timeCount, $htmlContent, 1, 0, false, true, 'C');
                    $timeCount += 25;
                }
                
                $dayCount = 40;
                foreach ($timeTable[$key1] as $key2 => $value2) 
                {
                    $timeCount = 50;
                    for ($i=0; $i < count($timeTable[$key1][$key2]); $i++) 
                    { 
                        $subject = ($timeTable[$key1][$key2][$i][0] == "")? "-" : $timeTable[$key1][$key2][$i][0];
                        $room = ($timeTable[$key1][$key2][$i][1] == "")? "-" : $timeTable[$key1][$key2][$i][1];
                        $color = ($timeTable[$key1][$key2][$i][2] == "1")? "red" : "black";
                        $htmlContent = <<<EOD
                        <br><div style="color:$color"><small><b>$subject</b></small> <br> <small>$room</small></div>
                        EOD;
                        PDF::writeHTMLCell(25, 25, $dayCount, $timeCount, $htmlContent, 1, 0, false, true, 'C');
                        $timeCount += 25;
                    }
                    $dayCount += 25;
                }
            }

            
        }

        PDF::Output('hello_world.pdf');
    }

    public function deleteSchedule(Request $request)
    {
        $timetables = new Timetable();
        $result = $timetables->deleteSchedule($request['scheduleId']);
        return response()->json($result);      
    }
}