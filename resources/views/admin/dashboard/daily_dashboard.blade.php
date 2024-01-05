@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Daily Dashboard</h1>

@stop

@section('content')
<?php
    $from_date = isset($_GET['from_date'])?$_GET['from_date']:date('d-m-Y');
    $to_date = isset($_GET['to_date'])?$_GET['to_date']:date('d-m-Y');
?>

<form action="{{ route('daily_dashboard') }}" method="get" accept-charset="utf-8" class="form-horizontal">
    <div class="col-md-2" style="float:right;">
     
        <input type="text" name="to_date" id="to_date" class="form-control unicode" placeholder="{{date('d-m-Y')}}" value="{{ old('to_date',$to_date) }}">
    </div>

     <div class="col-md-2" style="float:right;">
     
        <input type="text" name="from_date" id="from_date" class="form-control unicode" placeholder="{{date('d-m-Y')}}" value="{{ old('from_date',$from_date) }}">
    </div>
    
</form><br>
<h6>Solved by teams</h6>
    
    <div class="row">
                <div class="col-md-3">
                    <div class="card text-white" style="background-color: {{$bg_color}}">
                        <a href="{{url('/customer?tsh_id=&team_id=&from_date='.$from_date.'&to_date='.$to_date.'&survey_type=1')}}" class="text-white" target="_blank">
                        <div class="card-body">
                            <div class="card__icon"><i class="fas fa-users"></i>&nbsp;New</div>
                            <br/>
                            <div class="row">
                                <div class="col-md-5"></div>
                                <div class="col-md-6" ><h3>{{$new_count}}</h3></div>
                            </div>
                            <br>
                        </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white" style="background-color: {{$bg_color}}">
                        <a href="{{url('/ticket?is_solve=1&team_id=&from_date='.$from_date.'&to_date='.$to_date.'&tsh_id=&issue_id=')}}" class="text-white" target="_blank">
                        <div class="card-body">
                            <div class="card__icon"><i class="fas fa-tools"></i>&nbsp;Service</div>
                            <br/>
                            <div class="row">
                                <div class="col-md-5"></div>
                                <div class="col-md-6" ><h3>{{$service_count}}</h3></div>
                            </div>
                            <br>
                        </div>
                        </a>
                    </div>
                </div>
               
                <!-- <div class="col-md-3">
                    <div class="card text-white" style="background-color: {{$bg_color}}">
                        <a href="" class="text-white">
                        <div class="card-body">
                            <div class="card__icon"><i class="fas fa-users"></i>&nbsp;Total</div>
                            <br/>
                            <div class="row">
                                <div class="col-md-5"></div>
                                <div class="col-md-6" ><h3>{{$amt}}</h3></div>
                            </div>
                            <br>
                        </div>
                        </a>
                    </div>
                </div> -->
          
        </div>

    <hr>

      <div class="table-responsive">
        <h6>Team Report</h6>
      <table class="table table-bordered styled-table">
          <thead>
          <tr>
              <th style="width: 25px">No</th>
              <th style="text-align:center;">Team</th>
              <th style="text-align:center;">New Installation</th>
              <th style="text-align:center;">Service</th>
              <th style="text-align:center;">Service Charge</th>
          </tr>
      </thead>
      <tbody>
        @if($teams->count()>0)
         @foreach($teams as $key=>$team)
  
            <tr class="table-tr" >
                <td style="width: 25px">{{++$key}}</td>
               <td style="text-align:center;">{{$team->group_name}}</td>
               <td style="text-align:right;">{{$team->new_install_count}}</td>
               <td style="text-align:right;">{{$team->service_count}}</td>
               <td style="text-align:right;">{{number_format($team->service_charge)}}</td>
            </tr>
         @endforeach
           @else
            <tr align="center">
              <td colspan="10">No Data!</td>
            </tr>
          @endif
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"></td>

          <td style="text-align: right;">
            <?php 
              $new_total = 0;
              foreach ($teams as $key => $team) {
                $new_total += $team->new_install_count;
              }
             ?>
             {{$new_total}}
          </td>
          <td style="text-align: right;">
            <?php 
              $service_total = 0;
              foreach ($teams as $key => $team) {
                $service_total += $team->service_count;
              }
             ?>
             {{$service_total}}
          </td>
          <td style="text-align: right;">
            <?php 
              $charge_total = 0;
              foreach ($teams as $key => $team) {
                $charge_total += $team->service_charge;
              }
             ?>
             {{number_format($charge_total)}}
          </td>
        </tr>
      </tfoot>
      
      </table>
     
 </div>
<hr>
 <div class="table-responsive">
  <h6>Category Report</h6>
      <table class="table table-bordered styled-table">
          <thead>
          <tr>
              <th style="width: 25px">No</th>
              <th style="text-align:center">Category</th>
              <th style="text-align:center">Qty</th>
          </tr>
      </thead>
      <tbody>
        @if($category_list->count()>0)
         @foreach($category_list as $key=>$list)
            <tr class="table-tr" >
                <td>{{++$key}}</td>
               <td style="text-align:center">{{$list->name}}</td>
               <td style="text-align:right;">{{$list->qty}}</td>
            </tr>
         @endforeach
           @else
            <tr align="center">
              <td colspan="10">No Data!</td>
            </tr>
          @endif
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2"></td>
          <td style="text-align:right;">
            <?php 
              $total_qty = 0;
              foreach ($category_list as $key => $list) {
                $total_qty += $list->qty;
              }
             ?>
             {{$total_qty}}
          </td>
        </tr>
      </tfoot>
      
      </table>

 </div>
<hr>
<h6>Category by Team Report</h6>
<div class="row">
   @foreach($team_list as $key=>$list)
      
     <div class="table-responsive col-md-4">
      <h6>{{$list->group_name}}</h6>
      <table class="table table-bordered styled-table">
          <thead>
          <tr>
              <th style="width: 25px">No</th>
              <th style="text-align:center;">Category</th>
              <th style="text-align:right;">Qty</th>
          </tr>
      </thead>
      <tbody>
        @if($list->cat_list->count()>0)
         @foreach($list->cat_list as $key=>$c_list)

            <tr class="table-tr" >
                <td>{{++$key}}</td>
               <td style="text-align:center;">{{$c_list->name}}</td>
               <td style="text-align:right;">{{$c_list->qty}}</td>
            </tr>
         @endforeach
           @else
            <tr align="center">
              <td colspan="10">No Data!</td>
            </tr>
          @endif
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2">Total</td>
          <td style="text-align:right;">
            <?php 
              $total_qty = 0;
              foreach ($list->cat_list as $key => $list) {
                $total_qty += $list->qty;
              }
             ?>
             {{$total_qty}}
          </td>
        </tr>
      </tfoot>
      
      </table>

 </div>
  @endforeach
</div>


    

@stop

@section('css')
<link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">
<link id="bsdp-css" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">

    <style type="text/css">
        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }

   

        .main-container {
          padding: 30px;
        }

        /* HEADING */

        .heading {
          text-align: center;
        }

        .heading__title {
          font-weight: 200;
        }

        .heading__credits {
          margin: 10px 0px;
          color: #888888;
          font-size: 25px;
          transition: all 0.5s;
        }

        .heading__link {
          text-decoration: none;
        }

        .heading__credits .heading__link {
          color: inherit;
        }

        /* CARDS */

        .cards {
          display: flex;
          flex-wrap: wrap;
          justify-content: space-between;
        }

        

        .tech_card{
              margin: 5px;
              padding: 10px;
              width: 330px;
              /*height: 160px;*/
              /*min-height: 100px;*/
             /* display: grid;
              grid-template-rows: 20px 50px 1fr 50px;*/
              border-radius: 10px;
              box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.25);
              transition: all 0.2s;
        }

        .tech_card:hover {
          box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.4);
          transform: scale(1.01);
        }

        .card:hover {
          box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.4);
          transform: scale(1.01);
        }

        .card__link,
        .card__exit,
        .card__icon {
          position: relative;
          text-decoration: none;
          color: rgba(255, 255, 255, 0.9);
        }

        .card__link::after {
          position: absolute;
          top: 25px;
          left: 0;
          content: "";
          width: 0%;
          height: 3px;
          background-color: rgba(255, 255, 255, 0.6);
          transition: all 0.5s;
        }

        .card__link:hover::after {
          width: 100%;
        }

        .card__exit {
          grid-row: 1/2;
          justify-self: end;
        }

        .card__icon {
          grid-row: 2/3;
          font-size: 20px;
        }

        .card__title {
          grid-row: 3/4;
          font-weight: 400;
          color: #ffffff;
        }

        .card__apply {
         
          align-self: center;
        }

        /* CARD BACKGROUNDS */

        

        .card-3 {
          background: radial-gradient(#76b2fe, #b69efe);
        }

        
        /* RESPONSIVE */

        @media (max-width: 1600px) {
          .cards {
            justify-content: center;
          }
        }
    </style>
@stop

@section('js')
<script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
    <script>
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
    </script>
@stop