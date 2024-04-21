<div class="row" id="table">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <table class="table">
            <thead>
                <tr class="table-head">
                    <th id="table-bordered" style="width: 20%">Name</th>
                    <th id="table-bordered" style="width: 50%">Subjects Assigned</th>
                    <th id="table-bordered" style="width: 20%">Unavailable Timeslot</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @if (count($professors))
                @foreach($professors as $professor)
                <tr>
                    <td id="table-bordered">{{ $professor->name }}</td>
                    <td id="table-bordered">
                        @if (count($professor->subjects))
                            <ul>
                                @foreach ($professor->subjects as $subject)
                                <li>{{ $subject->subject_code . " - " . $subject->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>No subjects added yet.</p>
                        @endif
                    </td>
                    <td id="table-bordered">
                        @if (count($professor->unavailable_timeslots))
                            <ul>
                                @foreach ($professor->unavailable_timeslots as $unavailable)
                                    <li>{{ $unavailable->day->name . " " . $unavailable->timeslot->time }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>No unavailable timeslot.</p>
                        @endif
                    </td>
                    <td>
                    <button class="btn btn-sm resource-update-btn" data-id="{{ $professor->id }}"><i class="bi bi-pencil-square"></i></button>
                    <button class="btn btn-sm resource-delete-btn" data-id="{{ $professor->id }}"><i class="bi bi-trash3"></i></button></td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="4" class="text-center">No data found.</td>
                </tr>
                @endif
            </tbody>
        </table>
         <div id="pagination">
            {!! $professors->render() !!}
        </div>
    </div>
</div>