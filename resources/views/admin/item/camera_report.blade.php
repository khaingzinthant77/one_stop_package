@extends('adminlte::page')

@section('title', 'Category Report')

@section('content_header')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
    <h5 style="color: #009879">Category Report</h5>
    <style type="text/css">
        
    </style>
@stop

@section('content')

       <?php
            $from_date = isset($_GET['from_date'])?$_GET['from_date']:date('d-m-Y');  
            $to_date = isset($_GET['to_date'])?$_GET['to_date']:date('d-m-Y'); 
        ?>
    <form action="{{ route('camera_report') }}" method="get" accept-charset="utf-8" class="form-horizontal">
        <div class="col-md-2 form-group" style="float:right;">
         
            <input type="text" name="to_date" id="to_date" class="form-control unicode" placeholder="{{date('d-m-Y')}}" value="{{ old('to_date',$to_date) }}">
        </div>

         <div class="col-md-2" style="float:right;">
         
            <input type="text" name="from_date" id="from_date" class="form-control unicode" placeholder="{{date('d-m-Y')}}" value="{{ old('from_date',$from_date) }}">
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered styled-table">
            <thead>
            <tr>
                <th>No</th>
                <th>Team</th>
                <th>Category</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            @forelse($groups as $key=>$group)
            <tr>
                <td rowspan="{{ $group->items->count() + 1 }}">{{++$key}}</td>
                <td rowspan="{{ $group->items->count() + 1 }}">{{$group->group_name}}</td>
                @foreach($group->items as $item)
                <tr>
                    <td>{{$item->name}}</td>
                    <td>{{$item->total_count}}</td>
                </tr>
                @endforeach
            </tr>
            @empty
            @endforelse
        </tbody>
  </div>


@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">
<link id="bsdp-css" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
@stop

@section('js')
<script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
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

            $(function () {
                $("#from_date").datepicker({ format: 'dd-mm-yyyy' });
                $("#to_date").datepicker({ format: 'dd-mm-yyyy' });
            });

            $(function() {
                    $('#from_date').on('change',function(e) {
                        this.form.submit();
                    }); 
                    $('#to_date').on('change',function(e) {
                        this.form.submit();
                    }); 
                });


         $('#export_btn').click(function(){
                $('#excel_form').submit();
            });
         
        });
     </script>
@stop