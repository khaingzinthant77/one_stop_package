@extends('adminlte::page')

@section('title', 'Product')

@section('content_header')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
    <h5 style="color: blue">Serial Show Table</h5>
    <style type="text/css">
        tr:hover td {
        background: #c7d4dd !important;
   }
     tr {
        cursor: pointer;
    }
    .styled-table {
    border-collapse: collapse;
    /*margin: 25px 0;*/
    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 400px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }
    .styled-table thead tr {
        background-color: #009879;
        color: #ffffff;
        text-align: left;
    }
    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
    }

    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
    }

   /* .styled-table tbody tr:nth-of-type(even) {
        background-color: #c7d4dd;
    }*/

    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #009879;
    }
    </style>
@stop

@section('content')

     <?php
        $serial_no = isset($_GET['serial_no'])?$_GET['serial_no']:'';   
        ?>
   <div class="col-md-6">
                <a class="btn btn-success unicode" href="{{route('item.index')}}"> Back</a>
    </div><br>
     <form action="{{route('getserial',$item_id)}}" method="get" accept-charset="utf-8" class="form-horizontal">
    <div class="col-md-3">
      <input type="text" id="serial_no" value="{{ old('serial_no',$serial_no) }}" name="serial_no" class="form-control" placeholder="Search..." autofocus>
      </div>
  </form><br>
      {{-- @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif --}}

    <div class="row form-group">
        <div class="col-lg-12">
            <div>
                <h6 class="text-center text-dark text-md"><b>{{$item->viewCategory->name}}/{{$item->model}} 's Serial No</b></h6>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered styled-table">
            <thead>
            <tr>
                <th style="width: 20px">No</th>
                <th>Serial No</th>
                 <th>Create Date</th>
                <th style="width:150px;">Action</th>

            </tr>
        </thead>
        <?php
          $i=0;
        ?>
        
        <tbody>       
            @foreach($productserials as $productserial)
            <tr class="table-tr" style="{{ $productserial->status == 2 ? 'background-color: pink' : '' }}">
                <td>{{++$i}}</td>
                @if($productserial->status == 0)
                <td>
                 {{$productserial->serial_no}}(new)
                </td>
                @endif
                <!-- @if($productserial->status == 1)
                <td>
                  {{$productserial->serial_no}}(taken)
                </td>
                @endif -->
                @if($productserial->status == 1)
                <td>
                  {{$productserial->serial_no}}(use)
                </td>
                @endif
                @if($productserial->status == 4)
                <td>
                  {{$productserial->serial_no}}(return)
                </td>
                @endif
                <td>{{$productserial->created_at->format('d/m/Y')}}</td>
                @if($productserial->status != 2)
                <td>
                  <form action="{{ route('deleteserial',$productserial->id) }}" method="POST" onsubmit="return confirm('Do you really want to delete?');">
                    @csrf
                    @method('DELETE')
                    <a class="btn btn-sm btn-primary" href="{{route('editserial',$productserial->id)}}" ><i class="fa fa-fw fa-edit" style="padding-top: 5px;padding-bottom: 5px;padding-left: 2px;padding-right: 5px"/></i></a> 
                     <button type="submit" class="btn btn-sm btn-danger" style="margin-left: 10px"><i class="fa fa-fw fa-trash" /></i></button> 
                   </form>
                </td>
                @else
                <td> 
            
                </td>
                @endif
            </tr>
             @endforeach
            </tbody>
       
        </table>
         <div align="center">
                <p>Total - {{$count}}</p>
          </div>
       {!! $productserials->appends(request()->input())->links() !!}
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
            $("#serial_no").focus();
            setTimeout(function(){
            $("div.alert").remove();
            }, 1000 ); 
            $(function() {
                $('#serial_no').on('change',function(e) {
                this.form.submit();
               // $( "#form_id" )[0].submit();   
            }); 
        });  
        });
     </script>
@stop