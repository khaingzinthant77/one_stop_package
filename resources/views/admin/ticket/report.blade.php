@extends('adminlte::page')

@section('title', 'Ticket Report')

@section('content_header')
    <h5 style="color: #009879;">Ticket Report</h5>
     <style type="text/css">
    </style>
@stop
@section('content')
    <?php
        $keyword = isset($_GET['keyword'])?$_GET['keyword']:'';
        $team_id = isset($_GET['team_id'])?$_GET['team_id']:'';
        $from_date = isset($_GET['from_date'])?$_GET['from_date']:'';
        $to_date = isset($_GET['to_date'])?$_GET['to_date']:'';
    ?> 
    <div class="page_body">
       <form action="{{ route('ticket_report') }}" method="get" accept-charset="utf-8" class="form-horizontal" id="filter">
            <div class="row form-group">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" name="keyword" id="keyword" value="{{ old('keyword',$keyword) }}" class="form-control" placeholder="Search...">
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="team_id" name="team_id">
                                <option value="">Select Team</option>
                                @foreach(get_teams() as $key=>$value)
                                <option value="{{$value->id}}" {{$value->id == $team_id ? 'selected' : ''}}>{{$value->group_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="from_date" id="from_date" class="form-control unicode" placeholder="From Date" value="{{ old('from_date',$from_date) }}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="to_date" id="to_date" class="form-control unicode" placeholder="To Date" value="{{ old('to_date',$to_date) }}">
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <a class="btn btn-warning" id="export_btn"><i class="fas fa-file">Export</i></a>
             </div>
            </div>
        </form>
       <form id="excel_form" action="{{ route('service_report_export') }}"  method="POST" class="unicode">
            @csrf
            @method('post')
            <input type="hidden" id="team_id" name="team_id" value="{{ $team_id }}">
            <input type="hidden" id="from_date" name="from_date" value="{{ $from_date }}">
            <input type="hidden" id="to_date" name="to_date" value="{{ $to_date }}">
         </form>

        <div class="table-responsive">
            <table class="table table-bordered styled-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Phone No</th>
                        <th>Issue Type</th>
                        <th>Team</th>
                        <th>Sub Total</th>
                        <th>Install Charge</th>
                        <th>Cloud Service</th>
                        <th>Service Charge</th>
                        <th>One Call Charge</th>
                        <th>Discount</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($amounts as $amount)
                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{$amount->name}}</td>
                        <td>{{$amount->phone_no}}</td>
                        <td>{{$amount->issue_type}}</td>
                       
                        <td>{{get_team_member($amount->ticket_id)}}</td>
                        <td style="text-align:right;">{{number_format($amount->sub_total)}}</td>
                        <td style="text-align:right;">{{number_format($amount->install_charge)}}</td>
                        <td style="text-align:right;">{{number_format($amount->cloud_charge)}}</td>
                        <td style="text-align:right;">{{number_format($amount->service_charge)}}</td>
                        <td style="text-align:right;">{{number_format($amount->on_call_charge)}}</td>
                        <td style="text-align:right;">{{number_format($amount->discount)}}</td>
                        <td style="text-align:right;">{{number_format($amount->total_amt)}}</td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <?php 
                            $sub_total = 0;
                            $install_charge = 0;
                            $cloud_charge = 0;
                            $service_charge = 0;
                            $on_call_charge = 0;
                            $discount = 0;
                            $total_amount = 0;

                            foreach ($all_amount as $key => $value) {
                                $sub_total += $value->sub_total;
                                $install_charge += $value->install_charge;
                                $cloud_charge += $value->cloud_charge;
                                $service_charge += $value->service_charge;
                                $on_call_charge += $value->on_call_charge;
                                $discount += $value->discount;
                                $total_amount += $value->total_amt;
                            }
                         ?>
                        <td colspan="5" style="text-align:right;">Total Amount</td>
                        <td style="text-align:right;">{{number_format($sub_total)}}</td>
                        <td style="text-align:right;">{{number_format($install_charge)}}</td>
                        <td style="text-align:right;">{{number_format($cloud_charge)}}</td>
                        <td style="text-align:right;">{{number_format($service_charge)}}</td>
                        <td style="text-align:right;">{{number_format($on_call_charge)}}</td>
                        <td style="text-align:right;">{{number_format($discount)}}</td>
                        <td style="text-align:right;">{{number_format($total_amount)}}</td>
                    </tr>
                </tfoot>
            </table>
            <div align="center">
                <p>Total -{{$count}}</p>
          </div>
       </div>
       {{ $amounts->appends(request()->input())->links()}}
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
        $('#keyword').change(function(){
            $(this).form.submit();
        });

       const selectBox = document.getElementById('team_id');
          selectBox.addEventListener('change', (event) => {
            const form = document.getElementById('filter');
            form.submit();
        });

   

         $(document).ready(function(){

            $(function () {
                $("#from_date").datepicker({ format: 'dd-mm-yyyy' });
                $("#to_date").datepicker({ format: 'dd-mm-yyyy' });
            });

            $(function() {
                    // $('#from_date').on('change',function(e) {
                    //     this.form.submit();
                    // }); 
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