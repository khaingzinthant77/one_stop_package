@extends('adminlte::page')

@section('title', 'Technician')

@section('content_header')
    <h5 style="color: #009879;">Technicians</h5>
@stop
@section('content')
    <?php
        $name = isset($_GET['name'])?$_GET['name']:'';
        $team_id = isset($_GET['team_id'])?$_GET['team_id']:'';   
    ?>
        <form action="{{ route('technician.index') }}" method="get" accept-charset="utf-8" class="form-horizontal">
            <div class="row">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="name" id="name" value="{{ old('name',$name) }}" class="form-control" placeholder="Search...">
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="team_id" name="team_id">
                                <option value="">Select Team</option>
                                @foreach($teams as $key=>$team)
                                <option value="{{$team->id}}" {{$team->id == $team_id ? "selected" : ""}}>{{$team->group_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-success unicode" href="{{route('technician.create')}}"><i class="fas fa-user-cog" /></i> Add New!</a>
                </div>
            </div>
        </form>
    <div class="page_body">
       
        <!-- @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
          -->
        <br>

        <div class="table-responsive">
            <table class="table table-bordered styled-table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Photo</th>
                     <th>Name</th>
                    <th>Phone No</th>
                    <th>Team</th>
                    <th>Status</th>
                </tr>
                </thead>
            @if(count($technicians)>0)
              @foreach($technicians as $technician)
                <tr class="table-tr">
                    <td data-url="{{route('technician.show',$technician->id)}}">{{++$i}}</td>
                    <td data-url="{{route('technician.show',$technician->id)}}">
                        @if ($technician->photo == null)
                            <img src="{{ asset('uploads/unnamed.png') }}" alt="photo" width="60px" height="60px" style="border-radius: 30px;">
                        @else
                            <img src="{{ asset($technician->path.$technician->photo) }}" alt="image"
                                    width="60px" height="60px" style="border-radius:30px;">
                        @endif
                    </td>
                    <td data-url="{{route('technician.show',$technician->id)}}">{{$technician->name}}</a></td>
                    <td data-url="{{route('technician.show',$technician->id)}}">{{$technician->phone_no}}</td>
                    @if($technician->group != null)
                    <td data-url="{{route('technician.show',$technician->id)}}">{{$technician->group->group_name}}</td>
                    @else
                    <td data-url="{{route('technician.show',$technician->id)}}"></td>
                    @endif
                    <th onmouseover="this.style.background='#c7d4dd';" onmouseout="this.style.background='#f4f6f9';">
                        <label class="switch">
                          <input data-id="{{$technician->id}}" data-size ="small" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $technician->status ? 'checked' : '' }}>
                          <span class="slider round"></span>
                        </label>
                    </th>
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
        {{ $technicians->appends(request()->input())->links()}}
    </div>
@stop 

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style type="text/css">
        .add {
              background-color:#AA55AA;
              border: none;
              color: white;
              padding: 2px 20px;
              font-size: 30px;
              cursor: pointer;
            }

            /* Darker background on mouse-over */
            .add:hover {
              background-color: #FF55FF;
            }
            .input-group.md-form.form-sm.form-1 input{
            border: 1px solid purple;
            border-top-right-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
            }
            .input-group-text{
            background-color:#AA55AA;
            color:white;
            }
            .switch {
              position: relative;
              display: inline-block;
              width: 45px;
              height: 22px;
            }

            .switch input { 
              opacity: 0;
              width: 0;
              height: 0;
            }

            .slider {
              position: absolute;
              cursor: pointer;
              top: 0;
              left: 0;
              right: 0;
              bottom: 0;
              background-color: #ccc;
              -webkit-transition: .4s;
              transition: .4s;
            }

            .slider:before {
              position: absolute;
              content: "";
              height: 15px;
              width: 15px;
              left: 2px;
              bottom: 0px;
              top:3px;
              background-color: white;
              -webkit-transition: .4s;
              transition: .4s;
            }

            input:checked + .slider {
              background-color: #2196F3;
            }

            input:focus + .slider {
              box-shadow: 0 0 1px #2196F3;
            }

            input:checked + .slider:before {
              -webkit-transform: translateX(26px);
              -ms-transform: translateX(26px);
              transform: translateX(26px);
            }

            /* Rounded sliders */
            .slider.round {
              border-radius: 36px;
            }

            .slider.round:before {
              border-radius: 50%;
            }
    </style>
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
                $('#name').on('change',function(e) {
                this.form.submit();
            }); 
                $('#team_id').on('change',function(e) {
                this.form.submit();
            }); 
        });
            $(function() {
              $('table').on("click", "td", function() {
                window.location = $(this).data("url");
              });
            });
        });

        $(function() {
            $('.toggle-class').change(function() {
                var status = $(this).prop('checked') == true ? 1 : 0; 
                var tech_id = $(this).data('id'); 
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "<?php echo(route("change_status_tech")) ?>",
                    data: {'status': status, 'tech_id': tech_id},
                    success: function(data){
                     console.log(data.success);
                    }
                });
            })
          });
     </script>
@stop