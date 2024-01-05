 @extends('adminlte::page')

@section('title', 'Technician ')

@section('content_header')

@stop

@section('content')
<div class="container">
    <div class="panel-body">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <form action="{{route('technician.update',$technician->id)}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row form-group">
                    <label class="col-md-2 unicode">Name*</label>
                    <div class="col-md-5 {{ $errors->first('name', 'has-error') }}">
                        
                        <input type="text" name="name" id="name" value="{{ old('name',$technician->name) }}" class="form-control unicode" placeholder="Mg Mg">
                     
                    </div>    
            </div>

            <div class="row form-group">
                <label class="col-md-2 unicode">Phone No*</label>
                <div class="col-md-5 {{ $errors->first('phone_no', 'has-error') }}">
                    
                    <input type="number" name="phone_no" id="phone_no" value="{{ old('phone_no',$technician->phone_no) }}" class="form-control unicode" placeholder="09 XXX XXX XXX">
                 
                </div>    
            </div>
           
            <div class="row form-group">
                <label class="col-md-2 unicode">Photo</label>
                    <div class="col-md-5">
                        <input type="file" name="photo" id="photo" class="form-control unicode" value="{{$technician->photo}}">
                    </div>
                
            </div>
            <div class="row form-group">
                <label class="col-md-2 unicode">Team</label>
                    <div class="col-md-5">
                        <select class="form-control" id="group_id" name="group_id">
                            <option value="">Select Team</option>
                            @foreach($groups as $key=>$group)
                            <option value="{{$group->id}}" {{$group->id == $technician->group_id ? "selected" : ""}}>{{$group->group_name}}</option>
                            @endforeach
                        </select>
                    </div>
                
            </div>
            <div class="row form-group">
              <div class="col-md-2"></div>
                    <div class="col-md-5">
                         @if($technician->photo!='')
                            <img src="{{ asset($technician->path.$technician->photo) }}" alt="photo" width="200px">
                        @else
                            <img src="{{ asset('uploads/unnamed.png') }}" alt="photo" width="200px">
                        @endif
                    </div>
                
            </div>

            <!-- <br>
            <div class="row">
                    <label class="col-md-2 unicode">Address*</label>
                    <div class="col-md-5 {{ $errors->first('address', 'has-error') }}">
                       <textarea name="address" class="form-control" placeholder="Paug Long 4 street,Pyinmana">
                           {{$technician->township}}
                       </textarea>
                       
                    </div>
                
            </div> -->
            <br>   
            <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        <a class="btn btn-primary unicode" href="{{route('technician.show',$technician->id)}}"> Back</a>
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

<script>
    $(document).ready(function(){
    setTimeout(function(){
        $("div.alert").remove();
    }, 3000 );
       
    });
</script>
@stop