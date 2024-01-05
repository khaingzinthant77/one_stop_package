@extends('adminlte::page')

@section('title', 'Edit')

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
        <form action="{{route('item.update',$r_item->id)}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
        @csrf
        @method('PUT')

          <div class="row form-group">
                <div class="col-md-6">
                    <div class="row">
                       <label class="col-md-3 unicode">Category*</label>
                        <div class="col-md-9 {{ $errors->first('categorys', 'has-error') }}"> 
                           <select class="form-control livesearch" id="cat_id" name="cat_id">
                            <option value="">All</option>
                            @foreach($categories as $key=>$category)
                            <option value="{{$category->id}}" {{$category->id == $r_item->cat_id ? 'selected' : ''}}>{{$category->name}}</option>
                            @endforeach
                            </select>   
                        @if($errors->first('categorys'))
                            <span class="help-block">
                                <small>{{ $errors->first('categorys') }}</small>
                            </span>
                         @endif
                        </div>    
                        
                    </div>
                </div>

                 <div class="col-md-6">

                   <div class="row"> 
                       <label class="col-md-2 unicode">Unit*</label>
                    <div class="col-md-9 {{ $errors->first('unit', 'has-error') }}">
                      <select class="form-control {{ $errors->first('unit', 'has-error') }} label" name="unit">
                        <option value="">Select Unit</option>
                            <option value="0" {{$r_item->cat_id == 'unit' ? 'selected' : ''}}>Unit</option>
                             <option value="1" {{$r_item->cat_id == 'meter' ? 'selected' : ''}}>Meter</option>
                        </select>
                         @if($errors->first('unit'))
                            <span class="help-block">
                                <small>{{ $errors->first('unit') }}</small>
                            </span>
                         @endif
                    </div> 
                    </div>
                 
                </div>

            </div>

              <div class="row form-group">

                  <div class="col-md-6">
                    <div class="row"> 
                       <label class="col-md-3 unicode">Brand Name*</label>
                    <div class="col-md-9 {{ $errors->first('brands', 'has-error') }}">
                        <select class="form-control livesearch" id="brand_id" name="brand_id">
                            <option value="">All</option>
                            @foreach($brand as $key=>$b)
                            <option value="{{$b->id}}" {{$b->id == $r_item->brand_id ? 'selected' : ''}}>{{$b->name}}</option>
                            @endforeach
                        </select> 
                         @if($errors->first('brands'))
                            <span class="help-block">
                                <small>{{ $errors->first('brands') }}</small>
                            </span>
                         @endif
                    </div> 
                    </div>
                </div>

                 <div class="col-md-6">

                    <div class="row">
                        <label class="col-md-2 unicode">Photo</label>
                        <div class="col-md-9">
                        
                        <input type="file" name="photo" id="photo" class="form-control unicode">
                     
                    </div>
                    </div>
          
                </div>
            </div>  

                <div class="row form-group">
                  <div class="col-md-6">
                    <div class="row"> 
                       <label class="col-md-3 unicode">Model*</label>
                        <div class="col-md-9 ">
                            <input type="text" id="auto" name="model" id="model" value="{{ old('model',$r_item->model) }}" class="form-control unicode" placeholder="Enter Price Number">
                        </div> 
                    </div>
                </div>

                 <div class="col-md-6">

                    <div class="row form-group"> 
                       <label class="col-md-2 unicode">Price</label>
                    <div class="col-md-9 ">
                        <input type="number" name="price" id="price" value="{{ old('price',$r_item->price) }}" class="form-control unicode" placeholder="Enter Price Number">
                    </div> 
                    </div>
                
                </div>

              
            </div>

              <div class="row">

                  <div class="col-md-6">
                     <div class="row">
                   <label class="col-md-3 unicode">Item Code</label>
                        <div class="col-md-9">
                        
                        <input type="text" name="item_code" class="form-control unicode" placeholder="Item code" value="{{$r_item->item_code}}">
                     
                    </div>
                    </div>
                </div>

                 <div class="col-md-6">

                     <div class="row form-group"> 
                       <label class="col-md-2 unicode">Remark</label>
                    <div class="col-md-9">
                       <textarea name="remark" class="form-control" placeholder="Remark">{{$r_item->remark}}</textarea>
                    </div> 
                    </div>

                   <!--    <div class="row">
                   <label class="col-md-3 unicode">Is_SerialNo</label>
                        <div class="col-md-9">
                        
                        <input type="checkbox" name="is_serialno" id="is_serialno" value="{{ old('is_serialno') }}" >
                     
                    </div>
                    </div> -->
                   
                </div>

              
            </div>
            <div class="row form-group">
                <div class="col-md-6">
                     <div class="row">
                   <label class="col-md-3 unicode">Qty</label>
                        <div class="col-md-9">
                        
                        <input type="text" name="qty" class="form-control" placeholder="Qty" value="{{$r_item->qty}}">
                     
                    </div>
                    </div>
                </div>
            </div>

            <!--  <div class="row">
              
                <div class="col-md-6">
                     <div class="row">
                   <label class="col-md-3 unicode">Is_SerialNo</label>
                        <div class="col-md-9">
                        
                        <input type="checkbox" name="is_serialno" id="is_serialno" value="{{ old('is_serialno') }}" >
                     
                    </div>
                    </div>
                </div>
                
            </div> -->

           <!--  <div class="row">
                <div class="col-md-6">


                     <div class="row">
                        <label class="col-md-3 unicode" id="qty_label">Quantity</label>
                        <div class="col-md-9">
                        
                         <input type="text" name="qty" id="qty" value="{{ old('qty') }}" class="form-control unicode">

                         <input type="hidden" id="oldqty" value="{{ old('qty') }}" class="form-control unicode">
                     
                    </div>
                    </div>

                  </div>

            </div> -->

           <!--  <div class="row">
              <div class="col-md-6">
                     <div class="row">
                   <label class="col-md-3 unicode">Is_SerialNo</label>
                        <div class="col-md-9">
                        
                        <input type="checkbox" name="is_serialno" id="is_serialno" value="{{ old('is_serialno') }}" >
                     
                    </div>
                    </div>
                </div>
            </div> -->


            <div class="row">
              
                <div class="col-md-6">
                    <div class="row" id="dynamicTable">
                        <label class="col-md-3 unicode" id="serial_no_label" for="serial_no_label">Serial No</label>
                        <div class="col-md-9">
                        
                           <input type="text" name="addmore[0][serial_no]" id="serial_no" value="{{ old('serial_no') }}" class="form-control unicode scan_serial" placeholder="Enter Serial Number" autofocus>
                     
                    </div>
                </div>
                  <div class="col-md-6">
                  
                    </div>
                  </div>

            </div>

            <div class="row">
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
            // $(document).scannerDetection();
            // var i=1;
            // $(document).bind('scannerDetectionComplete',function(e,data){
              
            //   i++;

            //  $("#dynamicTable").append('<div class="col-md-12"><br><div class="row" id="rows"><label class="col-md-3 unicode"></label><div class="col-md-9"><div class="row"><div class="col-md-10"><input type="text"  name="addmore['+i+'][serial_no]" placeholder="Enter Serial Number" class="form-control unicode serial" autofocus id="thirdserial" onkeypress="return (event.char)"/></div><button class="ibtnDel btn btn-sm btn-danger"><i class="fas fa-times"></i></button></div></div></div><div>');
            //   $(".serial").focus();
            // }).bind('scannerDetectionError',function(e,data){
            //     console.log('detection error ');
            // })
            // .bind('scannerDetectionReceive',function(e,data){
            //     if(data.evt.which == 13){
            //         return false;
            //     }
            // })

       $(function () {
         var model_arr = {!! $modelArr !!};
            // console.log(model_arr);
            $( "#auto" ).autocomplete({
               source: model_arr
            });

        // Get the form fields and hidden div
          var checkbox = $("#is_serialno"); 
           $("#serial_no").hide();
           $("#serial_no_label").hide();
           

            var hidden = $("#unhide");
            hidden.hide();

            checkbox.change(function () {

            if (checkbox.is(':checked')) {
            $("#is_serialno").val("1");
            $("#serial_no").show();
            $("#serial_no_label").show();
            
            // $("#serial_no").focus();
            

              } else {
            $("#is_serialno").val(0);
            $("#serial_no").hide();
            $("#serial_no_label").hide();
            
              }

            });
          });

          $("#dynamicTable").on("click", ".ibtnDel", function(event) {
           $(this).closest("div").remove();
        });

      $(function() {
            $('#cat_id').select2({
            placeholder: 'Select Category',
            ajax: {
                url: "<?php echo(route("search_category")) ?>",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $('#brand_id').select2({
            placeholder: 'Select Category',
            ajax: {
                url: "<?php echo(route("search_brand")) ?>",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
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