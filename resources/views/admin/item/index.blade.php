@extends('adminlte::page')

@section('title', 'Product')

@section('content_header')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
    <h5 style="color: #009879">Product List</h5>
    <style type="text/css">
        
    </style>
@stop

@section('content')

       <?php
        $item_name = isset($_GET['item_name'])?$_GET['item_name']:'';  
        $cat_id = isset($_GET['cat_id'])?$_GET['cat_id']:''; 
        $brand_id = isset($_GET['brand_id'])?$_GET['brand_id']:''; 
        ?>

        <form action="{{route('item.index')}}" method="get" accept-charset="utf-8" class="form-horizontal">
            <div class="row">
                        <div class="col-md-2">
                           
                            <input type="text" name="item_name" id="item_name" value="{{ old('item_name',$item_name) }}" class="form-control" placeholder="Search...">
                        </div>
                        <div class="col-md-2">
                            
                            <select class="form-control" id="cat_id" name="cat_id">
                                <option value="">Select Category</option>
                                @foreach($category as $categorys)
                                <option value="{{$categorys->id}}" {{ (old('cat_id',$cat_id)==$categorys->id)?'selected':'' }}>{{$categorys->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                          
                            <select class="form-control" id="brand_id" name="brand_id">
                                <option value="">Select Brand</option>
                                @foreach($brand as $brands)
                                <option value="{{$brands->id}}" {{ (old('brand_id',$brand_id)==$brands->id)?'selected':'' }}>{{$brands->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                             <a class="btn btn-success unicode" href="{{route('item.create')}}" style="float: right;"><i class="fas fa-plus"> Product</i></a>
                        </div>
               
            </div>
        </form><br>
           
                <form class="form-horizontal" action="{{route('import')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row form-group">
                        <div class="col-md-3">
                            <input type="file" name="file" class="form-control">
                            @if ($errors->has('file'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('file') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-3">
                        <button class="btn btn-success btn-sm"><i class="fas fa-file-csv" style="padding-left: 6px;padding-right: 6px;padding-top: 6px;padding-bottom: 6px"></i> Import CSV</button>
                        </div>
                       <div class="col-md-5"></div>
                       <div class="col-md-1">
                        <a class="btn btn-warning btn-sm" id="export_btn" style="float: right;" ><i class="fa fa-fw fa-file-excel"></i>Export</a>
                       </div>
                       
                    
                    </div>
                </form>
            
           
             <form id="excel_form" action="{{ route('export') }}"  method="POST">
                @csrf
                @method('post')
                <input type="hidden" id="brand_id" name="brand_id" value="{{ $brand_id }}">
                    <input type="hidden" id="cat_id" name="cat_id" value="{{ $cat_id }}">
                </form>
      
      
     {{-- @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif --}}
    
    <div class="table-responsive">
        <table class="table table-bordered styled-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Category Name</th>
                <th>Brand Name</th>
                <th>Model</th>
                <!-- <th>Product Name</th> -->
                <!-- <th>Serial_No</th> -->
                
                <th>Unit</th>
                <th style="text-align: center;">Price</th>
                <th style="text-align: center;">Total Qty</th>
                <th>Use Qty</th>
                <th>Left Qty</th>
            </tr>
        </thead>
        
            @if($item->count()>0)
            @foreach($item as $items) 
            <tr class="table-tr" data-url="{{route('item.show',$items->id)}}">

                <td>{{++$i}}</td>
                <td>{{$items->viewCategory->name}}</td>
                <td>{{$items->viewBrand->name}}</td>
                <td>{{$items->model}}</td>
                <td>{{$items->unit}}</td>
                <td style="text-align: right;">{{$items->price}}</td>
                <td>{{$items->qty}}</td>
                <td>0</td>
                <td>{{$items->qty}}</td>
            </tr>
             @endforeach
           
                  @else
                    <tr align="center">
                      <td colspan="10">No Data!</td>
                    </tr>
                  @endif
        </table>
        <div align="center">
                <p>Total - {{$count}}</p>
          </div>
     {!! $item->appends(request()->input())->links() !!}
  </div>


@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> 
         @if(Session::has('success'))
            toastr.options =
            {
            "closeButton" : true,
            "progressBar" : true
            }
            toastr.success("{{ session('success') }}");
        @endif
        $(document).ready(function(){
            setTimeout(function(){
            $("div.alert").remove();
            }, 1000 ); 
            $(function() {
                $('#item_name').on('change',function(e) {
                this.form.submit();
               // $( "#form_id" )[0].submit();   
            }); 
                $('#cat_id').on('change',function(e){
                this.form.submit();
              });
                 $('#brand_id').on('change',function(e){
                this.form.submit();
              });
        });
         $(function() {
          $('table').on("click", "tr.table-tr", function() {
            window.location = $(this).data("url");
          });
        });

         $('#export_btn').click(function(){
                $('#excel_form').submit();
            });
         
        });
     </script>
@stop