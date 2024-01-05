@extends('adminlte::page')

@section('title', 'Survey')

@section('content_header')
    <h5 style="color: #009879;">Survey List</h5>
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
        $is_install = isset($_GET['is_install'])?$_GET['is_install']:'';
    ?>
        <form action="{{ route('surveys.index') }}" method="get" accept-charset="utf-8" class="form-horizontal">
            <div class="row">
                <div class="col-md-11">
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="keyword" id="keyword" value="{{ old('keyword',$keyword) }}" class="form-control" placeholder="Search...">
                             
                        </div>
                        <div class="mr-3">
                             <!-- Trigger the modal with a button -->
                              <button type="button" class="btn btn-warning "  data-toggle="modal" data-target="#myModal" style="font-size: 13px;margin-top: 4px;"><i class="fa fa-filter" aria-hidden="true"></i></button>
                        </div>
                        <div class="">
                             <button type="button" class="btn btn-warning" id="export_btn" style="font-size: 13px;margin-top: 4px;"><i class="fa fa-file-excel" aria-hidden="true"> Export</i></button>
                       </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <a class="btn btn-success" href="{{route('surveys.create')}}"><i class="fas fa-plus"> Create</i></a>
                </div>
            </div>
        </form>

        {{-- Excel Form --}}
        <form id="excel_form" action="{{ route('survey_export') }}"  method="POST" class="unicode">
            @csrf
            @method('post')

            <input type="hidden" name="keyword" id="keyword" value="{{$keyword}}">
            <input type="hidden" name="is_install" value="{{$is_install}}">
            <input type="hidden" name="tsh_id" id="tsh_id" value="{{$tsh_id}}">
            <input type="hidden" id="team_id" name="team_id" value="{{ $team_id }}">
            <input type="hidden" name="assign_status" id="assign_status" value="{{$assign_status}}">
            <input type="hidden" id="solve_status" name="solve_status" value="{{ $solve_status }}">
            <input type="hidden" name="from_date" class="form-control unicode" value="{{ old('from_date',$from_date) }}">
             <input type="hidden" name="to_date" class="form-control unicode" value="{{ old('to_date',$to_date) }}">
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
                 <form action="{{route('surveys.index')}}" method="get" accept-charset="utf-8" class="form-horizontal unicode" >
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
                            <div class="col-md-6">
                                <label for="">Select Team</label>
                                  <select class="form-control" id="team_id" name="team_id" style="font-size: 13px">
                                      <option value="">All</option>
                                      @foreach($teams as $team)
                                      <option value="{{$team->id}}" {{ (old('team_id',$team_id)==$team->id)?'selected':'' }}>{{$team->group_name}}</option>
                                      @endforeach
                                  </select>
                            </div>
                    </div>
                     <div class="row form-group" id="adv_filter">
                            <div class="col-md-6">
                                <label for="">Assign Status</label>
                                  <select class="form-control" id="assign_status" name="assign_status" style="font-size: 13px">
                                      <option value="">All</option>
                                        <option value="1" {{$assign_status == "1" ? 'selected' : ''}}>Assign</option>
                                        <option value="0" {{$assign_status == "0" ? 'selected' : ''}}>Not Assign</option>
                                  </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Select Team</label>
                                  <select class="form-control" id="solve_status" name="solve_status" style="font-size: 13px">
                                      <option value="">All</option>
                                      <option value="1" {{$solve_status == "1" ? 'selected' : ''}}>Solved</option>
                                      <option value="0" {{$solve_status == "0" ? 'selected' : ''}}>Unsolve</option>
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
                            <label for="">Not Install</label>
                            <select class="form-control" id="is_install" name="is_install">
                                <option value="">Select</option>
                                <option value="0" {{$is_install == "0" ? 'selected' : ''}}>Not Install</option>
                                <option value="1" {{$is_install == "1" ? 'selected' : ''}}>Install</option>
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


        <!-- remark_modal -->
        <!-- <div id="remark_modal" class="modal fade" role="dialog"> -->
        <div class="modal" id="remark_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                 <h5 class="modal-title">Please type remark</h5>
                <button type="button" class="remark_close" data-dismiss="modal">&times;</button>
               
              </div>
              <div class="modal-body">
                 <form action="{{route('surveys.not_install_update')}}" method="post" accept-charset="utf-8" class="form-horizontal unicode" >
                    @csrf
                    @method('post')
                   <div class="form-group">
                       <textarea class="form-control" id="remark" name="remark" placeholder="Remark" required></textarea>
                   </div>
                   <input type="hidden" name="survey_id" id="survey_id">
                    <div class="row form-group">
                       <div class="col-md-12" align="center">
                         <button type="button" class="btn btn-danger btn-sm" id="cancel" >Cancel</button>

                         <button type="submit" class="btn btn-primary btn-sm" >Save</button>
                       </div>
                    </div>
                </form>
              </div>
              <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              </div> -->
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
                    <td class="table-tr" data-url="{{route('surveys.show',$survey->id)}}">{{++$i}}</td>
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
                    <td style="display: flex; justify-content:center; align-items:center;">
                        @if($survey->is_solve==1)
                            <span style="background-color:#28a745;padding: 7px;color: white;border-radius: 5px;">Solved</span>  
                        @else
                            <span style="background-color:#ffc107;padding: 7px;color: white;border-radius: 5px;">Unsolve</span> 
                       @endif
                       @if($survey->is_install == 1)
                        <span onclick="show_remark_modal({{$survey->id}})" style="margin-left: 0.5rem">
                          <i class="fa fa-exclamation-triangle" style="font-size:24px;color: #136dbe;" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Not Install"></i>
                      </span>
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
            <div align="center">
                <p>Total -{{$count}}</p>
          </div>
          {{ $assigns->appends(request()->input())->links()}}
       </div>

    </div>
@stop 

@section('css')
<link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">
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
                url: "<?php echo route('update_survey_check') ?>",
                data: {'survey_id': survey_id},
                success: function(data){
                 location.reload();
                }
            });
    }

    function show_remark_modal(survey_id){
        // alert(survey_id);
        $('#survey_id').val(survey_id);
        $('#remark_modal').show();
    }

    $('.remark_close').click(function(){
        $('#remark_modal').hide();
    });

    $('#cancel').click(function(){
        $('#remark_modal').hide();
    });

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
        $(function() {
          $('table').on("click", "td.table-tr", function() {
            window.location = $(this).data("url");
          });
        });
        });

        $('#export_btn').click(function(){
                $('#excel_form').submit();
        });
     </script>
@stop