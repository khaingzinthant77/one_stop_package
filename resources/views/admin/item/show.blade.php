@extends('adminlte::page')

@section('title', 'Show Product')

@section('content_header')
   <link rel="stylesheet" type="text/css" href="{{asset('jquery-ui.css')}}">
@stop

@section('content')

 <form action="{{route('item.destroy',$item->id)}}" method="POST" onsubmit="return confirm('Do you really want to delete?');">
     @csrf
    @method('DELETE')
       <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-success unicode" href="{{route('item.index')}}"> Back</a>
                </div>
                <div class="col-md-6">
                    
                    <div class="row" style="float: right;">
                        <div>
                            <a class="btn btn-sm btn-secondary" href="" style="margin-right: 10px"><i class="fa fa-fw fa-eye" style="padding-right: 5px;padding-top: 5px;padding-bottom: 5px;padding-left: 2px" /></i></a> 
                        </div>
                        <div>
                            <a class="btn btn-sm btn-primary" href="{{route('item.edit',$item->id)}}"><i class="fa fa-fw fa-edit" style="padding-left: 2px;padding-right: 5px;padding-top: 5px;padding-bottom: 5px" /></i></a> 
                        </div>
                        <div>
                           <button type="submit" class="btn btn-sm btn-danger" style="margin-left: 10px"><i class="fa fa-fw fa-trash" /></i></button> 
                    </div>
                
                </div>
                </div>
                
            </div>
    </form><br>

          <div class="row" style="margin-top: 10px">
              <div class="col-md-6">
             <div class="row">
                    <label class="col-md-3 unicode">Category Name</label>
                    <div class="col-md-9">
                        <input type="text" name="price" id="price" value="{{$item->viewCategory->name}}" class="form-control unicode" readonly>
                    </div>
              </div><br>

              <div class="row">
                  <label class="col-md-3">Brand Name</label>
                  <div class="col-md-9">
                    <input type="text" name="price" id="price" value="{{$item->viewBrand->name}}" class="form-control unicode" readonly>
                  </div>
              </div><br>

              <div class="row">
                  <label class="col-md-3">Model</label>
                  <div class="col-md-9">
                     <input type="text" name="model" id="model" value="{{$item->model}}" class="form-control unicode" placeholder="Enter brand name" readonly>
                  </div>
              </div><br>

               <div class="row">
                  <label class="col-md-3">Item Code</label>
                  <div class="col-md-9">
                     <input type="text" name="item_code" id="item_code" readonly class="form-control unicode" value="{{$item->item_code}}">
                  </div>
              </div><br>

              <div class="row">
                  <label class="col-md-3">Price</label>
                  <div class="col-md-9">
                    <input type="text" name="price" id="price" value="{{$item->price}}" class="form-control unicode" readonly>
                  </div>
              </div><br>

              <div class="row">
                <label class="col-md-3">Unit</label>
                <div class="col-md-9">
                   @if($item->unit == 0)
                  <input type="text" name="unit" id="unit" value="Unit" class="form-control unicode" readonly>
                   @else
                   <input type="text" name="unit" id="unit" value="Meter" class="form-control unicode" readonly>
                   @endif
                </div>
              </div><br>

              <div class="row">
                <label class="col-md-3">Remark</label>
                <div class="col-md-9">
                  <textarea name="remark" class="form-control" readonly id="remark" >{{$item->remark}}</textarea>
                </div>
              </div><br>

             
          </div>
          <div class="col-md-6">
             <div class="row">
                <label class="col-md-2">Photo</label>
                <div class="col-md-9">
                   @if($item->photo != null)
                           <img src="{{ asset('uploads/productPhoto/'.$item->photo) }}" alt="photo" width="200px" height="200px"> 
                  @else
                            <img src="{{asset('uploads/productPhoto/unnamed.png')}}" alt="photo" width="200px" height="200px">
                  @endif
                </div>
              </div><br>
          </div>

           
        </div>
@stop

@section('css')
@stop

@section('js')
    <script> 
    </script>
@stop