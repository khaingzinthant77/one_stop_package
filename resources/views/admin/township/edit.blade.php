@extends('adminlte::page')

@section('title', 'Edit Township')

@section('content_header')
   <link rel="stylesheet" type="text/css" href="{{asset('jquery-ui.css')}}">
@stop

@section('content')

     <div class="container" style="margin-top: 50px; ">
        <form action="{{route('township.update',$township->id)}}" method="POST" >
        @csrf
       @method('PUT')

       <div class="row form-group">
            <label class="col-md-2 unicode">Township name</label>
            <div class="col-md-5 {{ $errors->first('town_name', 'has-error') }}">
                <input type="text" name="town_name" id="town_name" value="{{$township->town_name}}" class="form-control unicode">
            </div>    
        </div>

         <div class="row form-group">
            <label class="col-md-2 unicode">Township Short Name</label>
            <div class="col-md-5 {{ $errors->first('townshort_name', 'has-error') }}">
                
                <input type="text" name="townshort_name" id="townshort_name" value="{{$township->townshort_name}}" class="form-control unicode">
             
            </div>    
        </div>

         <div class="row form-group">
            <label class="col-md-2 unicode">Price</label>
            <div class="col-md-5 {{ $errors->first('price', 'has-error') }}">
                
                <input type="text" name="price" id="price" value="{{$township->price}}" class="form-control unicode">
            </div>     
        </div>

        <div class="row form-group">
            <label class="col-md-2 unicode">Township Color Code</label>
            <div class="col-md-5 {{ $errors->first('tsh_color', 'has-error') }}">
             <input type="text" id="color-css" data-wcp-format="css" name="tsh_color" class="form-control" placeholder=" Select Color" value="{{$township->tsh_color}}">
            </div>    
        </div>
      <!--   <div class="row form-group">
            <label class="col-md-2 unicode">Township Color Code</label>
            <div class="col-md-5 {{ $errors->first('tsh_color2', 'has-error') }}">
             <input type="text" id="color2" data-wcp-format="css" name="tsh_color2" class="form-control" placeholder=" Select Color" value="{{$township->tsh_color2}}">
            </div>    
        </div> -->

        <div class="row form-group">
            <div class="col-md-2"></div>
            <div class="col-md-5">
                <a class="btn btn-primary unicode" href="{{route('township.index')}}"> Back</a>
                <button type="submit" class="btn btn-success unicode">Update</button>
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
</script>
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop