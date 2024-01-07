@extends('adminlte::page')

@section('title', 'Customer')

@section('content_header')
    <h5 style="color: #009879;">Package Customer List</h5>
    <style type="text/css">
    </style>
@stop
@section('content')
    <?php
        $keyword = isset($_GET['keyword'])?$_GET['keyword']:'';
        $tsh_id = isset($_GET['tsh_id'])?$_GET['tsh_id']:'';
        $from_date = isset($_GET['from_date'])?$_GET['from_date']:'';
        $to_date = isset($_GET['to_date'])?$_GET['to_date']:'';
        $team_id = isset($_GET['team_id'])?$_GET['team_id']:'';
        $loc_id = isset($_GET['loc_id'])?$_GET['loc_id']:'';
        $package_id = isset($_GET['package_id'])?$_GET['package_id']:'';
    ?> 
    <form action="{{ route('customer.index') }}" method="get" accept-charset="utf-8" class="form-horizontal form-group">
        <div class="row">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-2">
                        <input type="text" name="keyword" id="keyword" value="{{ old('keyword',$keyword) }}" class="form-control" placeholder="Search...">
                         
                    </div>
                    <div class="col-md-2">
                          <button type="button" class="btn btn-info "  data-toggle="modal" data-target="#filterModal" style="font-size: 13px;margin-top: 4px;"><i class="fa fa-filter" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div>
                    <button type="button" class="btn btn-warning text-white" id="export_btn" style="font-size: 13px;margin-top: 4px;">Excel Export</i></button>
                </div>&nbsp;
                 <div>
                     <a href="{{route('package_create')}}" class="btn btn-success text-white" id="" style="font-size: 13px;margin-top: 4px;">Add New</i></a>
                 </div>
                 
           </div>
        </div>
    </form>
    <div id="filterModal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-md">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
             <h5 class="modal-title">More Filter</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
           
          </div>
          <div class="modal-body">
             <form action="" method="get" accept-charset="utf-8" class="form-horizontal unicode" >
                <div class="row form-group">
                    <div class="col-md-6">
                        <label for="">From Date</label>
                        <input type="text" name="from_date" id="from_date" class="form-control unicode" placeholder="{{date('d-m-Y')}}" value="{{ old('from_date',$from_date) }}" autocomplete="off">
                    </div>
                     <div class="col-md-6">
                        <label for="">To Date</label>
                        <input type="text" name="to_date" id="to_date" class="form-control unicode" placeholder="{{date('d-m-Y')}}" value="{{ old('to_date',$to_date) }}" autocomplete="off">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <label>Township</label>
                        <select class="form-control" id="tsh_id" name="tsh_id">
                            <option value="">Select Township</option>
                            @foreach(get_townsips() as $township)
                            <option value="{{$township->id}}">{{$township->town_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Created By</label>
                        <select class="form-control" id="team_id" name="team_id">
                            <option value="" {{$team_id == '' ? 'selected' : ''}}>Select Created By</option>
                            <option value="admin" {{$team_id == "admin" ? 'selected' : ''}}>Admin</option>
                            @foreach(get_teams() as $team)
                            <option value="{{$team->group_name}}" {{$team_id == $team->group_name ? 'selected' : ''}}>{{$team->group_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label>Install Location</label>
                        <select class="form-control" id="loc_id" name="loc_id">
                            <option value="">Select Location</option>
                            <option value="home" {{$loc_id == 'home' ? 'selected' : ''}}>Home</option>
                            <option value="shop" {{$loc_id == 'shop' ? 'selected' : ''}}>Shop</option>
                            <option value="office" {{$loc_id == 'office' ? 'selected' : ''}}>Office</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>Select Package</label>
                        <select class="form-control" id="package_id" name="package_id">
                            <option value="">Select Package</option>
                            <option value="CCTV" {{$package_id == 'CCTV' ? 'selected' : ''}}>CCTV</option>
                            <option value="Smart Home" {{$package_id == 'Smart Home' ? 'selected' : ''}}>Smart Home</option>
                            <option value="mm-link Wifi" {{$package_id == 'mm-link Wifi' ? 'selected' : ''}}>mm-link Wifi</option>
                            <option value="Fiber Internet" {{$package_id == 'Fiber Internet' ? 'selected' : ''}}>Fiber Internet</option>
                            <option value="Computer & Mobile" {{$package_id == 'Computer & Mobile' ? 'selected' : ''}}>Computer & Mobile</option>
                            <option value="Electronic" {{$package_id == 'Electronic' ? 'selected' : ''}}>Electronic</option>
                        </select>
                    </div>
                </div>
                
                <div class="row form-group">
                   <div class="col-md-12" align="center">
                     <button type="button" class="btn btn-danger btn-sm" id="clear_search" >Close</button>

                     <button type="submit" class="btn btn-primary btn-sm" >Search</button>
                   </div>
                </div>
            </form>
          </div>
        </div>

      </div> 
</div>

{{-- Excel Form --}}
    <form id="excel_form" action="{{ route('one_stock_export') }}"  method="POST" class="unicode">
        @csrf
        @method('post')

        <input type="hidden" name="keyword" id="keyword" value="{{$keyword}}">
        <input type="hidden" name="from_date" id="from_date" value="{{$from_date}}">
        <input type="hidden" id="to_date" name="to_date" value="{{ $to_date }}">
        <input type="hidden" name="team_id" id="team_id" value="{{$team_id}}">
        <input type="hidden" id="loc_id" name="loc_id" value="{{ $loc_id }}">
        <input type="hidden" id="package_id" name="package_id" value="{{ $package_id }}">
     </form>


    <div class="page_body">
        <label> Total - {{$count}}</label>
        <div class="table-responsive">
            <table class="table table-bordered styled-table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                    <th>Township</th>
                    <th>Created By</th>
                    <th>Created Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($count > 0)
               @foreach($customer_list as $list)
                <tr >
                    <td class="table-tr" data-url="{{route('package_cust_detail',$list->id)}}">{{++$i}}</td>
                    <td class="table-tr" data-url="{{route('package_cust_detail',$list->id)}}">{{$list->name}}</td>
                    <td class="table-tr" data-url="{{route('package_cust_detail',$list->id)}}">{{$list->phone_no}}</td>
                    <td class="table-tr" data-url="{{route('package_cust_detail',$list->id)}}">{{$list->town_name}}</td>
                    <td class="table-tr" data-url="{{route('package_cust_detail',$list->id)}}">{{$list->cby}}</td>
                    <td class="table-tr" data-url="{{route('package_cust_detail',$list->id)}}">{{date('d-m-Y h:i A',strtotime($list->created_at))}}</td>
                    <td>
                        <form action="{{route('package_customer.destroy',$list->id)}}" method="POST" onsubmit="return confirm('Do you really want to delete?');" style="margin-left: 10px">
                            @csrf
                            @method('DELETE')

                           <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-fw fa-trash" /></i></button> 
                           
                          </form>
                    </td>
                </tr>
               @endforeach
               @else
               <tr>
                   <td align="center" colspan="4">No Data</td>
               </tr>
               @endif
            </tbody>
        </table>
        {{ $customer_list->appends(request()->input())->links()}}
    </div>
    </div>
@stop 

@section('css')
<link id="bsdp-css" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
@stop

@section('js')
<script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
<script type="text/javascript">
    $(function () {
        $("#from_date").datepicker({ format: 'dd-mm-yyyy' });
        $("#to_date").datepicker({ format: 'dd-mm-yyyy' });
    });

    $(function() {
      $('table').on("click", "td.table-tr", function() {
        window.location = $(this).data("url");
      });
    });

    $('#export_btn').click(function(){
            $('#excel_form').submit();
    });

</script>
@stop