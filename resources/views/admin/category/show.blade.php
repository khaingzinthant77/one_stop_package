@extends('adminlte::page')

@section('title', 'Show Category')

@section('content_header')
   <link rel="stylesheet" type="text/css" href="{{asset('jquery-ui.css')}}">
@stop

@section('content')

	
     <div class="row form-group">
        <div class="col-md-10" >
             <a class="btn btn-success unicode" href="{{route('category.index')}}"> Back</a>
        </div>
        
            <div class="col-md-2">
                <div class="row">
                 <!-- <a href="#" style="background-color: red">Edit</a> -->
                   <a class="btn btn-sm btn-primary" href="{{route('category.edit',$category->id)}}"><i class="fa fa-fw fa-edit" /></i></a>
               <form action="{{route('category.destroy',$category->id)}}" method="POST" onsubmit="return confirm('Do you really want to delete?');" style="margin-left: 10px">
                @csrf
                @method('DELETE')

               <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-fw fa-trash" /></i></button> 
               
              </form>
              </div>
            </div>    
     </div>

    <div class="row">
               
        <label class="col-md-2 unicode">Category name</label>
        <div class="col-md-5 {{ $errors->first('name', 'has-error') }}">
            
            <input type="text" id="name" class="form-control unicode" value="{{$category->name}}" readonly>
         
        </div>    
   
    </div><br>

    <div class="row">
               
        <label class="col-md-2 unicode">Installation Charges</label>
        <div class="col-md-5">
            
            <input type="text" id="price" class="form-control unicode" value="{{$category->price}}" readonly>
         
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