<div class="row" id="table">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <table class="table">
            <thead>
                <tr class="table-head">
                    <th id="table-bordered" style="width: 90%">Timeslot</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @if (count($timeslots))
                @foreach($timeslots as $timeslot)
                <tr>
                    <td id="table-bordered">{{ $timeslot->time }}</td>
                    <td>
                        <button class="btn btn-sm resource-update-btn" data-id="{{ $timeslot->id }}"><i class="bi bi-pencil-square"></i></button>
                        <button class="btn btn-sm resource-delete-btn" data-id="{{ $timeslot->id }}"><i class="bi bi-trash3"></i></button>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="2" class="text-center">No data found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>