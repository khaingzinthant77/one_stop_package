 @extends('adminlte::page')

@section('title', 'Add New Technician ')

@section('content_header')
 <link rel="stylesheet" type="text/css" href="{{asset('jquery-ui.css')}}">
@stop

@section('content')
<div class="container">
    <div class="panel-body">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <form action="{{route('team_leaders.store')}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="row form-group">
                    <label class="col-md-2 unicode">Team*</label>
                    <div class="col-md-5 {{ $errors->first('team_id', 'has-error') }}">
                        <select class="form-control" id="team_id" name="team_id">
                            <option value="">Select Team</option>
                            @foreach($teams as $key=>$team)
                            <option value="{{$team->id}}">{{$team->group_name}}</option>
                            @endforeach
                        </select>
                    </div>    
            </div>
            
            <div class="row form-group">
                <label class="col-md-2 unicode">Team Leader*</label>
                <div class="col-md-5 {{ $errors->first('leader_id', 'has-error') }}">
                 <select class="form-control" id="leader_id" name="leader_id">
                     <option value="">Select Team Leader</option>
                     @foreach($technicians as $key=>$technician)
                     <option value="{{$technician->id}}">{{$technician->name}}</option>
                     @endforeach
                 </select>
                </div>    
            </div>

             <div class="row form-group">
                <label class="col-md-2 unicode">Member*</label>
                <div class="col-md-5 {{ $errors->first('member', 'has-error') }}">
                    <select multiple class="form-control livesearch" id="member_id" name="member_id[]">
                 </select>
                </div>    
            </div>
            
            <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        <a class="btn btn-primary unicode" href="{{route('team_leaders.index')}}"> Back</a>
                        <button type="submit" class="btn btn-success unicode">Save</button>
                    </div>
            </div>

        </form>
        <input type="hidden" id="ctr_token" value="{{ csrf_token()}}">
    </div>
</div>
@stop



@section('css')
<link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }}"/>
   <style type="text/css" media="screen">
      .error_msg{
        color: #DD4B39;
      }
      .has-error input{
        border-color: #DD4B39;
      }
      .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: black;
    }
    .select2-container .select2-selection--single {
        box-sizing: border-box;
        cursor: pointer;
        display: block;
        height: 35px;
        user-select: none;
        -webkit-user-select: none; 
    }
        .select2-container--default .select2-selection--single .select2-selection_{
        height: 30px;
        position: absolute;
        top: 2px;
        right: 0px;
        left: 365px;
        width: 100px; 
    }
  </style>
   
@stop



@section('js')
<script src="{{ asset('jquery.js') }}"></script>
 <script type="text/javascript" src="{{ asset('jquery-ui.js') }}"></script>
 <script type="text/javascript" src="{{ asset('select2/js/select2.min.js') }}"></script>
<script>
   $('#team_id').on("change", function() {
    let val = $(this).val();
    // alert(val);
    var token = $("input[name='_token']").val();
    if (val != "") {
        $.ajax({
            type: "POST",
            url: '<?php echo route('select_team') ?>',
            data: {
                id: val,
                _token:token
            },
            success: function(data) {
                $("#leader_id").html(data);
            },
            error: function(xhr, status, error) {
                console.error(xhr);
            }
        });
    }
});

   $(function() {

            var team_id = '';
            var dep_id = '';
            $("#team_id").change(function(){

                team_id = $(this).val();
               let val = $(this).val();
                // alert(val);
                var token = $("input[name='_token']").val();
                if (val != "") {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo route('select_team') ?>',
                        data: {
                            id: val,
                            _token:token
                        },
                        success: function(data) {
                            $("#leader_id").html(data);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr);
                        }
                    });
                }
            });

            $("#leader_id").change(function(){
                leader_id = $(this).val();
                getTechnician(team_id,leader_id)
            });

        });

   function getTechnician(team_id,leader_id){
            // alert("hello");
            var url = "<?php echo(route("get_technicians")) ?>";
            var fullurl = url + '?team_id='+team_id+"&leader_id="+leader_id;
            $('.livesearch').select2({
                placeholder: 'Select Member',
                ajax: {
                    url: fullurl,
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                    }
                });
            }
</script>
@stop