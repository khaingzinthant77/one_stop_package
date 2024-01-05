@extends('adminlte::page')

@section('title', 'Setting')

@section('content_header')
<link rel="stylesheet" type="text/css" href="{{asset('jquery-ui.css')}}">
@stop
@section('content')
 <div class="container" >
    @if($setting != null)
        <form action="{{route('setting.update',$setting->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="row form-group"> 
                <label class="col-md-2 unicode">Color</label>
                <div class="col-md-5 {{ $errors->first('color', 'has-error') }}">        
                     <input data-wcp-format="css" type="text" name="color" id="color" class="form-control bs-timepicker" value="{{ $setting->color }}">
                </div>    
            </div>

            
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-5">
                    <a class="btn btn-primary unicode" href="{{route('setting.index')}}"> Back</a>
                     <button class="btn btn-success unicode" type="submit" style="font-size: 13px">
                      Save
                    </button>
                </div>
            </div><br>

        </form>
        @else
        <form action="{{route('setting.create')}}" method="get" enctype="multipart/form-data">
            @csrf
            <div class="row form-group"> 
                <label class="col-md-2 unicode">Color</label>
                <div class="col-md-5 {{ $errors->first('color', 'has-error') }}">        
                     <input data-wcp-format="css" type="text" name="color" id="color" class="form-control bs-timepicker" value="">
                </div>    
            </div>

            
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-5">
                    <a class="btn btn-primary unicode" href="{{route('setting.index')}}"> Back</a>
                     <button class="btn btn-success unicode" type="submit" style="font-size: 13px">
                      Save
                    </button>
                </div>
            </div><br>

        </form>
        @endif
    </div>
@stop
@section('css')
<link type="text/css" rel="stylesheet" href="{{ asset('colorpicker/css/wheelcolorpicker.css')}} " />
<style type="text/css">
    
</style>
@stop

@section('js')
<script src="{{ asset('jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ asset('colorpicker/js/jquery-2.0.3.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('colorpicker/js/jquery.wheelcolorpicker-3.0.5.min.js') }} "></script>

    <script>
        @if(Session::has('success'))
            toastr.options =
            {
            "closeButton" : true,
            "progressBar" : true
            }
            toastr.success("{{ session('success') }}");
        @endif
          $(function() {
              $('#color').wheelColorPicker();
            });
    </script>
@stop