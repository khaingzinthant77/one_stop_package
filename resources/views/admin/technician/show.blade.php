@extends('adminlte::page')

@section('title', 'Technician')

@section('content_header')
    <!-- <h5 style="color: blue;">Technicians</h5> -->
@stop
@section('content')
    <div class="page_body">
       <div class="row">
                <div class="col-md-11">
                    <a class="btn btn-success unicode" href="{{route('technician.index')}}"> Back</a>
                </div>
                <div class="col-md-1">
                    <form action="{{route('technician.destroy',$technician->id)}}" method="POST" onsubmit="return confirm('Do you really want to delete?');">
                                @csrf
                                @method('DELETE')
                             
                                <a class="btn btn-sm btn-primary" href="{{route('technician.edit',$technician->id)}}"><i class="fa fa-fw fa-edit" /></i></a>

                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-fw fa-trash" /></i></button> 
                            </form> 
                </div>
           
        </div>
        <section class="content" style="align-items: center;">
        <div style="background-color: white;box-shadow:0.5px 0.5px 0.5px 5px #e7e7e7;">
        <div class="row" style="margin-top: 20px;">
            <div class="col-md-4"></div>
            <div class="col-md-5" style="margin-top: 50px;">
                @if($technician->photo!='')
                    <img src="{{ asset($technician->path.$technician->photo) }}" alt="photo" width="200px">
                @else
                    <img src="{{ asset('uploads/unnamed.png') }}" alt="photo" width="200px">
                @endif
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-md-4"></div>
            <label class="col-md-2">Team</label>
            <div class="col-md-5">
                @if($technician->group != null)
                <p > {{$technician->group->group_name}}</p>
                @else
                <p></p>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-4"></div>
            <label class="col-md-2">Name</label>
            <div class="col-md-5">
                <p > {{$technician->name}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <label class="col-md-2">Phone No</label>
            <div class="col-md-5">
                <p > {{$technician->phone_no}}</p>
            </div>
        </div>
        
        </div>
    </section>
    </div>
@stop 

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop