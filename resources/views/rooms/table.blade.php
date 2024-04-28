<div class="row" id="table">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <table class="table">
            <thead>
                <tr class="table-head">
                    <th id="table-bordered" style="width: 50%">Name</th>
                    <th id="table-bordered" style="width: 40%">Laboratory?</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @if (count($rooms))
                @foreach($rooms as $room)
                <tr>
                    <td id="table-bordered">{{ $room->name }}</td>
                    <td id="table-bordered">
                        @if($room->lab == 1)
                            Yes
                        @else
                            No 
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm resource-update-btn" data-id="{{ $room->id }}"><i class="bi bi-pencil-square"></i></button>
                        <button class="btn btn-sm resource-delete-btn" data-id="{{ $room->id }}"><i class="bi bi-trash3"></i></button>
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
        <div id="pagination">
            {!! $rooms->render() !!}
        </div>
    </div>
</div>