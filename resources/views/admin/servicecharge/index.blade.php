@extends('adminlte::page')

@section('title', 'ServiceCharge')

@section('content_header')
    <h5 style="color: #009879;">ServiceCharge List</h5>
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

    .styled-table tbody tr:nth-of-type(even) {
        background-color: #c7d4dd;
    }

    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #009879;
    }
    </style>
@stop
@section('content')
    <?php
        $name = isset($_GET['name'])?$_GET['name']:'';

    ?>
        <form action="{{ route('servicecharge.index') }}" method="get" accept-charset="utf-8" class="form-horizontal">
            <div class="row">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="name" id="name" value="{{ old('name',$name) }}" class="form-control" placeholder="Search...">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-success unicode" href="{{route('servicecharge.create')}}"><i class="fas fa-plus"> Charge</i></a>
                </div>
            </div>
        </form>
    <div class="page_body">
      
        <br>

        <div class="table-responsive">
            <table class="table table-bordered styled-table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Price</th>
                    <!-- <th>Action</th> -->
                </tr>
                </thead>
            @if($servicecharge->count()>0)
             @foreach($servicecharge as $servicecharges)
                <tr class="table-tr" data-url="{{route('servicecharge.show',$servicecharges->id)}}">
                    <td>{{++$i}}</td>
                   
                   <td>{{$servicecharges->name}}</td>
                   <td>{{$servicecharges->price}}</td>
                  
                </tr>
             @endforeach
               @else
                <tr align="center">
                  <td colspan="10">No Data!</td>
                </tr>
              @endif
            </table>
            <div align="center">
                <p>Total -{{$count}}</p>
          </div>
       </div>
{{ $servicecharge->appends(request()->input())->links()}}
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

          @if(Session::has('error'))
          toastr.options =
          {
            "closeButton" : true,
            "progressBar" : true
          }
                toastr.error("{{ session('error') }}");
          @endif

        $(document).ready(function(){
            setTimeout(function(){
            $("div.alert").remove();
            }, 1000 ); 
            $(function() {
                $('#name').on('change',function(e) {
                this.form.submit();
               // $( "#form_id" )[0].submit();   
            }); 
        });
        $(function() {
          $('table').on("click", "tr.table-tr", function() {
            window.location = $(this).data("url");
          });
        });

        });
     </script>
@stop