<div class="row" id="table">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <table class="table">
            <thead>
                <tr class="table-head">
                    <th id="table-bordered" style="width: 20%">Course/Yr/Blk</th>
                    <th id="table-bordered" style="width: 70%">Subjects</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @if (count($classes))
                @foreach($classes as $class)
                <tr>
                    <td id="table-bordered">{{ $class->name }}</td>
                    <td id="table-bordered">
                        @foreach ($academicPeriods as $period)
                            <?php 
                                $subjects = $class->subjects()->wherePivot('academic_period_id', $period->id)->get(); 
                            ?>
                            @if (count($subjects))
                                {{ $period->name . " : " }}
                                <ul>
                                    @foreach ($subjects as $subject)
                                        <li>{{ $subject->subject_code . " - " . $subject->name }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        <button class="btn btn-sm resource-update-btn" data-id="{{ $class->id }}"><i class="bi bi-pencil-square"></i></button>
                        <button class="btn btn-sm resource-delete-btn" data-id="{{ $class->id }}"><i class="bi bi-trash3"></i></button>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="5" class="text-center">No data found.</td>
                </tr>
                @endif
            </tbody>
        </table>
         <div id="pagination">
            {!! $classes->render() !!}
        </div>
    </div>
</div>