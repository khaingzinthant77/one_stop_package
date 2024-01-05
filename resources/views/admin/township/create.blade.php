@extends('adminlte::page')

@section('title', 'Create Township')

@section('content_header')
  <link rel="stylesheet" type="text/css" href="{{asset('jquery-ui.css')}}">
@stop

@section('content')


     <div class="container" style="margin-top: 50px;">
        <form action="{{route('township.store')}}" method="POST" >
        @csrf

	       <div class="row form-group">
                <label class="col-md-2 unicode">Township Name*</label>
                <div class="col-md-5 {{ $errors->first('town_name', 'has-error') }}">
                    
                    <input type="text" name="town_name" id="town_name" value="{{ old('town_name') }}" class="form-control" placeholder="Pyinmana">
                </div>    
               
            </div>
            
             <div class="row form-group">
             <label class="col-md-2 unicode">Township Short Code*</label>
                <div class="col-md-5 {{ $errors->first('townshort_name', 'has-error') }}">
                    
                    <input type="text" name="townshort_name" id="townshort_name" value="{{ old('townshort_name') }}" class="form-control" placeholder="PMA">
                 
                </div>    
               
            </div>
            
             <div class="row form-group">
                 <label class="col-md-2 unicode">Pirce*</label>
                <div class="col-md-5 {{ $errors->first('price', 'has-error') }}">
                    <input type="number" name="price" id="price" value="{{ old('price') }}" class="form-control unicode" placeholder="0">
                </div>    
            </div>

            <div class="row form-group">
             <label class="col-md-2 unicode">Township Color Code*</label>
                <div class="col-md-5 {{ $errors->first('tsh_color', 'has-error') }}">
                    <input type="text" id="color-css" data-wcp-format="css" name="tsh_color" class="form-control" placeholder=" Select Color ">
                    
                </div>    
               
            </div>
           <!--  <div class="row form-group">
             <label class="col-md-2 unicode">Township Color Code*</label>
                <div class="col-md-5 {{ $errors->first('tsh_color', 'has-error') }}">
                    <input type="text" id="color2" data-wcp-format="css" name="tsh_color2" class="form-control" placeholder=" Select Color ">
                    
                </div>    
               
            </div>
 -->
  			<div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        <a class="btn btn-primary unicode" href="{{route('township.index')}}"> Back</a>
                        <button type="submit" class="btn btn-success unicode">Save</button>
                    </div>
            </div>


        </form>
    </div>
@stop

@section('css')
<link type="text/css" rel="stylesheet" href="{{ asset('colorpicker/css/wheelcolorpicker.css')}} " />
@stop

@section('js')
<script src="{{ asset('jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ asset('colorpicker/js/jquery-2.0.3.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('colorpicker/js/jquery.wheelcolorpicker-3.0.5.min.js') }} "></script>
<script type="text/javascript">
    $(function() {
      $('#color-css').wheelColorPicker();
    });
    $(function() {
      $('#color2').wheelColorPicker();
    });
    
</script>
@stop