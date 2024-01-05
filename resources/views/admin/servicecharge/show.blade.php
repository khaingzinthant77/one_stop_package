@extends('adminlte::page')

@section('title', 'Show ServiceCharge')

@section('content_header')
   <link rel="stylesheet" type="text/css" href="{{asset('jquery-ui.css')}}">
@stop

@section('content')
<div class="row">
        <div class="col-md-10" >
             <a class="btn btn-success unicode" href="{{route('servicecharge.index')}}"> Back</a>
        </div>
        
            <div class="col-md-2">
                <div class="row">
                 <!-- <a href="#" style="background-color: red">Edit</a> -->
                   <a class="btn btn-sm btn-primary" href="{{route('servicecharge.edit',$servicecharge->id)}}"><i class="fa fa-fw fa-edit" /></i></a>
               <form action="{{route('servicecharge.destroy',$servicecharge->id)}}" method="POST" onsubmit="return confirm('Do you really want to delete?');" style="margin-left: 10px">
                @csrf
                @method('DELETE')

               <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-fw fa-trash" /></i></button> 
               
              </form>
              </div>
            </div>    
     </div>
<div class="container" style="margin-top: 50px;">
        
            <div class="row form-group">
                <label class="col-md-2 unicode">Name*</label>
                <div class="col-md-5 {{ $errors->first('name', 'has-error') }}">
                    <input type="text" name="name" id="name" value="{{$servicecharge->name}}" readonly class="form-control unicode" placeholder="Enter Name">
                </div>    
            </div>

            <div class="row form-group">
                <label class="col-md-2 unicode">Price*</label>
                <div class="col-md-5 {{ $errors->first('name', 'has-error') }}">
                    <input type="number" name="price" id="price" value="{{$servicecharge->price}}" readonly class="form-control unicode" placeholder="Enter Price">
                </div>    
            </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop