 @extends('adminlte::page')

@section('title', 'Edit Brand ')

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
        <form action="{{route('brand.update',$brand->id)}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        @csrf
        @method('PUT')

          
            <div class="row">
                    <label class="col-md-2 unicode">Brand Name</label>
                    <div class="col-md-5">
                        
                        <input type="text" name="name" id="name" value="{{ old('name',$brand->name) }}" class="form-control unicode" placeholder="Enter brand name">
                     
                    </div>    
            </div>
            <br>  
            
            <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        <a class="btn btn-primary unicode" href="{{route('brand.index')}}" style="width: 65px"> Back</a>
                        <button type="submit" class="btn btn-success unicode" style="height: 34px;font-size: 13px">Update</button>
                    </div>
            </div>

        </form>
        <input type="hidden" id="ctr_token" value="{{ csrf_token()}}">
    </div>
</div>
@stop



@section('css')
  
@stop



@section('js')
<script src="{{ asset('jquery.js') }}"></script>

@stop