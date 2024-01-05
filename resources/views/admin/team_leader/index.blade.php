
@extends('adminlte::page')

@section('title', 'Team Leader')

@section('content_header')
<h5 style="color: #009879;">Team Leader Management</h5>
@stop
@section('content')
<?php
  $team_id = isset($_GET['team_id'])?$_GET['team_id']:'';
?>
  <form action="{{route('team_leaders.index')}}" method="get" accept-charset="utf-8" class="form-horizontal">
             <div class="row form-group">
                 <div class="col-md-2">
                 <select class="form-control" id="team_id" name="team_id" style="font-size: 13px">
                      <option value="">Select Team</option>
                      @foreach($teams as $team)
                      <option value="{{$team->id}}" {{ (old('team_id',$team_id)==$team->id)?'selected':'' }}>{{$team->group_name}}</option>
                      @endforeach
                  </select>
                 </div>

                 <div class="col-md-10" align="right">
                    <a class="btn btn-success unicode" href="{{route('team_leaders.create')}}" style="float: right;font-size: 13px"><i class="fas fa-plus"></i>Add</a>
                </div>
               
             </div>
        </form><br>

<div class="table-responsive" style="font-size:13px">
                <table class="table table-bordered styled-table">
                  <thead>
                    <tr> 
                       <th>Team</th>
                      <th>Team Leader</th>
                      <th>Team Member</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($team_leaders->count()>0)
                      @foreach($team_leaders as $team_leader)
                      
                        <tr>
                          <td>{{$team_leader->team}}</td>
                          <td>
                              @if($team_leader->photo == '')
                                  <img src="{{ asset('uploads/unnamed.png') }}" width="40px" height="40px">
                                @else
                                  <img src="{{ asset($team_leader->path.$team_leader->photo)}}" width="40px" height="40px">
                                @endif
                                 {{$team_leader->tech_name}}
                            </td>
                                

                          <td>
                            
                          @foreach($team_leader->members as $team_member)
                          
                       
                            @if($team_member->photo == '')
                            <img src="{{ asset('uploads/unnamed.png') }}" width="40px" height="40px">
                           
                            @else
                                <img src="{{ asset($team_member->path.$team_member->photo)}}" width="40px" height="40px" style="margin-top: 10px">
                             @endif
                            {{$team_member->name}}<br>
                         
                          @endforeach
                          
                        </td>
                        <td>
                                <form action="{{route('team_leaders.destroy',$team_leader->id)}}" method="post"
                                    onsubmit="return confirm('Do you want to delete?');">
                                   @csrf
                                   @method('DELETE')
                                    
                                   <!--  <a class="btn btn-sm btn-primary" href=""><i class="fa fa-fw fa-edit"></i></a> -->
                                    
                                    <button class="btn btn-sm btn-danger btn-sm" type="submit">
                                        <i class="fa fa-fw fa-trash" title="Delete"></i>
                                    </button>
                                   
                                </form>
                            </td>
                         
                        </tr>
                          @endforeach
                    @else
                          <tr align="center">
                            <td colspan="10">No Data!</td>
                          </tr>
                    @endif
                        
                    </tbody>
           </table> 
         
           {!! $team_leaders->appends(request()->input())->links() !!}
       </div>   
@stop 
@section('css')

@stop

@section('js')

<script> 
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
            
            $('#team_id').on('change',function(e) {

                this.form.submit();
            });
            
   
        });
        $(function() {
          $('table').on("click", "tr.table-tr", function() {
            window.location = $(this).data("url");
          });
        });

        $("#date").datepicker({ format: 'dd-mm-yyyy' });

         
        });
     </script>
@stop