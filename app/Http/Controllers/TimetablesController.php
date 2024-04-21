<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TimetableService;
use App\Events\TimetablesRequested;

use App\Models\Day;
use App\Models\Timetable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TimetablesController extends Controller
{
    protected TimetableService $service;

    /**
     * Create a new instance of this controller and set up
     * middlewares on this controller methods
     */
    public function __construct(TimetableService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
        $this->middleware('activated');
    }

    /**
     * Handle ajax request to load timetable to populate
     * timetables table on dashboard
     *
     */
    public function index(Request $request)
    {
        $timetables = $this->service->all([
            'keyword' => $request->has('keyword') ? $request->keyword : null,
            'order_by' => 'name',
            'paginate' => 'true',
            'per_page' => 20
        ]);

        if ($request->ajax()) {
            return view('dashboard.timetables', compact('timetables'));
        }

        return view('dashboard.index', compact('timetables'));
    }

    /**
     * Create a new timetable object and hand over to genetic algorithm
     * to generate
     *
     * @param \Illuminate\Http\Request $request The HTTP request
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:timetables,name',
            'academic_period_id' => 'required'
        ];

        $messages = [
            'name.unique' => 'Timetable name already exists.',
            'academic_period_id.required' => 'An academic period must be selected.'
        ];

        $this->validate($request, $rules, $messages);

        $errors = [];
        $dayIds = [];

        $days = Day::all();

        foreach ($days as $day) {
            if ($request->has('day_' . $day->id)) {
                $dayIds[] = $day->id;
            }
        }

        if (!count($dayIds)) {
            $errors[] = 'At least one day should be selected';
        }

        if (count($errors)) {
            return response()->json(['errors' => $errors], 422);
        }

        $otherChecks = $this->service->checkCreationConditions();

        if (count($otherChecks)) {
            return response()->json(['errors' => $otherChecks], 422);
        }

        $timetable = Timetable::create([
            'user_id' => Auth::user()->id,
            'academic_period_id' => $request->academic_period_id,
            'status' => 'IN PROGRESS',
            'name' => $request->name
        ]);

        if ($timetable) {
            $timetable->days()->sync($dayIds);
        }

        event(new TimetablesRequested($timetable));

        return response()->json(['message' => 'Timetables are being generated. Check back later...'], 200);
    }

    /**
     * Display a printable view of timetable set
     *
     * @param int $id
     */
    public function view($id)
    {
        $timetable = Timetable::find($id);

        if (!$timetable) {
            return redirect('/');
        } else {
            $path = $timetable->file_url;
            $timetableData =  Storage::get($path);
            $timetableName = $timetable->name;
            return view('timetables.view', compact('timetableData', 'timetableName'));
        }
    }

    public function destroy($id)
    {
        $timetable = Timetable::find($id);

        if (!$timetable) {
            return response()->json(['error' => 'Timetable not found!'], 404);
        }

        if ($this->service->delete($id)) {
            return response()->json(['message' => 'Timetable has been deleted.'], 200);
        } else {
            return response()->json(['error' => 'An unknown system error occurred!'], 500);
        }
    }
}