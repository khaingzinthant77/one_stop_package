@extends('adminlte::page')

@section('title', 'One Stock Package')

@section('content_header')
    <h5 style="color: #009879;">One Stock Package Dashboard</h5>
    <style type="text/css">
    </style>
@stop

@section('content')
<?php
    $from_date = isset($_GET['from_date'])?$_GET['from_date']:'';
    $to_date = isset($_GET['to_date'])?$_GET['to_date']:'';
?>

<div class="container-fluid">
    <div class="card">
        <form action="" method="get" accept-charset="utf-8" class="form-horizontal unicode">
            <div class="d-flex justify-content-end" style="margin-top: 20px;">
                <div class="row col-md-6" style="margin-right: 10px;">
                    <div class="col-md-5">
                        <input type="text" name="from_date" id="from_date" class="form-control unicode" placeholder="From Date" value="{{ old('from_date',$from_date) }}" autocomplete="off">
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="to_date" id="to_date" class="form-control unicode" placeholder="To Date" value="{{ old('to_date',$to_date) }}" autocomplete="off">
                    </div>
                    <div class="col-md-2" style="margin-top: 1px;">
                        <button type="submit" class="btn btn-primary" >Search</button>
                    </div>
                </div>
            </div>
        </form>
          <div class="card-body">
             <h6>Customer Count by Township</h6>
            <div class="row mt-3 col-md-12">
               @foreach(get_townsips() as $township)
                <div class="col-md-2 mydiv" onclick="window.open('{{ url('one_stock_package_customers?tsh_id='.$township->id) }}', '_blank');">
                    <div class="card bg-light p-3  shadow-md" style="border: 1px solid #4d8fc6">
                        <div class="d-flex">
                            <div class="col-md-3">
                                <i class="fas fa-city" style="color:#4d8fc6"></i>
                            </div>
                            <div class="col-md-9">
                                <div class="text text-primary mb-2">{{$township->town_name}}</div>
                                <div  style="color:#4d8fc6">{{getCustomerCount($township->id,$from_date,$to_date)}}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <hr>
            <h6>Customer Count by Install Type</h6>
            <div class="row mt-3 col-md-12">
               @foreach($types as $type)
                <div class="col-md-4 mydiv" onclick="window.open('{{ url('one_stock_package_customers?loc_id='.$type) }}', '_blank');">
                    <div class="card  p-3  shadow-md" style="border: 1px solid #4d8fc6">
                        <div class="d-flex">
                            <div class="col-md-2">
                                @if($type == 'home')
                                <i class="fas fa-home" style="color:#4d8fc6;font-size: 30px;"></i>
                                @elseif($type == 'shop')
                                <i class="fas fa-store" style="color:#3FC9C1;font-size: 30px;"></i>
                                @else
                                <i class="fas fa-hotel" style="color:#814786;font-size: 30px;"></i>
                                @endif
                            </div>
                            <div class="col-md-9">
                                @if($type == 'home')
                                <div class="text mb-2" style="color:#4d8fc6;font-weight: bold;">{{Str::upper($type)}}</div>
                                <div  style="color:#4d8fc6">{{typeByCount($type,$from_date,$to_date)}}</div>
                                @elseif($type == 'shop')
                                <div class="text mb-2" style="color:#3FC9C1;font-weight: bold;">{{Str::upper($type)}}</div>
                                <div  style="color:#3FC9C1">{{typeByCount($type,$from_date,$to_date)}}</div>
                                @else
                                <div class="text mb-2" style="color:#814786;font-weight: bold;">{{Str::upper($type)}}</div>
                                <div  style="color:#814786">{{typeByCount($type,$from_date,$to_date)}}</div>
                                @endif

                                
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <hr>
          <h6>Customer Count by Package</h6>
            <div class="row mt-3 col-md-12">
               @foreach(package_lists() as $package)
                <div class="col-md-3">
                    <div class="card bg-light p-3  shadow-md" style="border: 1px solid #4d8fc6">
                        <div style="text-align: center;margin-bottom: 10px;">
                            <div class="text text-primary" style="font-weight: bold;">{{$package}}</div>
                        </div>
                        <div class="d-flex justify-content-between" style="text-align:center;">
                            <div class="col-md-4 d-flex">
                                <div>
                                    <i class="fas fa-home" style="color:#4d8fc6;font-size: 20px;margin-right: 10px;"></i>
                                    <div style="font-size:12px;">Home</div>
                                </div>

                                <div class="mydiv" onclick="window.open('{{ url('one_stock_package_customers?loc_id=home&package_id='.$package) }}', '_blank');">
                                    {{getPackageCount($package,'home',$from_date,$to_date)}}
                                </div>
                            </div>
                            <div class="col-md-4 d-flex">
                                <div>
                                    <i class="fas fa-store" style="color:#3FC9C1;font-size: 20px;;margin-right: 10px;"></i>
                                    <div style="font-size:12px;">Shop</div>
                                </div>
                                <div class="mydiv" onclick="window.open('{{ url('one_stock_package_customers?loc_id=shop&package_id='.$package) }}', '_blank');">
                                    {{getPackageCount($package,'shop',$from_date,$to_date)}}
                                </div>
                            </div>
                            <div class="col-md-4 d-flex">
                                <div>
                                    <i class="fas fa-hotel" style="color:#814786;font-size: 20px;;margin-right: 10px;"></i>
                                    <div style="font-size:12px;">Office</div>
                                </div>
                                
                                <div class="mydiv" onclick="window.open('{{ url('one_stock_package_customers?loc_id=office&package_id='.$package) }}', '_blank');">
                                    {{getPackageCount($package,'office',$from_date,$to_date)}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <hr>
            <div id="barchart_material" style="width: 900px; height: 500px;"></div>
          </div>

          </div>
    </div>
</div>
@stop

@section('css')
<link id="bsdp-css" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
<style type="text/css">
    .mydiv:hover {
        cursor: pointer; /* Change cursor to pointer on hover */
    }
</style>
@stop

@section('js')
<script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    $(function () {
        $("#from_date").datepicker({ format: 'dd-mm-yyyy' });
        $("#to_date").datepicker({ format: 'dd-mm-yyyy' });
    });
    google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable({!! json_encode($result) !!});

        var options = {
          chart: {
            title: 'Package',
            bar: {groupWidth: "15%"},
            legend: { position: "none" },
          },
        };

        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
</script>
@stop