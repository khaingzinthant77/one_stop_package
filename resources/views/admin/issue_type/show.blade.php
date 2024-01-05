 @extends('adminlte::page')

@section('title', 'Issue Type')

@section('content_header')
 <link rel="stylesheet" type="text/css" href="{{asset('jquery-ui.css')}}">
@stop

@section('content')

    <div class="row">
        <div class="col-md-10" >
             <a class="btn btn-success unicode" href="{{route('issue_type.index')}}"> Back</a>
        </div>
        
            <div class="col-md-2">
                <div class="row">
                
                   <a class="btn btn-sm btn-primary" href="{{route('issue_type.edit',$issueType->id)}}"><i class="fa fa-fw fa-edit" style="padding-left: 2px;padding-right: 5px;padding-top: 5px;padding-bottom: 5px"/></i></a>
               <form action="{{route('issue_type.destroy',$issueType->id)}}" method="POST" onsubmit="return confirm('Do you really want to delete?');" style="margin-left: 10px">
                @csrf
                @method('DELETE')

               <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-fw fa-trash" /></i></button> 
               
              </form>
              </div>
            </div>    
     </div><br>

            <div class="row">
                    <label class="col-md-1 unicode">Issue Type</label>
                    <div class="col-md-3">
                        
                        <input type="text" name="name" id="name" value="{{ old('name',$issueType->issue_type) }}" class="form-control unicode" placeholder="Enter Issue Type" readonly>
                     
                    </div>    
           </div>
   
@stop

@section('css')
  <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="{{ asset('jquery.js') }}"></script>

@stop