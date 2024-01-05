 @extends('adminlte::page')

@section('title', 'Show Brand')

@section('content_header')
 <link rel="stylesheet" type="text/css" href="{{asset('jquery-ui.css')}}">
@stop

@section('content')

    <div class="row">
        <div class="col-md-10" >
             <a class="btn btn-success unicode" href="{{route('brand.index')}}"> Back</a>
        </div>
        
            <div class="col-md-2">
                <div class="row">
                 <!-- <a href="#" style="background-color: red">Edit</a> -->
                   <a class="btn btn-sm btn-primary" href="{{route('brand.edit',$brand->id)}}"><i class="fa fa-fw fa-edit" style="padding-left: 2px;padding-right: 5px;padding-top: 5px;padding-bottom: 5px"/></i></a>
               <form action="{{route('brand.destroy',$brand->id)}}" method="POST" onsubmit="return confirm('Do you really want to delete?');" style="margin-left: 10px">
                @csrf
                @method('DELETE')

               <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-fw fa-trash" /></i></button> 
               
              </form>
              </div>
            </div>    
     </div><br>

            <div class="row">
                    <label class="col-md-1 unicode">Brand Name</label>
                    <div class="col-md-5">
                        
                        <input type="text" name="name" id="name" value="{{ old('name',$brand->name) }}" class="form-control unicode" placeholder="Enter brand name" readonly>
                     
                    </div>    
           </div>
   
@stop

@section('css')
  <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script src="{{ asset('jquery.js') }}"></script>

@stop