@extends('adminlte::page')

@section('title', 'Township')

@section('content_header')
    <h5 style="color: #009879;">Township List</h5>
    <style type="text/css">
    </style>
@stop
@section('content')
    <?php
        $town_name = isset($_GET['town_name'])?$_GET['town_name']:'';

    ?>
        <form action="{{ route('township.index') }}" method="get" accept-charset="utf-8" class="form-horizontal">
            <div class="row">
                <div class="col-md-11">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="town_name" id="town_name" value="{{ old('town_name',$town_name) }}" class="form-control" placeholder="Search...">
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <a class="btn btn-success" href="{{route('township.create')}}"><i class="fas fa-plus">  Township</i></a>
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
                    <th>Township Name</th>
                    <th>Township Short Code</th>
                    <th>Price</th>
                    <!-- <th>Action</th> -->
                </tr>
            </thead>
            @if($township->count()>0)
             @foreach($township as $townships)
                <tr class="table-tr" data-url="{{route('township.show',$townships->id)}}">
                    <td>{{++$i}}</td>
                   
                   <td>{{$townships->town_name}}</td>
                   <td>{{$townships->townshort_name}}</td>
                   <td>{{$townships->price}}</td>
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
{{ $township->appends(request()->input())->links()}}
    </div>
@stop 

@section('css')

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
                $('#town_name').on('change',function(e) {
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