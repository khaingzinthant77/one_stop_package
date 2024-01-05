 @extends('adminlte::page')

@section('title', 'Ticket')

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
        <form action="{{route('ticket.store')}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="row">
                <label class="col-md-2 unicode">Customer Type*</label>
                <div class="col-md-2 row {{ $errors->first('name', 'has-error') }} form-group">
                     <input type="radio" name="c_type" id="c_type1" style="margin-top:2px;margin-right: 10px;" value="1" checked> 
                     <label>Linn Customer</label>  
                </div>
                <div class="col-md-5 row {{ $errors->first('name', 'has-error') }}">
                     <input type="radio" name="c_type" id="c_type2" style="margin-top:2px;margin-right: 10px;" value="2">   
                     <label>Others Customer</label>  
                </div> 
            </div>
            <div class="row form-group" id="linn_customer">
               
                    <label class="col-md-2 unicode">Customer Name*</label>
                    <div class="col-md-5 {{ $errors->first('name', 'has-error') }}">
                        
                        <select class="livesearch form-control" name="cust_id"></select>
                     
                    </div>    
               
            </div>
            
            <div id="other_customer">
                <div class="row form-group">
               
                    <label class="col-md-2 unicode">Customer Name*</label>
                    <div class="col-md-5 {{ $errors->first('name', 'has-error') }}">
                      <input type="text" name="c_name" class="form-control">
                    </div>    
               
                </div>
                

                <div class="row form-group">
                    <label class="col-md-2 unicode">Phone Number*</label>
                    <div class="col-md-5 {{ $errors->first('phone_no', 'has-error') }}">
                      <input type="number" name="phone_no" class="form-control">
                    </div>    
               
                </div>
                
                <div class="row form-group">
                    <label class="col-md-2 unicode">Township*</label>
                    <div class="col-md-5">
                    <select class="form-control " id="tsh_id" name="tsh_id">
                        <option value="">All</option>
                        @foreach($townships as $key=>$township)
                        <option value="{{$township->id}}">{{$township->town_name}}</option>
                        @endforeach
                    </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-2 unicode">Address*</label>
                    <div class="col-md-5">
                        <textarea class="form-control" id="address" name="address"></textarea>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="col-md-2 unicode">Latitude*</label>
                    <div class="col-md-5 {{ $errors->first('lat', 'has-error') }}">
                      <input type="text" name="lat" class="form-control">
                    </div>    
               
                </div>
                
                <div class="row form-group">
                    <label class="col-md-2 unicode">Longitude*</label>
                    <div class="col-md-5 {{ $errors->first('lng', 'has-error') }}">
                      <input type="text" name="lng" class="form-control">
                    </div>    
               
                </div>
                
            </div>
            <div class="row form-group">
               
                    <label class="col-md-2 unicode">Assign Team</label>
                    <div class="col-md-5 {{ $errors->first('team_id', 'has-error') }}">
                        
                       <select class="form-control" name="team_id" id="team_id">
                            <option value="">Select Teams</option>
                            @foreach($groups as $group)
                            <option value="{{$group->id}}">{{$group->group_name}}</option>
                            @endforeach
                        </select>
                     
                    </div>    
               
            </div>
            
               
            
            <div class="row form-group">
                <label class="col-md-2 unicode">Assign Date</label>
                <div class="col-md-5">
                    <input type="text" name="assign_date" id="assign_date" class="form-control unicode" placeholder="01-08-2020" value="{{ old('assign_date',date('d-m-Y')) }}">
                </div>
            </div>
           
            <div class="row form-group">
                <label class="col-md-2 unicode">Ticket Issue*</label>
                    <div class="col-md-5">
                        <!-- ticket_issues -->
                        <!-- <input type="text" name="title" id="title" class="form-control"> -->
                        <select class="form-control" id="ticket_issue" name="ticket_issue">
                            <option value="">All</option>
                            @foreach($ticket_issues as $key=>$ticket_issue)
                            <option value="{{$ticket_issue->id}}">{{$ticket_issue->issue_type}}</option>
                            @endforeach
                        </select>
                    </div>
                
            </div>
            
            <div class="row form-group">
                    <label class="col-md-2 unicode">Description*</label>
                    <div class="col-md-5 {{ $errors->first('description', 'has-error') }}">
                       <textarea name="description" class="form-control" placeholder=""></textarea>
                    </div>
                
            </div>
            
            <div class="row form-group">
                    <div class="col-md-2"></div>
                    <div class="col-md-5">
                        <a class="btn btn-primary unicode" href="{{route('ticket.index')}}" style="width:60px;height: 38px;">Back</a>
                        <button type="submit" class="btn btn-success unicode">Save</button>
                    </div>
            </div>

        </form>
        <input type="hidden" id="ctr_token" value="{{ csrf_token()}}">
    </div>
</div>
@stop



@section('css')
<link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }}"/>

<link id="bsdp-css" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">

   <style type="text/css" media="screen">
      .error_msg{
        color: #DD4B39;
      }
      .has-error input{
        border-color: #DD4B39;
      }
      .select2-container .select2-selection--single {
        box-sizing: border-box;
        cursor: pointer;
        display: block;
        height: 35px;
        user-select: none;
        -webkit-user-select: none; }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 30px;
        position: absolute;
        top: 2px;
        right: 1px;
        width: 20px; 
    }
  </style>
   
@stop



@section('js')
<script type="text/javascript" src="{{ asset('select2/js/select2.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('jquery-ui.js') }}"></script>

 <script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>

<script type="text/javascript">
    
    $(function () {
            $("#assign_date").datepicker({ format: 'dd-mm-yyyy' });
        });

    $("document").ready(function(){
            setTimeout(function(){
                $("div.alert-success").remove();
            }, 3000 ); // 3 secs
        });

    $("#other_customer").hide();

    $(function(){
        $('input[type=radio][name=c_type]').change(function() {
            if (this.id == 'c_type1') {
                $("#other_customer").hide();
                $("#linn_customer").show();
            }
            else if (this.id == 'c_type2') {
                $("#other_customer").show();
                $("#linn_customer").hide();
            }
        });
    });
    

    
         $(function() {
            $('.livesearch').select2({
            placeholder: 'Customer Name',
            ajax: {
                url: "<?php echo(route("customer_search")) ?>",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name+' ('+item.phone_no+')',
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
        });
    
   

</script>
@stop