 @extends('adminlte::page')

@section('title', 'Add New Technician ')

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
        <form action="{{route('group.store')}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="row">
               
                    <label class="col-md-2 unicode">Team Name*</label>
                    <div class="col-md-5 {{ $errors->first('name', 'has-error') }}">
                        
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control unicode" placeholder="Team A">
                     
                    </div>    
               
            </div>
            <br>
            <div class="row">
               
                    <label class="col-md-2 unicode">Login Id*</label>
                    <div class="col-md-5 {{ $errors->first('loginId', 'has-error') }}">
                        
                        <input type="number" name="loginId" id="loginId" value="{{ old('loginId') }}" class="form-control unicode" placeholder="09 XXX XXX XXX">
                     
                    </div>    
               
            </div>
            <br>
            <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        <a class="btn btn-primary unicode" href="{{route('group.index')}}"> Back</a>
                        <button type="submit" class="btn btn-success unicode">Save</button>
                    </div>
            </div>

        </form>
        <input type="hidden" id="ctr_token" value="{{ csrf_token()}}">
    </div>
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
  </style>
   
@stop



@section('js')
<script src="{{ asset('jquery.js') }}"></script>
 <script type="text/javascript" src="{{ asset('jquery-ui.js') }}"></script>
<script>
   
</script>
@stop