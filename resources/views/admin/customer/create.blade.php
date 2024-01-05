@extends('adminlte::page')

@section('title', 'Create Product')

@section('content_header')
  <link rel="stylesheet" type="text/css" href="{{asset('jquery-ui.css')}}">
  <style type="text/css">
    #catoption{
    padding-bottom: auto;
    }
  </style>
@stop

@section('content')
      <div class="panel-body">
          @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <form action="{{route('item.store')}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        @csrf
        @method('post')

        <div class="col-md-6 form-group">
            <div class="row form-group">
               <label class="col-md-3 unicode">Name*</label>
                <div class="col-md-9"> 
                   <input type="text" name="cust_name" id="cust_name" class="form-control">
                </div>    
            </div>
            <div class="row form-group">
               <label class="col-md-3 unicode">Phone No*</label>
                <div class="col-md-9"> 
                   <input type="number" name="cust_phone" id="cust_phone" class="form-control">
                </div>    
            </div>
            <div class="row form-group">
               <label class="col-md-3 unicode">Township*</label>
                <div class="col-md-9"> 
                   <input type="number" name="cust_phone" id="cust_phone" class="form-control">
                </div>    
            </div>
        </div>
          

            <div class="row form-group">
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        <a class="btn btn-primary unicode" href="{{route('item.index')}}"> Back</a>
                        <button type="submit" class="btn btn-success unicode" onClick="javascript:p=true;" style="height: 34px;font-size: 13px">Save</button>
                    </div>
            </div>

        </form>

        <input type="hidden" id="ctr_token" value="{{ csrf_token()}}">
      </div>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }}"/>
    <style type="text/css" media="screen">
      .error_msg{
        color: #DD4B39;
      }
      .has-error input{
        border-color: #DD4B39;
      }
      .help-block{
        color: #DD4B39;
      }

      .select2-container .select2-selection--single {
        box-sizing: border-box;
        cursor: pointer;
        display: block;
        height: 35px;
        user-select: none;
        -webkit-user-select: none; 
    }
        .select2-container--default .select2-selection--single .select2-selection_{
        height: 30px;
        position: absolute;
        top: 2px;
        right: 0px;
        left: 365px;
        width: 100px; 
    }

  </style>
@stop

@section('js')

<script src="{{asset('js/jquery.min.js')}}" type="text/javascript"></script>
 <script src = "{{asset('js/jquery.js')}}"></script>
<script src = "{{asset('js/jquery-ui.js')}}"></script>
<script type="text/javascript" src="{{asset('scannerdetection.js')}}"></script>
 <script type="text/javascript" src="{{ asset('select2/js/select2.min.js') }}"></script>
    <script type="text/javascript">
       
    </script>
@stop