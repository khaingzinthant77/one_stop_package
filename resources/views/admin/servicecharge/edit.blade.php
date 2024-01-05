@extends('adminlte::page')

@section('title', 'Edit ServiceCharge')

@section('content_header')
   <link rel="stylesheet" type="text/css" href="{{asset('jquery-ui.css')}}">
@stop

@section('content')

     <div class="container" style="margin-top: 50px; ">
        <form action="{{route('servicecharge.update',$servicecharge->id)}}" method="POST" >
        @csrf
       @method('PUT')

       <div class="row">
               
        <label class="col-md-2 unicode">Name*</label>
        <div class="col-md-5 {{ $errors->first('name', 'has-error') }}">
            
            <input type="text" name="name" id="name" value="{{$servicecharge->name}}" class="form-control unicode">
         
        </div> 
        </div> <br>
         <div class="row">
               
        <label class="col-md-2 unicode">Price*</label>
        <div class="col-md-5 {{ $errors->first('price', 'has-error') }}">
            
            <input type="text" name="price" id="price" value="{{$servicecharge->price}}" class="form-control unicode">
         
        </div>    
               
        </div><br>
        <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        <a class="btn btn-primary unicode" href="{{route('servicecharge.index')}}"> Back</a>
                        <button type="submit" class="btn btn-success unicode">Update</button>
                    </div>
            </div>

        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop