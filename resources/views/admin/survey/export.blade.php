<html>
    <head>
    </head>
    <body>
      <div class="table-responsive">
        <table class="table table-bordered styled-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Survey Date</th>
                <th>Name</th>
                <th>Phone No.</th>
                <th>Township</th>
                <th>Assign Team</th>
                <th>Assign Date</th>
                <th>Survey By:</th>
                <th>Check</th>
                <th>Checked By</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if(count($assigns) != 0)
            @foreach($assigns as $key=>$survey)
            <tr>
                <td class="table-tr" data-url="{{route('surveys.show',$survey->id)}}">{{++$key}}</td>
                <td class="table-tr" data-url="{{route('surveys.show',$survey->id)}}">{{date('d-m-Y',strtotime($survey->created_at))}}</td>
                <td class="table-tr" data-url="{{route('surveys.show',$survey->id)}}">{{$survey->name}}</td>
                <td class="table-tr" data-url="{{route('surveys.show',$survey->id)}}">{{$survey->phone_no}}</td>
                <td class="table-tr" data-url="{{route('surveys.show',$survey->id)}}">{{$survey->town_name}}</td>
                <td class="table-tr" data-url="{{route('surveys.show',$survey->id)}}">{{$survey->group_name}}</td>
                @if($survey->assign_date != null)
                <td class="table-tr" data-url="{{route('surveys.show',$survey->id)}}">{{date('d-m-Y',strtotime($survey->assign_date))}}</td>
                @else
                <td class="table-tr" data-url="{{route('surveys.show',$survey->id)}}"></td>
                @endif
                <td class="table-tr" data-url="{{route('surveys.show',$survey->id)}}">{{$survey->survey_name}}</td>
                <th>
                    @if($survey->admin_check == 0)
                        <button class="btn btn-secondary" onclick="showAlert({{$survey->id}});"><i
                            class="fa fa-check text-white"></i></button>
                    @elseif($survey->admin_check ==1)
                    <button class="btn btn-success"><i
                            class="fa fa-check text-white"></i></button>
                   @endif
                </th>
                <td class="table-tr" data-url="{{route('surveys.show',$survey->id)}}">{{$survey->checked_by}}</td>
                <td class="table-tr" data-url="{{route('surveys.show',$survey->id)}}">
                    @if($survey->is_solve==1)
                        <span style="background-color:#28a745;padding: 7px;color: white;border-radius: 5px;">Solved</span>  
                    @else
                        <span style="background-color:#ffc107;padding: 7px;color: white;border-radius: 5px;">Unsolve</span> 
                   @endif
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="4"></td>
                <td>No Data</td>
            </tr>
            @endif
        </tbody>
        </table>
   </div>
    </body>
</html>