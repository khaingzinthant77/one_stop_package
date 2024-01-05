@extends('adminlte::page')

@section('title', 'Category')

@section('content_header')
    <h5 style="color: blue;">Category Management</h5>
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
@section('content')
    <?php
        $name = isset($_GET['name'])?$_GET['name']:'';

    ?>
    <form ction="{{ route('category.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row" >
            <div class="col-md-2 {{ $errors->first('name', 'has-error') }} form-group">
               <input type="text" name="name" placeholder="Category Name" class="form-control"> 
                @if($errors->first('name'))
                    <span class="help-block">
                        <small>{{ $errors->first('name') }}</small>
                    </span>
                @endif
            </div>
             <div class="col-md-2 {{ $errors->first('name', 'has-error') }} form-group">
                <input type="number" name="price" placeholder="Enter Price" class="form-control">
                @if($errors->first('name'))
                    <span class="help-block">
                        <small>{{ $errors->first('name') }}</small>
                    </span>
                @endif
            </div>
             <div class="col-md-2">
            <button class="btn btn-success" type="submit" style="height: 34px;font-size: 13px">
                Save
                <!-- <i class="fas fa-plus"> Category</i> -->
            </button>
        </div>
           
        </div>
    </form>

        <div class="row form-group">
        <div class="col-md-2">
        <form action="{{ route('category.index') }}" method="get" accept-charset="utf-8" class="form-horizontal">
            <input type="text" name="name" id="name" value="{{ old('name',$name) }}" class="form-control" placeholder="Search...">
        </form>
        </div>
        <div class="col-md-6">
              <form class="form-horizontal" action="{{route('categoryimport')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                    <div class="col-md-4">
                         <input type="file" name="file" class="form-control">
                            @if ($errors->has('file'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('file') }}</strong>
                                </span>
                            @endif
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-success btn-sm"><i class="fas fa-file-csv"  style="padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px"></i> Import CSV</button>
                    </div>
                    </div>
                </form>
        </div>
        <div class="col-md-3">
             <a class="btn btn-warning btn-sm" id="export_btn" href="{{route('categoryexport')}}" style="float: right;"><i class="fa fa-fw fa-file-excel"></i>Export</a>
        </div>
        </div>
           

    <div class="page_body">
       
        {{-- @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif --}}

        <div class="table-responsive">
            <table class="table table-bordered styled-table">
                <thead>
                <tr>
                    <th style="width: 25px">No</th>
                    <th>Name</th>
                    <th>Installation Charges</th>
                    <th>Status</th>
                    <th>Show Dashboard</th>
                </tr>
            </thead>
            @if($category->count()>0)
             @foreach($category as $cat)
                <tr class="table-tr" >
                    <td style="width: 25px" data-url="{{route('category.show',$cat->id)}}">{{++$i}}</td>
                   <td data-url="{{route('category.show',$cat->id)}}">{{$cat->name}}</td>
                   <td data-url="{{route('category.show',$cat->id)}}">{{number_format($cat->install_charge)}}</td>
                   <th >
                       <label class="switch">
                          <input data-id="{{$cat->id}}" data-size ="small" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $cat->status ? 'checked' : '' }}>
                          <span class="slider round"></span>
                        </label>
                   </th>
                   <th>
                       <label class="switch">
                          <input data-id="{{$cat->id}}" data-size ="small" class="show_status" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ $cat->show_status ? 'checked' : '' }}>
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
{{ $category->appends(request()->input())->links()}}
    </div>
@stop 

@section('css')
     <style type="text/css" media="screen">
      .error_msg{
        color: #DD4B39;
      }
      .has-error input{
        border-color: #DD4B39;
      }
      .help-block{
        color: #DD4B39;
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
               // $( "#form_id" )[0].submit();   
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
                var cat_id = $(this).data('id'); 
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "<?php echo(route("change_status_category")) ?>",
                    data: {'status': status, 'cat_id': cat_id},
                    success: function(data){
                     console.log(data.success);
                    }
                });
            })
          });
        $(function() {
            $('.show_status').change(function() {
                var status = $(this).prop('checked') == true ? 1 : 0; 
                var cat_id = $(this).data('id'); 
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "<?php echo(route("change_status_show")) ?>",
                    data: {'status': status, 'cat_id': cat_id},
                    success: function(data){
                     console.log(data.success);
                    }
                });
            })
          });
        
     </script>
@stop