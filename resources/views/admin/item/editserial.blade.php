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
        <form action="{{route('updateserial',$productserials->id)}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        @csrf
        @method('PUT')
            <div class="row">
                    <label class="col-md-2 unicode">Serial No</label>
                    <div class="col-md-5">
                        
                        <input type="text" name="serial_no" id="serial_no" value="{{$productserials->serial_no}}" class="form-control unicode">
                     
                    </div>    
            </div>
            <br>  
            
            <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        <a class="btn btn-primary " href="{{route('item.index')}}" style="width: 65px"> Back</a>
                        <button type="submit" class="btn btn-success " style="height: 34px;font-size: 13px">Update</button>
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