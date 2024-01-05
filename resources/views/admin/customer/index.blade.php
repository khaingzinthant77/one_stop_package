@extends('adminlte::page')

@section('title', 'Customer')

@section('content_header')
    <h5 style="color: #009879;">Customer List</h5>
    <style type="text/css">
    </style>
@stop
@section('content')
    <?php
        $keyword = isset($_GET['keyword'])?$_GET['keyword']:'';
        $tsh_id = isset($_GET['tsh_id'])?$_GET['tsh_id']:'';
        $team_id = isset($_GET['team_id'])?$_GET['team_id']:'';
        $assign_status = isset($_GET['assign_status'])?$_GET['assign_status']:'';
        $solve_status = isset($_GET['solve_status'])?$_GET['solve_status']:'';
        $from_date = isset($_GET['from_date'])?$_GET['from_date']:'';
        $to_date = isset($_GET['to_date'])?$_GET['to_date']:'';
        $survey_type = isset($_GET['survey_type'])?$_GET['survey_type']:'';
        $warranty_status = isset($_GET['warranty_status'])?$_GET['warranty_status']:'';
    ?>
        <form action="{{ route('customer.index') }}" method="get" accept-charset="utf-8" class="form-horizontal form-group">
            <div class="row">
                <div class="col-md-11">
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="keyword" id="keyword" value="{{ old('keyword',$keyword) }}" class="form-control" placeholder="Search...">
                             
                        </div>
                        <div class="col-md-2">
                             <!-- Trigger the modal with a button -->
                              <button type="button" class="btn btn-warning "  data-toggle="modal" data-target="#myModal" style="font-size: 13px;margin-top: 4px;"><i class="fa fa-filter" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
                
            </div>
        </form>
        <form class="form-horizontal" action="{{route('customer_import')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-10 row">
                    <div class="col-md-3">
                        <input type="file" name="file" class="form-control">
                    </div>
                    <button class="btn btn-success btn-sm"><i class="fas fa-file-csv"  style="padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px"></i> Import CSV</button>
                </div>
                <div class="row col-md-2">
                    <div >
                        <a class="btn btn-primary"  href="{{route('download_csv')}}"><i class="fa fa-fw fa-download"></i>Demo CSV File</a>&nbsp; 
                    </div>
                
                    <div>
                        <a class="btn btn-warning btn-sm" id="export_btn" style="font-size: 13px;><i class="fa fa-fw fa-file-excel"></i>Export</a>
                    </div>
                </div>
            </div>
        </form>

        <form id="excel_form" action="{{ route('customer_export') }}"  method="POST" class="unicode">
            @csrf
            @method('post')

            <input type="hidden" name="keyword" id="keyword" value="{{$keyword}}">
            <input type="hidden" name="tsh_id" id="tsh_id" value="{{$tsh_id}}">
            <input type="hidden" id="team_id" name="team_id" value="{{ $team_id }}">
            <input type="hidden" id="assign_status" name="assign_status" value="{{ $assign_status }}">
            <input type="hidden" id="solve_status" name="solve_status" value="{{ $solve_status }}">
            <input type="hidden" name="from_date" class="form-control unicode" value="{{ old('from_date',$from_date) }}" >
            <input type="hidden" name="to_date" class="form-control unicode" value="{{ old('to_date',$to_date) }}">
            <input type="hidden" id="survey_type" name="survey_type" value="{{ $survey_type }}">
         </form>

        <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
         <h5 class="modal-title">More Filter</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
       
      </div>
      <div class="modal-body">
         <form action="{{route('customer.index')}}" method="get" accept-charset="utf-8" class="form-horizontal unicode" >
            <div class="row form-group" id="adv_filter">
                    <div class="col-md-6">
                        <label for="">Select Township</label>
                          <select class="form-control" id="tsh_id" name="tsh_id" style="font-size: 13px">
                              <option value="">All</option>
                              @foreach($townships as $township)
                              <option value="{{$township->id}}" {{ (old('tsh_id',$tsh_id)==$township->id)?'selected':'' }}>{{$township->town_name}}</option>
                              @endforeach
                          </select>
                    </div>
                    <!-- <div class="col-md-6">
                        <label for="">Select Team</label>
                          <select class="form-control" id="team_id" name="team_id" style="font-size: 13px">
                              <option value="">All</option>
                              @foreach($teams as $team)
                              <option value="{{$team->id}}" {{ (old('team_id',$team_id)==$team->id)?'selected':'' }}>{{$team->group_name}}</option>
                              @endforeach
                          </select>
                    </div> -->
            </div>
            
            <div class="row form-group">
                <div class="col-md-6">
                    <label for="">From Date</label>
                    <input type="text" name="from_date" id="from_date" class="form-control unicode" placeholder="{{date('d-m-Y')}}" value="{{ old('from_date',$from_date) }}">
                </div>
                 <div class="col-md-6">
                    <label for="">To Date</label>
                    <input type="text" name="to_date" id="to_date" class="form-control unicode" placeholder="{{date('d-m-Y')}}" value="{{ old('to_date',$to_date) }}">
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                    <label for="">Customer Type</label>
                    <select class="form-control" id="survey_type" name="survey_type">
                        <option value="">All</option>
                        <option value="1" {{$survey_type == 1 ? 'selected':''}}>Linn Customer</option>
                        <option value="2" {{$survey_type == 2 ? 'selected':''}}>Other Customer</option>
                    </select>
                </div>
                <!-- <div class="col-md-6">
                    <label for="">Warranty Status</label>
                    <select class="form-control" id="warranty_status" name="warranty_status">
                        <option value="">All</option>
                        <option value="1" {{$warranty_status == 1 ? 'selected' :''}}>Warranty</option>
                        <option value="2" {{$warranty_status == 2 ? 'selected' :''}}>No Warranty</option>
                    </select>
                </div> -->
            </div>
            
            <div class="row form-group">
               <div class="col-md-12" align="center">
                 <button type="button" class="btn btn-danger btn-sm" id="clear_search" >Clear</button>

                 <button type="submit" class="btn btn-primary btn-sm" >Search</button>
               </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
   
    <div class="page_body">
       
        <br>

        <div class="table-responsive">
            <table class="table table-bordered styled-table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Customer Code</th>
                    <th>Survey Date</th>
                    <th>Name</th>
                    <th>Phone No.</th>
                    <th>Township</th>
                    <th>Service Year</th>
                    <th>Assign Team</th>
                    <!-- <th>Team ID</th> -->
                    <th>Assign Date</th>
                    <th>Check</th>
                    <th>Checked By:</th>
                    <th>Solve Status</th>
                </tr>
            </thead>
            <tbody>
               
                @if(count($assigns) != 0)
                @foreach($assigns as $key=>$survey)


                
                    @php

                        // Assuming $yourDate contains the date '2022-11-22 09:23:07'
                        $solved_date = get_solved_date($survey->id);
                      
                        // Convert the string to a Carbon instance
                        $date = \Carbon\Carbon::parse($solved_date);

                        // Add 3 months to the date
                        $newDate = $date->addMonths($warranty_period);

                        // Check if the new date is less than the current date
                        $isWarning = $newDate->isPast();

                    @endphp
            
                <tr>
                    <td class="table-tr" data-url="{{route('customer.show',$survey->id)}}">{{++$key}}</td>
                    <td class="table-tr" data-url="{{route('customer.show',$survey->id)}}">{{$survey->c_code}}</td>
                    <td class="table-tr" data-url="{{route('customer.show',$survey->id)}}">{{date('d-m-Y',strtotime($survey->created_at))}}</td>

                    <td class="table-tr" data-url="{{route('customer.show',$survey->id)}}">{{$survey->name}}</td>
                    <td class="table-tr" data-url="{{route('customer.show',$survey->id)}}">{{$survey->phone_no}}</td>
                    <td class="table-tr" data-url="{{route('customer.show',$survey->id)}}">{{$survey->town_name}}</td>
                    <td class="table-tr" data-url="{{route('customer.show',$survey->id)}}" @if($isWarning) style="color:rgb(255, 174, 0)" @endif>{{date('d-m-Y',strtotime(get_solved_date($survey->id)))}} ({{ date_diff(new \DateTime(get_solved_date($survey->id)), new \DateTime())->format("%yY,%mM, %dD") }})</td>
                    <td>
                        @if(get_assign_team($survey->id) != null)
                        {{get_assign_team($survey->id)}}
                        @else
                        {{get_assign_ticket($survey->id)}}
                        @endif
                    </td>
                    <td>
                        @if(get_assign_date($survey->id) != null)
                        {{get_assign_date($survey->id)}}
                        @else
                        {{get_assign_ticketdate($survey->id)}}
                        @endif
                    </td>
                    <th>
                        @if($survey->admin_check == 0)
                            <button class="btn btn-secondary" onclick="showAlert({{$survey->id}});"><i
                                class="fa fa-check text-white"></i></button>
                        @elseif($survey->admin_check ==1)
                        <button class="btn btn-success"><i
                                class="fa fa-check text-white"></i></button>
                       @endif
                    </th>
                    <td class="table-tr" data-url="{{route('customer.show',$survey->id)}}">
                        {{$survey->checked_by}}
                    </td>
                    <td class="table-tr" data-url="{{route('customer.show',$survey->id)}}">
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
            {!! $assigns->appends(request()->input())->links() !!}
            <div align="center">
                <p>Total -{{$count}}</p>
          </div>
       </div>

    </div>
@stop 

@section('css')

<link id="bsdp-css" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
@stop

@section('js')
<script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
<script> 

    function showAlert(survey_id){
        // alert(survey_id);
        if(!confirm("Do you want to confirm?")) {
            return false;
          }
        $.ajax({
                type: "GET",
                dataType: "json",
                url: "<?php echo route('update_admin_check') ?>",
                data: {'survey_id': survey_id},
                success: function(data){
                 location.reload();
                }
            });
    }
    $(function () {
            $("#from_date").datepicker({ format: 'dd-mm-yyyy' });
            $("#to_date").datepicker({ format: 'dd-mm-yyyy' });
        });

    @if(Session::has('success'))
            toastr.options =
            {
            "closeButton" : true,
            "progressBar" : true
            }
            toastr.success("{{ session('success') }}");
        @endif

          @if(Session::has('error'))
          toastr.options =
          {
            "closeButton" : true,
            "progressBar" : true
          }
                toastr.error("{{ session('error') }}");
          @endif

        $(document).ready(function(){
            setTimeout(function(){
            $("div.alert").remove();
            }, 1000 ); 
            $(function() {
                $('#town_name').on('change',function(e) {
                this.form.submit();
               // $( "#form_id" )[0].submit();   
            }); 
            $('#export_btn').click(function(){
                $('#excel_form').submit();
            });
        });
        $(function() {
          $('table').on("click", "td.table-tr", function() {
            window.location = $(this).data("url");
          });
        });
        });
     </script>
@stop