<div class="row" id="table">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <table class="table">
            <thead>
                <tr class="table-head">
                    <th id="table-bordered" style="width: 15%">Subject Code</th>
                    <th id="table-bordered" style="width: 40%">Subject Description</th>
                    <th id="table-bordered" style="width: 15%">Laboratory</th>
                    <th id="table-bordered" style="width: 35%">Professor Assigned</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @if (count($subjects))
                @foreach($subjects as $subject)
                <tr>
                    <td id="table-bordered">{{ $subject->subject_code }}</td>
                    <td id="table-bordered">{{ $subject->name }}</td>
                    <td id="table-bordered">
                        @if($subject->lab == 1)
                            Yes
                        @else
                            No 
                        @endif
                    </td>
                    <td id="table-bordered">
                        <ul>
                        @foreach ($subject->professors as $professor)
                        <li>{{ $professor->name }}</li>
                        @endforeach
                        </ul>
                    </td>
                    <td>
                    <button class="btn btn-sm resource-update-btn" data-id="{{ $subject->id }}"><i class="bi bi-pencil-square"></i></button>
                    <button class="btn btn-sm resource-delete-btn" data-id="{{ $subject->id }}"><i class="bi bi-trash3"></i></button></td>
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
            {!! $subjects->render() !!}
        </div>
    </div>
</div>