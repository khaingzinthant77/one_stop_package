@extends('adminlte::page')

@section('title', 'Ticket')

@section('content_header')
    <h5 style="color: #009879;">Ticket List</h5>
     <style type="text/css">
    </style>
@stop
@section('content')
    <?php
        $keyword = isset($_GET['keyword'])?$_GET['keyword']:'';
        $is_solve = isset($_GET['is_solve'])?$_GET['is_solve']:'';
        $team_id = isset($_GET['team_id'])?$_GET['team_id']:'';
        $tsh_id = isset($_GET['tsh_id'])?$_GET['tsh_id']:'';
        $issue_id = isset($_GET['issue_id'])?$_GET['issue_id']:'';
        $from_date = isset($_GET['from_date'])?$_GET['from_date']:'';
        $to_date = isset($_GET['to_date'])?$_GET['to_date']:''; 
        
    ?> 
     
        <form action="{{ route('ticket.index') }}" method="get" accept-charset="utf-8" class="form-horizontal">
            <div class="row form-group">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="keyword" id="keyword" value="{{ old('keyword',$keyword) }}" class="form-control" placeholder="Search...">
                            
                             <input type="hidden" name="is_solve"  value="{{$is_solve}}">
                             <input type="hidden" name="team_id"  value="{{$team_id}}">
                             <input type="hidden" name="tsh_id" value="{{$tsh_id}}">
                             <input type="hidden" name="issue_id" value="{{$issue_id}}">
                             <input type="hidden" name="from_date" value="{{$from_date}}">
                             <input type="hidden" name="to_date" value="{{$to_date}}">
                        </div>
                        <div class="col-md-2">
                             <!-- Trigger the modal with a button -->
                              <button type="button" class="btn btn-warning "  data-toggle="modal" data-target="#myModal" style="font-size: 13px;margin-top: 4px;"><i class="fa fa-filter" aria-hidden="true"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <a class="btn btn-success" href="{{route('ticket.create')}}"><i class="fas fa-plus">Create</i></a>

                </div>
                <div class="col-md-1">
                    <a class="btn btn-warning" id="export_btn"><i class="fas fa-file">Export</i></a>
             </div>
            </div>
        </form>

        <form id="excel_form" action="{{ route('service_export') }}"  method="POST" class="unicode">
            @csrf
            @method('post')
            <input type="hidden" name="keyword" id="keyword" value="{{$keyword}}">
            <input type="hidden" name="is_solve" id="is_solve" value="{{$is_solve}}">
            <input type="hidden" id="team_id" name="team_id" value="{{ $team_id }}">
            <input type="hidden" name="tsh_id" id="tsh_id" value="{{$tsh_id}}">
            <input type="hidden" id="issue_id" name="issue_id" value="{{ $issue_id }}">
            <input type="hidden" name="from_date" class="form-control unicode" value="{{ old('from_date',$from_date) }}">
             <input type="hidden" name="to_date" class="form-control unicode" value="{{ old('to_date',$to_date) }}">
         </form>

        <div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                 <h5 class="modal-title">More Filter</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
               
              </div>
              <div class="modal-body">
                 <form action="{{route('ticket.index')}}" method="get" accept-charset="utf-8" class="form-horizontal unicode" >
                    <div class="row form-group" id="adv_filter">
                            <div class="col-md-6">
                                <label for="">Ticket Status</label>
                                  <select class="form-control" name="is_solve" id="is_solve" >
                                    <option value="">All</option>
                                    <option value="0" {{ ($is_solve=='0')?'selected':'' }} >Unsolved</option>
                                    <option value="1" {{ ($is_solve=='1')?'selected':'' }} >Solved</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Select Team</label>
                                  <select class="form-control" name="team_id" id="team_id" >
                                    <option value="">All</option>
                                    @foreach($groups as $group)
                                    <option value="{{$group->id}}" {{ ($team_id == $group->id)?'selected':'' }} >{{$group->group_name}}</option>
                                    @endforeach
                                </select>
                            </div>
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
                            <label for="">Township</label>
                              <select class="form-control" name="tsh_id" id="tsh_id" >
                                <option value="">All</option>
                                @foreach($townships as $township)
                                <option value="{{$township->id}}" {{ ($tsh_id == $township->id)?'selected':'' }} >{{$township->town_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Issue Type</label>
                              <select class="form-control" name="issue_id" id="issue_id" >
                                <option value="">All</option>
                                @foreach($issue_types as $issue_type)
                                <option value="{{$issue_type->id}}" {{ ($issue_id == $issue_type->id)?'selected':'' }} >{{$issue_type->issue_type}}</option>
                                @endforeach
                            </select>
                        </div>
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
       
       

        <div class="table-responsive">
            <table class="table table-bordered styled-table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Ticket ID</th>
                    <th>Name</th>
                    <th>Phone No</th>
                    <th>Township</th>
                    <th>Ticket Issue</th>
                    <th>Assign Technician</th>
                    <th>Assign Date</th>
                    <th>Solved Date</th>
                    <th>Check</th>
                    <th>Checked by:</th>
                    <th>Status</th>
                    <!-- <th>Action</th> -->
                </tr>
                </thead>
            @if(count($tickets)>0)
             @foreach($tickets as $key=>$ticket)
            
                @php
                    $date1 = $ticket->assign_date;
                    $date2 = date('Y-m-d H:i:s');
                    $timestamp1 = strtotime($date1);

                    $timestamp2 = strtotime($date2);
                    $diffInHours = abs($timestamp2 - $timestamp1)/(60*60);
                  
                    $hour1 = 0; $hour2 = 0;
                    $min = 0;
                    $d1 = $ticket->assign_date;
                    $d2 = date('Y-m-d H:i:s');
                    $datetimeObj1 = new DateTime($d1);
                    $datetimeObj2 = new DateTime($d2);
                    $interval = $datetimeObj1->diff($datetimeObj2);
                    
                    if($interval->format('%a') > 0){
                    $hour1 = $interval->format('%a')*24;
                    }
                    if($interval->format('%h') > 0){
                    $hour2 = $interval->format('%h');
                    }

                    $min = $interval->format('%i');
                    
                    $ticket_hour = $hour1 + $hour2 ; 
                    if($ticket_hour<10){
                        $ticket_hour = "0". $ticket_hour;
                    }

                    if($min<10){
                        $min = "0". $min;
                    }

                    $ticket_hour = $ticket_hour . ":". $min;

                    
                @endphp

                <tr @if($diffInHours>8 && $diffInHours<24 && $ticket->is_solve == 0) style="color:rgb(255, 174, 0)" @elseif($diffInHours>24 && $ticket->is_solve==0) style="color:red" @endif>
                    <td class="table-tr" data-url="{{route('ticket.show',$ticket->id)}}">{{++$key}}</td>
                    <td>{{$ticket->townshort_name}}-T{{$ticket->id}}</td>
                    <td class="table-tr" data-url="{{route('ticket.show',$ticket->id)}}">{{$ticket->name}}</td>
                    <td class="table-tr" data-url="{{route('ticket.show',$ticket->id)}}">{{$ticket->phone_no}}</td>
                    <td class="table-tr" data-url="{{route('ticket.show',$ticket->id)}}">{{$ticket->town_name}}</td>
                    <td class="table-tr" data-url="{{route('ticket.show',$ticket->id)}}">{{$ticket->issue_type}}</td>
                    <td class="table-tr" data-url="{{route('ticket.show',$ticket->id)}}">{{$ticket->group_name}}</td>
                    <td class="table-tr" data-url="{{route('ticket.show',$ticket->id)}}">{{date('d-m-Y', strtotime($ticket->assign_date))}}</td>
                    @if($ticket->solved_date != null)
                    <td class="table-tr" data-url="{{route('ticket.show',$ticket->id)}}">{{date('d-m-Y h:i A', strtotime($ticket->solved_date))}}</td>
                    @else
                    <td class="table-tr" data-url="{{route('ticket.show',$ticket->id)}}"></td>
                    @endif
                    <th>
                        @if($ticket->admin_check == 0)
                            <button class="btn btn-secondary" onclick="showAlert({{$ticket->id}});"><i
                                class="fa fa-check text-white"></i></button>
                        @elseif($ticket->admin_check ==1)
                        <button class="btn btn-success"><i
                                class="fa fa-check text-white"></i></button>
                       @endif
                    </th>
                    <td class="table-tr" data-url="{{route('ticket.show',$ticket->id)}}">{{$ticket->checked_by}}</td>
                    @if($ticket->is_solve == 1)
                    <td class="table-tr" data-url="{{route('ticket.show',$ticket->id)}}">
                        <span style="background-color:#28a745;padding: 7px;color: white;border-radius: 5px;">Solved</span>
                    </td> 
                    @else
                    <td class="table-tr" data-url="{{route('ticket.show',$ticket->id)}}">
                        <span style="background-color:#ffc107;padding: 7px;color: white;border-radius: 5px;">Unsolve</span> 
                    </td>
                    @endif
                   
                </tr>
             @endforeach
             @else
             <tr align="center">
                  <td colspan="10">No Data!</td>
            </tr>
             @endif
            </table>
            <div align="center">
                <p>Total -{{$count}}</p>
          </div>
       </div>
       {{ $tickets->appends(request()->input())->links()}}
    </div>
@stop 

@section('css')
   <link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">
    <link id="bsdp-css" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    
@stop

@section('js')
<script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
    <script> 
        function showAlert(ticket_id){
        // alert(ticket_id);
        if(!confirm("Do you want to confirm?")) {
            return false;
          }
        $.ajax({
                type: "GET",
                dataType: "json",
                url: "<?php echo route('update_ticket_check') ?>",
                data: {'ticket_id': ticket_id},
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
            
        });

            $('#export_btn').click(function(){
                $('#excel_form').submit();
            });
        $(function() {
          $('table').on("click", "td.table-tr", function() {
            window.location = $(this).data("url");
          });
        });
        });
     </script>
@stop