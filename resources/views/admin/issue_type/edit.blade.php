 @extends('adminlte::page')

@section('title', 'Issue Type')

@section('content_header')
 <link rel="stylesheet" type="text/css" href="{{asset('jquery-ui.css')}}">
@stop

@section('content')

    <div class="row form-group">
        <div class="col-md-10" >
             <a class="btn btn-success unicode" href="{{route('issue_type.index')}}"> Back</a>
        </div>
        
            <div class="col-md-2">
                <div class="row">
                
                 
               <form action="{{route('issue_type.destroy',$issueType->id)}}" method="POST" onsubmit="return confirm('Do you really want to delete?');" style="margin-left: 10px">
                @csrf
                @method('DELETE')

               <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-fw fa-trash" /></i></button> 
               
              </form>
              </div>
            </div>    
        </div>
    <form action="{{route('issue_type.update',$issueType->id)}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row form-group">
                <label class="col-md-1 unicode">Issue Type</label>
                <div class="col-md-3">
                    
                    <input type="text" name="issue_type" id="issue_type" value="{{ old('issue_type',$issueType->issue_type) }}" class="form-control unicode" placeholder="Enter Issue Type">
                 
                </div>    
       </div>

       <div class="row form-group">
                <div class="col-md-2"></div>
                <div class="col-md-5">
                    <a class="btn btn-primary unicode" href="{{route('issue_type.index')}}" style="width: 65px"> Back</a>
                    <button type="submit" class="btn btn-success unicode" style="height: 34px;font-size: 13px">Update</button>
                </div>
        </div>
    </form>
   
@stop

@section('css')
  <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="{{ asset('jquery.js') }}"></script>

@stop