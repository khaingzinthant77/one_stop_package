 @extends('adminlte::page')

@section('title', 'Ticket ')

@section('content_header')
<style type="text/css">
    .wrap {
        position: relative;
        display: inline-block;
    }
    .wrap span {
        position: absolute;
        top: 0;
        right: 10;
        color: red;
        cursor: pointer;

    }
</style>
@stop

@section('content')

<div class="container-fluid">
  <div class="fade-in">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h4>Ticket Form</h4></div>
            <form action="{{route('ticket.update',$tickets->id)}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="tab">
                        <button class="tablinks"  id="customer" disabled active style="color:black;">Customer Information</button>
                        <button class="tablinks" id="service_info" disabled style="color:black;">Service Information</button>
                        <button class="tablinks" id="service_photo" disabled style="color:black;">Service Photos</button>
                        <button class="tablinks" id="install_material" disabled style="color:black;">Install Materials</button>
                    </div>
                    <div id="customer_info" class="tabcontent">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row form-group">
                                    <label class="col-md-2">Customer Type</label>
                                    <div class="col-md-7">
                                        @if($tickets->c_type == 1)
                                            <input type="text" id="name" class="form-control unicode" value="Linn Customer" readonly>
                                        @else
                                            <input type="text" id="name" class="form-control unicode" value="Other Customer" readonly>
                                        @endif
                                    </div>
                                </div>

                                <div class="row form-group" style="margin-top:20px;">
                                    <label class="col-md-2 unicode">Name*</label>
                                        <div class="col-md-7 {{ $errors->first('name', 'has-error') }}">
                                            <input type="text" name="name" id="name" value="{{ $tickets->name}}" class="form-control unicode" placeholder="Mg Mg" style="border:1px solid #327da8;" readonly>
                                        </div>    
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-2 unicode">Phone No*</label>
                                        <div class="col-md-7 {{ $errors->first('name', 'has-error') }}">
                                            <input type="number" name="ph_no" id="ph_no" value="{{ $tickets->phone_no}}" class="form-control unicode" placeholder="09XXXXXXXXX" style="border:1px solid #327da8;" readonly>
                                        </div>    
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-2 unicode">Latitude</label>
                                        <div class="col-md-7">
                                            <input type="text" name="lat" id="lat" value="{{ $tickets->lat}}" class="form-control unicode" placeholder="19.XXXXXX" style="border:1px solid #327da8;" readonly>
                                        </div>    
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-2 unicode">Longitude</label>
                                        <div class="col-md-7">
                                            <input type="text" name="lng" id="lng" value="{{ $tickets->lng }}" class="form-control unicode" placeholder="96.XXXXXX" style="border:1px solid #327da8;" readonly>
                                        </div>    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row form-group" style="margin-top:20px;">
                                    <label class="col-md-2 unicode">Township*</label>
                                        <div class="col-md-7">
                                            <input type="text" name="tsh_id" id="tsh_id" value="{{ $tickets->town_name }}" class="form-control unicode" placeholder="Pyinmana" style="border:1px solid #327da8;" readonly>
                                            <!-- <select class="form-control" id="tsh_id" name="tsh_id">
                                                <option value="">All</option>
                                                    @foreach($townships as $key=>$township)
                                                    <option value="{{$township->id}}" {{$township->id == $tickets->tsh_id ? 'selected' : ''}}>{{$township->town_name}}</option>
                                                    @endforeach
                                            </select> -->
                                            
                                        </div>    
                                </div>
                                <div class="row form-group" style="margin-top:20px;">
                                    <label class="col-md-2 unicode">Address*</label>
                                        <div class="col-md-7">
                                            <textarea class="form-control" id="address" name="address" style="border:1px solid #327da8;" readonly>{{$tickets->address}}</textarea>
                                        </div>    
                                </div>
                                <div class="row form-group" style="margin-top:20px;">
                                    <label class="col-md-2 unicode">Remark</label>
                                        <div class="col-md-7">
                                            <textarea class="form-control" id="remark" name="remark" style="border:1px solid #327da8;">{{$tickets->remark}}</textarea>
                                        </div>    
                                </div>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <a class="btn btn-success" id="cust_next">Next</a>
                        </div>
                    </div>

                    <div id="service_infos" class="tabcontent">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row form-group">
                                    <label class="col-md-3">Ticket Issue</label>
                                    <div class="col-md-6">
                                        <!-- <input type="text" id="ticket_issue" name="ticket_issue" class="form-control unicode" value="{{$tickets->ticket_issue}}"> -->
                                        
                                        <select class="form-control" id="issue_id" name="issue_id">
                                            <option value="">All</option>
                                            @foreach($ticket_issues as $key=>$issue)
                                            <option value="{{$issue->id}}" {{$issue->id == $tickets->issue_id ? 'selected' : ''}}>{{$issue->issue_type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-3">Description</label>
                                    <div class="col-md-6">
                                        <!-- <input type="text" id="description" name="description" class="form-control unicode" value="{{$tickets->description}}"> -->
                                        <textarea class="form-control" id="description" name="description">{{$tickets->description}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row form-group">
                                    <label class="col-md-3">Assign Date</label>
                                    <div class="col-md-6">
                                        <input type="text" id="assign_date" name="assign_date" class="form-control unicode" value="{{date('d-m-Y',strtotime($tickets->assign_date))}}">
                                    </div>
                                </div>
                               
                                <div class="row form-group">
                                    <label class="col-md-3">Assign Team</label>
                                    <div class="col-md-6">
                                        <!-- <input type="text" id="assign_team" name="assign_team" class="form-control unicode" value="{{$tickets->group_name}}"> -->
                                         <select class="form-control" id="team_id" name="team_id" style="border:1px solid #327da8;">
                                            <option value="">Select Teams</option>
                                            @foreach($teams as $key=>$team)
                                            <option value="{{$team->id}}" {{$tickets->team_id == $team->id ? 'selected' : ''}}>{{$team->group_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-3">Solve Status</label>
                                    <div class="col-md-7">
                                        @if($tickets->is_solve == 1)
                                            <span style="background-color:#28a745;padding: 7px;color: white;border-radius: 5px;">Solved</span>
                                        @else
                                            <span style="background-color:#ffc107;padding: 7px;color: white;border-radius: 5px;">Unsolve</span> 
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <a class="btn btn-info" onclick="openTab(event, 'customer_info')">Next</a>
                            </div>
                            <div class="col-md-6" style="text-align:right">
                                <a class="btn btn-success" id="photo_next">Next</a>
                            </div>
                        </div>
                    </div>

                    <div id="service_photos" class="tabcontent">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group my-2">
                            @if(count($ticket_photos)>0)
                                <input type="file" id="images" name="images[]" class="form-control"
                                accept="image/jpg, image/jpeg, image/png" multiple style="margin-right:3px; border:1px solid #327da8;" value="1">
                                <button type="button" onclick="addImageField()" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                
                            @else
                                <input type="file" id="images" name="images[]" class="form-control"
                                accept="image/jpg, image/jpeg, image/png" multiple style="margin-right:3px; border:1px solid #327da8;" value="">
                                <button type="button" onclick="addImageField()" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                            @endif
                            </div>
                                <div id="images_container"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                     @foreach($ticket_photos as $key=>$photo)
                                        <div class="col-md-4 wrap">
                                          <img src="{{ asset($photo->path.$photo->photo_name) }}" alt="photo" width="200px" height="200px" style="border-radius:10px;"><span onclick="deletePhoto({{$photo->id}});">X</span>
                                        </div>
                                      @endforeach
                                </div>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="col-md-6">
                                <a class="btn btn-info" onclick="openTab(event, 'service_infos')" style="float:left;">Back</a>
                            </div>
                            
                            <div class="col-md-6">
                                <a class="btn btn-success" id="assign_next" style="float:right;">Next</a>
                            </div>
                            

                        </div>
                    </div>
                      
                    <div id="install_materials" class="tabcontent">
                            <div class="row" style="text-align: right;">
                                    <button type="button" onclick="addActualRow()" class="btn btn-primary"><i class="fa fa-plus" style="float:right;"></i></button>
                            </div> 
                            <div class="row">
                                <div class="col-md-3">
                                  <label>Materials</label>
                                </div>
                                <div class="col-md-2">
                                  <label>Unit</label>
                                </div>
                                <div class="col-md-2">
                                  <label>Qty</label>
                                </div>
                                <div class="col-md-2">
                                   <label>Price</label>
                                </div>
                                <div class="col-md-2">
                                   <label>Amount</label>
                                </div>
                              </div>
                              <input type="hidden" name="item_count"  id="item_count" value="{{count($ticket_install_items)}}">
                            @if(count($ticket_install_items)>0)

                            @foreach($ticket_install_items as $key=>$install_item)
                             
                            <div class="row options form-group" id="actualField0">
                            <div class="col-md-3">
                                <input id="actual_item{{$key}}" type="text" name="actual_item[]" placeholder="Items" class="actual_item form-control" style="border:1px solid #327da8;" value="{{$install_item->model}}">
                                <input type="hidden" name="actual_items" id="actual_item" value="{{json_encode($items)}}">
                                <input type="hidden" id="install_amt{{$key}}" name="install_amt[]" class="install_amt" value="{{$install_item->item_id}}">
                                <input type="hidden" id="cat_id{{$key}}" name="cat_id[]" class="cat_id" value="{{$install_item->cat_id}}">
                                <input type="hidden" id="cat_price{{$key}}" name="cat_price[]" class="cat_price" value="{{$install_item->cat_price}}">
                            </div>
                            <div class="col-md-2">
                                <input id="unit{{$key}}" type="text" name="unit[]" placeholder="unit" class="unit form-control" style="border:1px solid #327da8;" value="{{$install_item->unit}}" readonly>
                                
                            </div>
                            <div class="col-md-2">
                                <input id="actual_qty{{$key}}" data-id="{{$key}}" type="number" name="actual_qty[]" placeholder="Qty" value="1" class="actual_qty form-control" style="border:1px solid #327da8;" value="{{$install_item->qty}}">
                            </div>
                            <div class="col-md-2">
                                <input id="price{{$key}}" type="number" name="price[]" placeholder="Price" class="price form-control" value="{{$install_item->item_price}}" style="border:1px solid #327da8;" readonly>
                            </div>
                            <div class="col-md-2">
                                <input id="amount{{$key}}" type="number" name="amount[]" placeholder="Amount" class="amount form-control" style="border:1px solid #327da8;" readonly value="{{$install_item->amount}}">
                            </div>
                            <div class="col-md-1">
                                <button type="button" onclick="removeRow({{$key}})" class="btn btn-danger"><i class="fa fa-minus"></i></button>
                            </div>
                            
                            </div>
                            @endforeach
                            @else
                            <div class="row options" id="actualField0">
                            <div class="col-md-3">
                              
                                <input id="actual_item0" type="text" name="actual_item[]" placeholder="Items" class="actual_item form-control" style="border:1px solid #327da8;">
                                <input type="hidden" name="actual_items" id="actual_item" value="{{json_encode($items)}}">
                                <input type="hidden" id="install_amt0" name="install_amt[]" class="install_amt">
                                <input type="hidden" id="cat_id0" name="cat_id[]" class="cat_id">
                                <input type="hidden" id="cat_price0" name="cat_price[]" class="cat_price">
                            </div>
                            <div class="col-md-2">
                               
                                <input id="unit0" type="text" name="unit[]" placeholder="unit" class="unit form-control" style="border:1px solid #327da8;" readonly>
                                
                            </div>
                            <div class="col-md-2">
                               
                                <input id="actual_qty0" data-id="0" type="number" name="actual_qty[]" placeholder="Qty" value="1" class="actual_qty form-control" style="border:1px solid #327da8;">
                            </div>
                            <div class="col-md-2">
                             
                                <input id="price0" type="number" name="price[]" placeholder="Price" class="price form-control" style="border:1px solid #327da8;" readonly>
                            </div>
                            <div class="col-md-2">
                              
                                <input id="amount0" type="number" name="amount[]" placeholder="Amount" class="amount form-control" style="border:1px solid #327da8;" readonly>
                            </div>
                            <div class="col-md-1">
                                <button type="button" onclick="removeRow(0)" class="btn btn-danger"><i class="fa fa-minus"></i></button>
                            </div>
                            
                        </div>
                            @endif
                            <div id="actual_row_container" style="margin-top:20px">
                                
                            </div>

                            <div class="row form-group">
                                <div class="col-md-7"></div>
                                <div class="col-md-2" style="text-align:right;">
                                    <label>Sub Total</label>
                                </div>
                                <div class="col-md-2">
                                    @if($amounts != null)
                                    <input id="sub_total" type="number" name="sub_total" placeholder="Amount" class="sub_total form-control" style="border:1px solid #327da8;text-align: right;" value="{{$amounts->sub_total}}" readonly>
                                    @else
                                    <input id="sub_total" type="number" name="sub_total" placeholder="Amount" class="sub_total form-control" style="border:1px solid #327da8;" value="" readonly>
                                    @endif
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-7"></div>
                                <div class="col-md-2" style="text-align: right;">
                                    
                                    <label>Install Charge</label>
                                </div>
                                
                                <div class="col-md-2">
                                    @if($amounts != null)
                                    <input id="install_charge" type="number" name="install_charge" placeholder="Amount" class="install_charge form-control" style="border:1px solid #327da8;text-align: right;" value="{{$amounts->install_charge}}" readonly>
                                    @else
                                    <input id="install_charge" type="number" name="install_charge" placeholder="Amount" class="install_charge form-control" style="border:1px solid #327da8;" value="" readonly>
                                    @endif
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-5"></div>
                                @if($amounts != null)
                                <div class="col-md-2" style="text-align:right;margin-left: 137px;">
                                    @if($amounts->cloud_charge == 0)
                                    <input type="checkbox" name="cloud_check" style="width:20px;height: 20px;margin-top: 7px;" id="cloud_check">
                                    @else
                                    <input type="checkbox" name="cloud_check" style="width:20px;height: 20px;margin-top: 7px;" id="cloud_check" checked>
                                    @endif
                                    
                                </div>
                                @else
                                <div class="col-md-2" style="text-align:right;margin-left: 137px;">
                                    <input type="checkbox" name="cloud_check" style="width:20px;height: 20px;margin-top: 7px;" id="cloud_check">
                                </div>
                                @endif
                                <div>
                                    <label>Cloud Service</label>
                                </div>
                                
                                
                                <div class="col-md-2">
                                    @if($amounts != null)
                                    <input id="cloud_charge" type="number" name="cloud_charge" placeholder="Amount" class="cloud_charge form-control" style="border:1px solid #327da8;text-align: right;" value="{{$amounts->cloud_charge}}" readonly>
                                    @else
                                    <input id="cloud_charge" type="number" name="cloud_charge" placeholder="Amount" class="cloud_charge form-control" style="border:1px solid #327da8;" value="" readonly>
                                    @endif
                                </div>

                            </div>
                            <div class="row form-group">
                                <div class="col-md-7"></div>
                                <div class="col-md-2" style="text-align:right;">
                                    <label>Service Charge</label>
                                </div>
                                
                                <div class="col-md-2">
                                    @if($amounts != null)
                                    <input id="service_charge" type="number" name="service_charge" placeholder="Amount" class="service_charge form-control" style="border:1px solid #327da8;text-align: right;" value="{{$amounts->service_charge}}">
                                    @else
                                    <input id="service_charge" type="number" name="service_charge" placeholder="Amount" class="service_charge form-control" style="border:1px solid #327da8;text-align: right;" value="">
                                    @endif
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-7"></div>
                                <div class="col-md-2" style="text-align:right;">
                                    <label>On Call Charge</label>
                                </div>
                                
                                <div class="col-md-2">
                                    @if($amounts != null)
                                    <input id="on_call_charge" type="number" name="on_call_charge" placeholder="Amount" class="on_call_charge form-control" style="border:1px solid #327da8;text-align: right;" value="{{$amounts->on_call_charge}}">
                                    @else
                                    <input id="on_call_charge" type="number" name="on_call_charge" placeholder="Amount" class="on_call_charge form-control" style="border:1px solid #327da8;text-align: right;" value="">
                                    @endif
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-7"></div>
                                <div class="col-md-2" style="text-align:right;">
                                    <label>Discount</label>
                                </div>
                                
                                <div class="col-md-2">
                                    @if($amounts != null)
                                    <input id="discount" type="number" name="discount" placeholder="Amount" class="discount form-control" style="border:1px solid #327da8;text-align: right;" value="{{$amounts->discount}}">
                                    @else
                                    <input id="discount" type="number" name="discount" placeholder="Amount" class="discount form-control" style="border:1px solid #327da8;text-align: right;" value="">
                                    @endif
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-7"></div>
                                <div class="col-md-2" style="text-align:right;"> 
                                    <label>Total Amount</label>
                                </div>
                                
                                <div class="col-md-2">
                                    @if($amounts != null)
                                    <input id="total_amt" type="number" name="total_amt" placeholder="Amount" class="total_amt form-control" style="border:1px solid #327da8;text-align: right;" value="{{$amounts->total_amt}}" readonly>
                                    @else
                                    <input id="total_amt" type="number" name="total_amt" placeholder="Amount" class="total_amt form-control" style="border:1px solid #327da8;text-align: right;" value="" readonly>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <a class="btn btn-info" onclick="openTab(event, 'service_photos')" style="float:left;">Back</a>
                                </div>
                                <div class="col-md-6" style="text-align:right;">
                                    <button type="submit" class="btn btn-success unicode" style="margin-right: 110px;">Save</button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </form>

              </div>
          </div>
      </div>
  </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">
<link id="bsdp-css" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
<style type="text/css">
    .autocomplete-items {
          position: absolute;
          border: 1px solid #d4d4d4;
          border-bottom: none;
          border-top: none;
          z-index: 99;
          /*position the autocomplete items to be the same width as the container:*/
          top: 100%;
          left: 0;
          right: 0;
        }

        .autocomplete-items div {
          padding: 10px;
          cursor: pointer;
          background-color: #fff; 
          border-bottom: 1px solid #d4d4d4; 
        }

        /*when hovering an item:*/
        .autocomplete-items div:hover {
          background-color: #e9e9e9; 
        }

        /*when navigating through the items using the arrow keys:*/
        .autocomplete-active {
          background-color: DodgerBlue !important; 
          color: #ffffff; 
        }
</style>
@stop

@section('js')
<script src="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>

<script>
    $(function () {
                $("#assign_date").datepicker({ format: 'dd-mm-yyyy' });
                $("#appoint_date").datepicker({ format: 'dd-mm-yyyy' });
        });
    function deletePhoto(id){
        var c = confirm("Do you want to delete?");
        if (c == true) {
            // alert('hi');
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "<?php echo route('delete_img') ?>",
                data: {'img_id': id},
                success: function(data){
                 location.reload();
                }
            });
        }else{
            return false;
        }
    }
    $(document).ready(function(){
       document.getElementById("customer_info").style.display = "block"; 

     $("#customer").addClass("active");

    });

    $('#cloud_check').on('click', function(e) {
        if($(this).is(':checked',true))  
        {
            $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "<?php echo(route("get_cloud_charge")) ?>",
                    success: function(data){
                        $('#cloud_charge').val(data);
                        calculate_total();
                    }
                });
            
        } else {  
            $('#cloud_charge').val(0);
            calculate_total();
        }  
    });

   function openTab(evt, tabName) {
    // console.log(tabName);
      var i, tabcontent, tablinks;
      tabcontent = document.getElementsByClassName("tabcontent");
      for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
      }

      tablinks = document.getElementsByClassName("tablinks");
      for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
      }
      document.getElementById(tabName).style.display = "block";
      if (tabName == 'customer_info') {
        $("#customer").addClass("active");
      }
      if (tabName == 'service_infos') {
        $("#service_info").addClass("active");
      }

      if (tabName == 'service_photos') {
        $("#service_photo").addClass("active");
      }
    }

   $(function(){
        $('#cust_next').on('click',function(){
            var name = $('#name').val();
            var ph_no = $('#ph_no').val();
            var township = $("#tsh_id option:selected" ).text();
            // alert(township);
            var address = $("#address").val();
            if (!name) {
                alert("Name is empty!");
                // return false;
            }else if (!ph_no) {
                alert('Phone No empty!');
            }else if (township == "Select Township") {
                alert('Township empty!');
            }else if (!address) {
                alert('Address empty!');
            }else{
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                  for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                  }
                document.getElementById('service_infos').style.display = "block";
                $("#service_info").addClass("active");
            }
        });

        $('#photo_next').on('click',function(){
            // var img = $('#images').val();
            // var image = document.getElementById('images').value;
            // alert(image);
            // if (!image) {
            //     alert('Image empty!');
            // }else{
                  var i, tabcontent, tablinks;
                  tabcontent = document.getElementsByClassName("tabcontent");
                  for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                  }
                  tablinks = document.getElementsByClassName("tablinks");
                      for (i = 0; i < tablinks.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                      }
                document.getElementById('service_photos').style.display = "block";
                 $("#service_photo").addClass("active");
            

            
        });

        $('#assign_next').on('click',function(){
            // var team = $("#team_id option:selected" ).text();
            // if (team == 'Select Teams') {
            //     alert('Team empty!');
            // }else{

                 
            // }

            var i, tabcontent, tablinks;
                  tabcontent = document.getElementsByClassName("tabcontent");
                  for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                  }
                  tablinks = document.getElementsByClassName("tablinks");
                      for (i = 0; i < tablinks.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                      }
                document.getElementById('install_materials').style.display = "block";
                 $("#install_material").addClass("active");
        });
   });

    var i = 0;
    function addImageField(){
        i++;
        let html = '<img id="image'+i+'">\
                    <div class="input-group" id="inputFile'+i+'"><input type="file" id="images" name="images[]" class="form-control"\
                    accept="image/jpg, image/jpeg, image/png" required style="margin-right:3px;">\
                    <button type="button" onclick="removeImageField('+i+')" class="btn btn-danger"><i class="fa fa-minus"></i></button>\
                    </div>';
        $("#images_container").append(html);
    }

    function removeImageField(id){
        $('#inputFile'+id).remove();
    }

        var j = 0;
        if (parseInt($("#item_count").val()) != 0) {
            j = parseInt($("#item_count").val()) - 1;
        }

        function addActualRow(){
            j++;
            let html = '<div class="row options" id="actualField'+j+'"><div class="col-md-3 form-group"><input type="hidden" id="install_amt'+j+'" class="install_amt" name="install_amt[]"><input type="hidden" id="cat_id'+j+'" name="cat_id[]" class="cat_id"><input type="hidden" id="cat_price'+j+'" name="cat_price[]" class="cat_price"><input id="actual_item'+j+'" type="text" name="actual_item[]" placeholder="Items" class="actual_item form-control" style="border:1px solid #327da8;"></div><div class="col-md-2"><input id="unit'+j+'" type="text" name="unit[]" placeholder="unit" class="unit form-control" style="border:1px solid #327da8;" readonly></div><div class="col-md-2"><input data-id="'+j+'" id="actual_qty'+j+'" type="number" name="actual_qty[]" placeholder="Qty" value="1" class="actual_qty form-control" style="border:1px solid #327da8;"></div><div class="col-md-2"><input id="price'+j+'" type="number" name="price[]" placeholder="Price" class="price form-control" style="border:1px solid #327da8;" readonly></div><div class="col-md-2"><input id="amount'+j+'" type="number" name="amount[]" placeholder="Amount" class="amount form-control" style="border:1px solid #327da8;" readonly></div><div class="col-md-1"><button type="button" onclick="removeRow('+j+')" class="btn btn-danger"><i class="fa fa-minus"></i></button></div></div>'
            $("#actual_row_container").append(html);

            actual_autocomplete(document.getElementById("actual_item"+j), data);

            $("#actual_qty"+j).on("change",function(){

                var qty = $(this).val();
            
                // alert($(this).closest('tr').find('input[type=checkbox]').val());
                cal_install_charge();

                var price = $(this).closest("div.options").find("input[name='price[]']").val();
                var amt = qty * price;

                var val = $(this).closest("div.options").find("input[name='amount']").attr('id');
                // alert(val);
                $("#amount"+j).val(amt);

                // $("#total").val($("#"+val).val());
                
                
                var total = 0;
                $('.amount').each(function () {
                    var amt = parseInt(this.value);
                    total = total + amt;
                });
                $('#sub_total').val(total);
                calculate_total();
            });

        }



        var items = $("#actual_item").val();
        var data = $.parseJSON(items);
        
        actual_autocomplete(document.getElementById("actual_item0"), data);

            var k = parseInt($("#item_count").val());
            function actual_autocomplete(inp, arr) {
              /*the autocomplete function takes two arguments,
              the text field element and an array of possible autocompleted values:*/
              var currentFocus;
              /*execute a function when someone writes in the text field:*/
              inp.addEventListener("input", function(e) {
                  var a, b, i, val = this.value;
                  /*close any already open lists of autocompleted values*/
                  closeAllLists();
                  if (!val) { return false;}
                  currentFocus = -1;
                  /*create a DIV element that will contain the items (values):*/
                  a = document.createElement("DIV");
                  a.setAttribute("id", this.id + "autocomplete-list");
                  a.setAttribute("class", "autocomplete-items");
                  /*append the DIV element as a child of the autocomplete container:*/
                  this.parentNode.appendChild(a);
                  /*for each item in the array...*/
                  
                  for (i = 0; i < arr.length; i++) {
                    if (arr[i].model.toUpperCase().includes(val.toUpperCase())) {
                          b = document.createElement("DIV");
                     
                          b.innerHTML += arr[i].model;
                          
                          b.innerHTML += "<input type='hidden' value='" + arr[i].model + "'>";
                          b.innerHTML += "<input type='hidden' value='" + arr[i].price + "'>";
                          b.innerHTML += "<input type='hidden' value='" + arr[i].qty + "'>";
                          b.innerHTML += "<input type='hidden' value='" + arr[i].unit + "'>";
                          b.innerHTML += "<input type='hidden' value='" + arr[i].id + "'>";
                          b.innerHTML += "<input type='hidden' value='" + arr[i].cat_id + "'>";
                          
                          b.addEventListener("click", function(e) {
                            
                              inp.value = this.getElementsByTagName("input")[0].value;
                              var price = this.getElementsByTagName("input")[1].value;
                              var qty = this.getElementsByTagName("input")[2].value;
                              var unit = this.getElementsByTagName("input")[3].value;
                              var item_id = this.getElementsByTagName("input")[4].value;
                              var cat_id = this.getElementsByTagName("input")[5].value;
                              closeAllLists();
                              // console.log(get_cat_price());
                              $('#install_amt'+k).val(item_id);
                              $('#cat_id'+k).val(cat_id);
                              // $('#cat_price'+k).val();
                              get_cat_price(cat_id,k);
                              $("#price"+k).val(price);
                              $("#unit"+k).val(unit);
                              $("#amount"+k).val(price);
                              $("#stock_qty_"+k).val(qty);
                              $("#actual_item"+k).attr('readonly', true);

                              calc_actual_amt()
                              cal_install_charge();
                              
                             ++k;

                          });
                          a.appendChild(b);
                    }

                  }
              });
            
          /*execute a function presses a key on the keyboard:*/
          inp.addEventListener("keydown", function(e) {
              var x = document.getElementById(this.id + "autocomplete-list");
              if (x) x = x.getElementsByTagName("div");
              if (e.keyCode == 40) {
                /*If the arrow DOWN key is pressed,
                increase the currentFocus variable:*/
                currentFocus++;
                /*and and make the current item more visible:*/
                addActive(x);
              } else if (e.keyCode == 38) { //up
                /*If the arrow UP key is pressed,
                decrease the currentFocus variable:*/
                currentFocus--;
                /*and and make the current item more visible:*/
                addActive(x);
              } else if (e.keyCode == 13) {
                /*If the ENTER key is pressed, prevent the form from being submitted,*/
                e.preventDefault();
                if (currentFocus > -1) {
                  /*and simulate a click on the "active" item:*/
                  if (x) x[currentFocus].click();
                }
              }
          });

          function addActive(x) {
            /*a function to classify an item as "active":*/
            if (!x) return false;
            /*start by removing the "active" class on all items:*/
            removeActive(x);
            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);
            /*add class "autocomplete-active":*/
            x[currentFocus].classList.add("autocomplete-active");
          }
          function removeActive(x) {
            /*a function to remove the "active" class from all autocomplete items:*/
            for (var i = 0; i < x.length; i++) {
              x[i].classList.remove("autocomplete-active");
            }
          }

          function closeAllLists(elmnt) {
            /*close all autocomplete lists in the document,
            except the one passed as an argument:*/
            var x = document.getElementsByClassName("autocomplete-items");
            for (var i = 0; i < x.length; i++) {
              if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
              }
            }
          }
          /*execute a function when someone clicks in the document:*/
          document.addEventListener("click", function (e) {
            // console.log(e);
              closeAllLists(e.target);
          });
        }

        
        $(".actual_qty").on("change",function(){

            var qty = $(this).val();
            cal_install_charge();
            var price = $(this).closest("div.options").find("input[name='price[]']").val();
            var amt = qty * price;
            var val = $(this).closest("div.options").find("input[name='amount[]']").attr('id');
            $("#"+val).val(amt);
            var total = 0;
            $('.amount').each(function () {
                var amt = parseInt(this.value);
                total = total + amt;
            });
            $('#sub_total').val(total);
            calculate_total();


        //     var val = $(this).closest("div.options").find("input[name='amount']").attr('id');
        //     alert(val);
        //     $("#"+val).val(total);

        //     $('.amount').each(function () {
        //     var amt = parseInt(this.value);
        //     total = total + amt;
        // });
            // $('#total_amt').val(total);


        });

        // $("#actual_qty0").on("change",function(){
        //         var qty = $(this).val();
        //         cal_install_charge();
        //         // var rowindex = $(this).closest('tr').attr("data_index");
        //         var price = $("#price0").val();
                

        //         $("#amount0").val(amt);
        //         var sum = 0;
        //         var total = 0;
        //         $('.amount').each(function () {
        //             var amt = parseInt(this.value);
        //             total = total + amt;
        //         });
        //         $('#sub_total').val(total);
        //         calculate_total();
                
        // });

        function cal_install_charge() {
            var install_charge = 0;
            $(".install_amt").each(function() {
                    var qty = $(this).closest("div.options").find("input[name='actual_qty[]']").val();
                    $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "<?php echo(route("get_install_amt")) ?>",
                    data: {'item_id': $(this).val()},
                    success: function(data){
                        // console.log(data);
                        install_charge += data * qty;
                        $('#install_charge').val(install_charge);
                        calculate_total();
                    }
                });
            });
            
        }

        function get_cat_price(cat_id,index){
                // console.log(cat_id);
                    $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: "<?php echo(route("get_cat_price")) ?>",
                    data: {'cat_id': cat_id},
                    success: function(data){
                       $('#cat_price'+index).val(data);
                    }
                });
            return data;
        }

          function calc_actual_amt(){
            var sum = 0;
            $(".amount").each(function() {
                sum += +this.value;
            });
            
            $("#sub_total").val(sum);

            calculate_total();
        }
        $('#service_charge').on('change', function() {
            calculate_total();
        });

        $('#on_call_charge').on('change', function() {
            calculate_total();
        });

        $('#discount').on('change', function() {
            calculate_total();
        });

        function calculate_total() {
        var sub_total = $('#sub_total').val() == "" || $('#sub_total').val() == null ? 0 : $('#sub_total').val();
        
        var install_charge = $('#install_charge').val() == "" || $('#install_charge').val() == null ? 0 : $('#install_charge').val();

        var cloud_charge = $('#cloud_charge').val() == "" || $('#cloud_charge').val() == null ? 0 : $('#cloud_charge').val();

        var service_charge = $('#service_charge').val() == "" || $('#service_charge').val() == null ? 0 : $('#service_charge').val();

        var on_call_charge = $('#on_call_charge').val() == "" || $('#on_call_charge').val() == null ? 0 : $('#on_call_charge').val();

        var total = parseInt(sub_total) + parseInt(install_charge) + parseInt(cloud_charge) + parseInt(service_charge) + parseInt(on_call_charge);

        var discount = $('#discount').val() == "" || $('#discount').val() == null ? 0 : $('#discount').val();
        
        var net_pay = parseInt(total) - parseInt(discount);

        $('#total_amt').val(net_pay);

    }


        function removeRow(id){
            $('#actualField'+id).remove();
            var total = 0;
            $('.amount').each(function () {
                var amt = parseInt(this.value);
                total = total + amt;
            });
            cal_install_charge();

            $('#sub_total').val(total);


        }



</script>
@stop