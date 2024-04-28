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
use App\Models\Professor;

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
                    $slots[] = ["","","","",""];
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

        foreach ($arrData as $key => $value) 
        {
            for ($i=0; $i < count($value); $i++) 
            { 
                if($arrData[$key][$i]['units'] > $maxUnit)
                {
                    $maxUnit = $arrData[$key][$i]['units'];
                }
            }
        }
        return $maxUnit;
    }

    function setScheduleOne($days, $times, $timeTable, $rooms, $section, $subject)
    {
        $arrRooms = [];
        $arrProfs = [];
        for ($x=0; $x < count($days); $x++) 
        {
            $day = $days[$x];

            $randSchedIndex = array_rand($timeTable[$section][$day],1);
            $time = $randSchedIndex;

            foreach ($timeTable as $key => $value) 
            {
                if($key != $section)
                {
                    $arrRooms[] = $timeTable[$key][$day][$time][1];
                    $arrProfs[] = $timeTable[$key][$day][$time][3];
                }
            }

            foreach ($rooms as $key => $value) 
            {
                if(!in_array($value['room'], $arrRooms) && $timeTable[$section][$day][$time][1] == "")
                {
                    if(!in_array($subject['prof'], $arrProfs) && $timeTable[$section][$day][$time][3] == "")
                    {
                        if($subject['lab'] == 1)
                        {
                            if($value['lab'] == 1)
                            {
                                return [$day, $time, $value['room']];
                            }
                        }   
                        else
                        {
                            if($value['lab'] == 0)
                            {
                                return [$day, $time, $value['room']];
                            }
                        }
                    }               
                }       
            }
        }
    }

    function setScheduleTwo($days, $times, $timeTable, $rooms, $section, $subject)
    {
        $arrRooms = [];
        $arrProfs = [];
        for ($x=0; $x < count($days); $x++) 
        { 
            for ($y=0; $y < count($times); $y++) 
            { 
                $day = $days[$x];
                $time = $times[$y];

                $arrRooms = [];
                $arrProfs = [];
                foreach ($timeTable as $key => $value) 
                {
                    if($key != $section)
                    {
                        $arrRooms[] = $timeTable[$key][$day][$time-1][1];
                        $arrProfs[] = $timeTable[$key][$day][$time-1][3];
                    }
                }

                foreach ($rooms as $key => $value) 
                {
                    if(!in_array($value['room'], $arrRooms) && $timeTable[$section][$day][$time-1][1] == "")
                    {
                        if(!in_array($subject['prof'], $arrProfs) && $timeTable[$section][$day][$time-1][3] == "")
                        {
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
                        }               
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
                        'subject'       => $value['subject_code'],
                        'subject_name'  => $value['subject_name'],
                        'units'         => $value['units'],
                        'lab'           => $value['lab'],
                        'prof'          => $value['prof']
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
                shuffle($arrDays);
                $section = $key;
                for ($x=0; $x < count($value); $x++) 
                { 
                    if(count($arrDays) == 0)
                    {
                        $arrDays = $days;
                        shuffle($arrDays);
                    }
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
                                    $result = $this->setScheduleTwo($arrDays, $times, $timeTable, $rooms, $section, $value[$x]);

                                    if($result != null)
                                    {
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
                            $timeTable[$section][$arrTempData[$a][0]][$arrTempData[$a][1]][4] = ""; 
                        }       

                        $arrDays = array_reverse($arrDays);
                        $arrDaysCount = count($arrDays);
                        array_splice($arrDays, $arrDaysCount - 1);
                        $arrDays = array_reverse($arrDays);     
                    }               
                }                
            }
        }

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
                                $result = $this->setScheduleOne($arrDays, $times, $timeTable, $rooms, $section, $value[$x]);

                                if($result != null)
                                {
                                    $timeTable[$section][$result[0]][$result[1]][0] = $value[$x]['subject'];
                                    $timeTable[$section][$result[0]][$result[1]][1] = $result[2];
                                    $timeTable[$section][$result[0]][$result[1]][2] = '0';
                                    $timeTable[$section][$result[0]][$result[1]][3] = $value[$x]['prof'];
                                    $timeTable[$section][$result[0]][$result[1]][4] = $value[$x]['subject_name'];
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

    public function loadProfName($profId)
    {
        if($profId != "")
        {
            $professors = new Professor();
            $arrData = $professors->selectProfessor($profId);
            return $arrData[0]->prof_name;
        }
        else
        {
            return "";
        }
    }

    public function printSchedule($scheduleId)
    {
        $timetables = new Timetable();
        $arrData = $timetables->selectSchedule($scheduleId);

        $header = $arrData[0]->name;
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

        foreach ($timeTable as $key => $value) 
        {
            for ($x=0; $x < count($days); $x++) 
            { 
                for ($y=0; $y < count($times); $y++) 
                { 
                    $timeTable[$key][$days[$x]][$y][3] = $this->loadProfName($timeTable[$key][$days[$x]][$y][3]);
                }
            }
        }

        PDF::SetTitle($arrData[0]->name);

        foreach ($timeTable as $key1 => $value1) 
        {
            PDF::AddPage('L', 'A4');

            PDF::SetFont ('helvetica', '', 12 , '', 'default', true );
            PDF::Write(0, $header, '', false, 'C');
            PDF::Ln();
            PDF::SetFont ('helvetica', '', 16 , '', 'default', true );
            PDF::Write(0, str_replace('_',' ',$key1), '', false, 'C');

            PDF::Ln();
            PDF::SetFont ('helvetica', '', 12 , '', 'default', true );
            if(count($days) <= 5)
            {
                $htmlContent = "<br><div><small><b>TIME</b></small></div>";
                PDF::writeHTMLCell(25, 10, 15, 25, $htmlContent, 1, 0, false, true, 'C');

                $dayCount = 40;
                for ($i=0; $i < count($days); $i++) 
                { 
                    $day = strtoupper($days[$i]);
                    $htmlContent = "<br><div><small><b>$day</b></small></div>";
                    PDF::writeHTMLCell(48, 10, $dayCount, 25, $htmlContent, 1, 0, false, true, 'C');
                    $dayCount += 48;
                }

                $timeCount = 36;
                foreach ($arrTimeSlots as $key2 => $value2) 
                {
                    $time = $value2->time;
                    $htmlContent = "<br><div><small><b>$time</b></small></div>";
                    PDF::writeHTMLCell(25, 17, 15, $timeCount, $htmlContent, 1, 0, false, true, 'C');
                    $timeCount += 17;
                }
                
                $dayCount = 40;
                foreach ($timeTable[$key1] as $key2 => $value2) 
                {
                    $timeCount = 36;
                    for ($i=0; $i < count($timeTable[$key1][$key2]); $i++) 
                    { 
                        $subjectCode = ($timeTable[$key1][$key2][$i][0] == "")? "" : "({$timeTable[$key1][$key2][$i][0]})";
                        $room = ($timeTable[$key1][$key2][$i][1] == "")? "" : $timeTable[$key1][$key2][$i][1];
                        $color = ($timeTable[$key1][$key2][$i][2] == "1")? "red" : "black";
                        $prof = ($timeTable[$key1][$key2][$i][3] == "")? "" : $timeTable[$key1][$key2][$i][3];
                        $subjectName = ($timeTable[$key1][$key2][$i][4] == "")? "" : $timeTable[$key1][$key2][$i][4];
                        $htmlContent = <<<EOD
                        <table>
                            <tbody>
                                <tr>
                                    <td colspan="2" style="font-size:1px;"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-size:10px;"><small><i><b>$subjectName</b></i></small> <small><i>$subjectCode</i></small></td>
                                </tr>
                                <tr>
                                    <td style="font-size:8px;"><small><i>$room</i></small></td>
                                    <td style="font-size:8px;"><small><i>$prof</i></small></td>
                                </tr>
                            </tbody>
                        </table>
                        EOD;
                        PDF::writeHTMLCell(48, 17, $dayCount, $timeCount, $htmlContent, 1, 0, false, false, 'C');
                        $timeCount += 17;
                    }
                    $dayCount += 48;
                }
            }
            else if(count($days) == 6)
            {
                $htmlContent = "<br><div><small><b>TIME</b></small></div>";
                PDF::writeHTMLCell(25, 10, 15, 25, $htmlContent, 1, 0, false, true, 'C');

                $dayCount = 40;
                for ($i=0; $i < count($days); $i++) 
                { 
                    $day = strtoupper($days[$i]);
                    $htmlContent = "<br><div><small><b>$day</b></small></div>";
                    PDF::writeHTMLCell(40, 10, $dayCount, 25, $htmlContent, 1, 0, false, true, 'C');
                    $dayCount += 40;
                }

                $timeCount = 36;
                foreach ($arrTimeSlots as $key2 => $value2) 
                {
                    $time = $value2->time;
                    $htmlContent = "<br><div><small><b>$time</b></small></div>";
                    PDF::writeHTMLCell(25, 17, 15, $timeCount, $htmlContent, 1, 0, false, true, 'C');
                    $timeCount += 17;
                }
                
                $dayCount = 40;
                foreach ($timeTable[$key1] as $key2 => $value2) 
                {
                    $timeCount = 36;
                    for ($i=0; $i < count($timeTable[$key1][$key2]); $i++) 
                    { 
                        $subjectCode = ($timeTable[$key1][$key2][$i][0] == "")? "" : "({$timeTable[$key1][$key2][$i][0]})";
                        $room = ($timeTable[$key1][$key2][$i][1] == "")? "" : $timeTable[$key1][$key2][$i][1];
                        $color = ($timeTable[$key1][$key2][$i][2] == "1")? "red" : "black";
                        $prof = ($timeTable[$key1][$key2][$i][3] == "")? "" : $timeTable[$key1][$key2][$i][3];
                        $subjectName = ($timeTable[$key1][$key2][$i][4] == "")? "" : $timeTable[$key1][$key2][$i][4];
                        $htmlContent = <<<EOD
                        <table>
                            <tbody>
                                <tr>
                                    <td colspan="2" style="font-size:1px;"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-size:10px;"><small><i><b>$subjectName</b></i></small> <small><i>$subjectCode</i></small></td>
                                </tr>
                                <tr>
                                    <td style="font-size:8px;"><small><i>$room</i></small></td>
                                    <td style="font-size:8px;"><small><i>$prof</i></small></td>
                                </tr>
                            </tbody>
                        </table>
                        EOD;
                        PDF::writeHTMLCell(40, 17, $dayCount, $timeCount, $htmlContent, 1, 0, false, false, 'C');
                        $timeCount += 17;
                    }
                    $dayCount += 40;
                }
            }

            
        }

        PDF::Output(str_replace(" ","-",$header).'.pdf');
    }

    public function deleteSchedule(Request $request)
    {
        $timetables = new Timetable();
        $result = $timetables->deleteSchedule($request['scheduleId']);
        return response()->json($result);      
    }
}