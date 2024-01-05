@extends('adminlte::page')

@section('title', 'Create ServiceCharge')

@section('content_header')
  <link rel="stylesheet" type="text/css" href="{{asset('jquery-ui.css')}}">
@stop

@section('content')
    <div class="container" style="margin-top: 50px;">
        <form action="{{route('servicecharge.store')}}" method="POST" >
        @csrf
            <div class="row form-group">
                <label class="col-md-2 unicode">Name</label>
                <div class="col-md-5 {{ $errors->first('name', 'has-error') }}">
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control unicode" placeholder="Enter Name">
                </div>    
            </div>

            <div class="row form-group">
                <label class="col-md-2 unicode">Price</label>
                <div class="col-md-5 {{ $errors->first('name', 'has-error') }}">
                    <input type="number" name="price" id="price" value="{{ old('price') }}" class="form-control unicode" placeholder="Enter Price">
                </div>    
            </div>

  			<div class="row form-group">
                <div class="col-md-2"></div>
                <div class="col-md-5">
                    <a class="btn btn-primary unicode" href="{{route('servicecharge.index')}}"> Back</a>
                    <button type="submit" class="btn btn-success unicode">Save</button>
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