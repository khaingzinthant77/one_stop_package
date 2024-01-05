@extends('adminlte::page')

@section('title', 'Edit Category')

@section('content_header')
   <link rel="stylesheet" type="text/css" href="{{asset('jquery-ui.css')}}">
@stop

@section('content')

     <div class="container" style="margin-top: 50px; ">
        <form action="{{route('category.update',$category->id)}}" method="POST" >
        @csrf
       @method('PUT')

       <div class="row">
               
        <label class="col-md-2 unicode">Category name</label>
        <div class="col-md-5 {{ $errors->first('name', 'has-error') }}">
            
            <input type="text" name="name" id="name" value="{{$category->name}}" class="form-control unicode">
         
        </div>    
    </div><br>

         <div class="row">
               
        <label class="col-md-2 unicode">Installation Charges</label>
        <div class="col-md-5">
            
            <input type="text" name="price" id="price" value="{{$category->install_charge}}" class="form-control unicode">
         
        </div>    
               
        </div><br>
        <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        <a class="btn btn-primary unicode" href="{{route('category.index')}}"> Back</a>
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