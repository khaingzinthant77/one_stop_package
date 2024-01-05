@extends('adminlte::page')

@section('title', 'Show Township')

@section('content_header')
      <link rel="stylesheet" type="text/css" href="{{asset('jquery-ui.css')}}">
@stop

@section('content')
   <div class="row">
        <div class="col-md-10" >
             <a class="btn btn-success unicode" href="{{route('township.index')}}"> Back</a>
        </div>
        
            <div class="col-md-2">
                <div class="row">
                 <!-- <a href="#" style="background-color: red">Edit</a> -->
                   <a class="btn btn-sm btn-primary" href="{{route('township.edit',$township->id)}}"><i class="fa fa-fw fa-edit" /></i></a>
               <form action="{{route('township.destroy',$township->id)}}" method="POST" onsubmit="return confirm('Do you really want to delete?');" style="margin-left: 10px">
                @csrf
                @method('DELETE')

               <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-fw fa-trash" /></i></button> 
               
              </form>
              </div>
            </div>    
     </div><br>
     <div class="container" style="margin-top: 50px; ">
        <form action="#" method="POST" >

      <div class="form-group row">
	    <label for="name" class="col-sm-2 col-form-label">Township name</label>
	    <div class="col-sm-6">
	      <input type="text" class="form-control" id="name" placeholder="Enter township name" name="town_name" value="{{$township->town_name}}" readonly>
	    </div>
	  </div>

	   <div class="form-group row">
	    <label for="short_name" class="col-sm-2 col-form-label">Township short name</label>
	    <div class="col-sm-6">
	      <input type="text" class="form-control" id="short_name" placeholder="Enter township short name" name="townshort_name" value="{{$township->townshort_name}}" readonly>
	    </div>
	  </div>

     <div class="form-group row">
      <label for="price" class="col-sm-2 col-form-label">Price</label>
      <div class="col-sm-6">
        <input type="text" class="form-control" id="price" name="price" value="{{$township->price}}" readonly>
      </div>
    </div>
    <div class="form-group row">
        <label for="short_name" class="col-sm-2 col-form-label">Township Color Code</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="short_name" placeholder="Enter township short name" name="tsh_color" value="{{$township->tsh_color}}" readonly>
        </div>
      </div>
     <!--  <div class="form-group row">
        <label for="short_name" class="col-sm-2 col-form-label">Township Color Code2</label>
        <div class="col-sm-6">
          <input type="text" class="form-control" id="short_name" placeholder="Enter township short name" name="tsh_color" value="{{$township->tsh_color2}}" readonly>
        </div>
      </div>
 -->
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop