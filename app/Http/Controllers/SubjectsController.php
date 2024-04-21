<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SubjectsService;

use App\Models\Subject;
use App\Models\Professor;

class SubjectsController extends Controller
{
    /**
     * Service class for handling operations relating to this
     * controller
     *
     * @var App\Services\SubjectsService $service
     */
    protected $service;

    public function __construct(SubjectsService $service)
    {
        $this->middleware('auth');
        $this->middleware('activated');
        $this->service = $service;
    }

    /**
     * Get a listing of subjects
     *
     * @param Illuminate\Http\Request $request The HTTP request
     */
    public function index(Request $request)
    {
        $subjects = $this->service->all([
            'keyword' => $request->has('keyword') ? $request->keyword : null,
            'filter' => $request->has('filter') ? $request->filter : null,
            'order_by' => 'subject_code',
            'paginate' => 'true',
            'per_page' => 20
        ]);

        $professors = Professor::all();

        if ($request->ajax()) {
            return view('subjects.table', compact('subjects'));
        }

        return view('subjects.index', compact('subjects', 'professors'));
    }

    /**
     * Add a new subject
     *
     * @param Illuminate\Http\Request $request The HTTP request
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'subject_code' => 'required|unique:subjects,subject_code',
        ];

        $messages = [
            'name.unique' => 'This subject already exists',
        ];

        $this->validate($request, $rules, $messages);

        $subject = $this->service->store($request->all());

        if ($subject) {
            return response()->json(['message' => 'Subject added.'], 200);
        } else {
            return response()->json(['error' => 'A system error occurred!'], 500);
        }
    }

    /**
     * Get a room by id
     *
     * @param int id The id of the room
     * @param Illuminate\Http\Request $request HTTP request
     */
    public function show($id, Request $request)
    {
        $subject = $this->service->show($id);

        if ($subject) {
            return response()->json($subject, 200);
        } else {
            return response()->json(['error' => 'Subject not found!'], 404);
        }
    }

    /**
     * Update room with given ID
     *
     * @param int id The id of the room to be updated
     * @param Illuminate\Http\Request The HTTP request
     */
    public function update($id, Request $request)
    {
        $rules = [
            'name' => 'required',
            'subject_code' => 'required|unique:subjects,subject_code,' . $id
        ];

        $messages = [
            'name.unique' => 'This subject already exists'
        ];

        $this->validate($request, $rules, $messages);

        $subject = $this->service->show($id);

        if (!$subject) {
            return response()->json(['error' => 'Subject not found!'], 404);
        }

        $subject = $this->service->update($id, $request->all());

        return response()->json(['message' => 'Subject updated.'], 200);
    }

    /**
     * Delete the subject whose id is given
     *
     * @param int $id The id of subject to be deleted
     */
    public function destroy($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json(['error' => 'Subject not found!'], 404);
        }

        if ($this->service->delete($id)) {
            return response()->json(['message' => 'Subject has been deleted.'], 200);
        } else {
            return response()->json(['error' => 'An unknown system error occurred!'], 500);
        }
    }
}
