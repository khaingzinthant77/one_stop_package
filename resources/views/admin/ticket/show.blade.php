 @extends('adminlte::page')

@section('title', 'Ticket ')

@section('content_header')
<style type="text/css">
  .img_modal {
      display: none; /* Hidden by default */
      position: fixed; /* Stay in place */
      z-index: 1; /* Sit on top */
      padding-top: 100px; /* Location of the box */
      left: 0;
      top: 0;
      width: 100%; /* Full width */
      height: 100%; /* Full height */
      overflow: auto; /* Enable scroll if needed */
      background-color: rgb(0,0,0); /* Fallback color */
      background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
    }
    .photomodal-content {
      margin: auto;
      display: block;
      width: 80%;
      max-width: 700px;
    }

    /* Caption of Modal Image */
    #caption {
      margin: auto;
      display: block;
      width: 80%;
      max-width: 700px;
      text-align: center;
      color: #ccc;
      padding: 10px 0;
      height: 150px;
    }
    /* Add Animation */
    .photomodal-content, #caption {  
      -webkit-animation-name: zoom;
      -webkit-animation-duration: 0.6s;
      animation-name: zoom;
      animation-duration: 0.6s;
    }

    @-webkit-keyframes zoom {
      from {-webkit-transform:scale(0)} 
      to {-webkit-transform:scale(1)}
    }

    @keyframes zoom {
      from {transform:scale(0)} 
      to {transform:scale(1)}
    }

    /* The Close Button */
    .close {
      position: absolute;
      top: 70px;
      right: 50px;
      color: #f1f1f1;
      font-size: 40px;
      font-weight: bold;
      transition: 0.3s;
    }

    .close:hover,
    .close:focus {
      color: #bbb;
      text-decoration: none;
      cursor: pointer;
    }

    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px){
      .modal-content {
        width: 100%;
      }
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
            <!-- <h4>Survey Form</h4> -->
            <div class="row">
              <div style="text-align:left">
                   <a class="btn btn-success unicode" href="{{route('ticket.index')}}">Back</a>
              </div>
              <div class="row col-md-11">
                <div class="col-md-10"></div>
                <div class="col-md-2 row">
                  <a class="btn btn-sm btn-primary" href="{{route('ticket.edit',$tickets->id)}}"><i class="fa fa-fw fa-edit" /></i></a>
                  <form action="" method="POST" onsubmit="return confirm('Do you really want to delete?');" style="margin-left: 10px">
                    @csrf
                    @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fa fa-fw fa-trash" /></i>
                  </button>
                </form>
                </div>
                
              </div>
           </div>
          </div>
          <div class="card-body">
            <div id="photoModal" class="img_modal">
               <span class="close">&times;</span>
               <img class="photomodal-content" id="img01">
               <div id="caption"></div>
             </div>
              <div class="tab">
                  <button class="tablinks"  id="customer" onclick="openTab(event, 'customer_info')" active>Customer Information</button>
                  <button class="tablinks" onclick="openTab(event, 'service_info')">Service Information</button>
                  @if(count($ticket_photos)>0)
                  <button class="tablinks" onclick="openTab(event, 'service_photo')">Service Photos</button>
                  @endif
                  <button class="tablinks" onclick="openTab(event, 'install_materials')">Install Materials</button>
                  <button class="tablinks" onclick="openTab(event, 'signature')">Agreement</button>
                  
              </div>
              <div id="customer_info" class="tabcontent">
                  <div class="row form-group">
                      <div class="col-md-6">
                          <div class="row form-group" style="margin-top:20px;">
                              <label class="col-md-2 unicode">Name*</label>
                                  <div class="col-md-7 {{ $errors->first('name', 'has-error') }}">
                                      <input type="text" name="name" id="name" value="{{$tickets->name}}" class="form-control unicode" placeholder="Mg Mg" style="border:1px solid #327da8;" readonly>
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
                                      <input type="text" name="lat" id="lat" value="{{$tickets->lat}}" class="form-control unicode" placeholder="19.XXXXXX" style="border:1px solid #327da8;" readonly>
                                  </div>    
                          </div>
                          <div class="row form-group">
                              <label class="col-md-2 unicode">Longitude</label>
                                  <div class="col-md-7">
                                      <input type="text" name="lng" id="lng" value="{{$tickets->lng}}" class="form-control unicode" placeholder="96.XXXXXX" style="border:1px solid #327da8;" readonly>
                                  </div>    
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="row form-group" style="margin-top:20px;">
                              <label class="col-md-2 unicode">Township*</label>
                                  <div class="col-md-7">
                                      <input type="text" name="lng" id="lng" value="{{$tickets->town_name}}" class="form-control unicode" style="border:1px solid #327da8;" readonly>
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
                                      <textarea class="form-control" id="remark" name="remark" style="border:1px solid #327da8;" readonly>{{$tickets->remark}}</textarea>
                                  </div>    
                          </div>
                      </div>
                  </div>
                
              </div>

              <div id="service_info" class="tabcontent">
                  <div class="row form-group">
                    <div class="col-md-6">
                        <div class="row form-group" style="margin-top:20px;">
                              <label class="col-md-2 unicode">Ticket Issue*</label>
                                  <div class="col-md-7 {{ $errors->first('ticket_issue', 'has-error') }}">
                                      <input type="text" name="ticket_issue" id="ticket_issue" value="{{$tickets->issue_type}}" class="form-control unicode" placeholder="Mg Mg" style="border:1px solid #327da8;" readonly>
                                  </div>    
                          </div>
                          <div class="row form-group" style="margin-top:20px;">
                              <label class="col-md-2 unicode">Description*</label>
                                  <div class="col-md-7 {{ $errors->first('description', 'has-error') }}">
                                     
                                     <textarea class="form-control" id="description" name="description" style="border:1px solid #327da8;" readonly>{{$tickets->description}}</textarea>
                                  </div>    
                          </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row form-group" style="margin-top:20px;">
                              <label class="col-md-2 unicode">Assign Date*</label>
                                  <div class="col-md-7">
                                      <input type="text" name="assign_date" id="assign_date" value="{{date('d-m-Y',strtotime($tickets->assign_date))}}" class="form-control unicode" placeholder="{{date('d-m-Y')}}" style="border:1px solid #327da8;" readonly>
                                  </div>    
                          </div>
                          <div class="row form-group" style="margin-top:20px;">
                              <label class="col-md-2 unicode">Assign Team*</label>
                                  <div class="col-md-7">
                                      <input type="text" name="assign_date" id="assign_date" value="{{$tickets->group_name}}" class="form-control unicode" placeholder="Team A" style="border:1px solid #327da8;" readonly>
                                  </div>    
                          </div>
                          <div class="row form-group" style="margin-top:20px;">
                              <label class="col-md-2 unicode">Solve Status*</label>
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
              </div>

              <div id="service_photo" class="tabcontent">
                 <div class="row">
                     @foreach($ticket_photos as $key=>$ticket_photo)
                     <div class="col-md-3">
                        <img src="{{ asset($ticket_photo->path.$ticket_photo->photo_name) }}" alt="photo" width="200px" height="200px" style="border-radius:10px;">
                    </div>
                     @endforeach
                 </div>
              </div>

                  
              <div id="install_materials" class="tabcontent">
                  <div class="row">
                    <div class="col-md-4">
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
                  @if(count($ticket_install_items)>0)
                  @foreach($ticket_install_items as $key=>$install_item)
                    <div class="row options form-group" id="actualField0">
                      <div class="col-md-4">
                          
                          <input id="actual_item0" type="text" name="actual_item" placeholder="Items" class="actual_item form-control" style="border:1px solid #327da8;" value="{{$install_item->model}}" readonly>

                      </div>
                      <div class="col-md-2">
                          
                          <input id="unit0" type="text" name="unit[]" placeholder="unit" class="unit form-control" style="border:1px solid #327da8;" value="{{$install_item->unit}}" readonly>
                          
                      </div>
                      <div class="col-md-2">
                          
                          <input id="actual_qty0" type="number" name="actual_qty[]" placeholder="Qty" value="{{$install_item->qty}}" class="actual_qty form-control" value="{{$install_item->qty}}" style="border:1px solid #327da8;" readonly>
                      </div>
                      <div class="col-md-2">
                         
                          <input id="price0" type="number" name="price[]" placeholder="Price" class="price form-control" style="border:1px solid #327da8;" value="{{$install_item->item_price}}" readonly>
                      </div>
                      <div class="col-md-2">
                         
                          <input id="amount0" type="number" name="amount[]" placeholder="Amount" class="amount form-control" style="border:1px solid #327da8;text-align: right;" value="{{$install_item->amount}}" readonly>
                      </div>
                      
                  </div>
                  @endforeach
                  @else
                  <div class="row options form-group" id="actualField0">
                      <div class="col-md-4">
                          
                          <input id="actual_item0" type="text" name="actual_item" placeholder="Items" class="actual_item form-control" style="border:1px solid #327da8;" value="" readonly>

                      </div>
                      <div class="col-md-2">
                          
                          <input id="unit0" type="text" name="unit[]" placeholder="unit" class="unit form-control" style="border:1px solid #327da8;" value="" readonly>
                          
                      </div>
                      <div class="col-md-2">
                          
                          <input id="actual_qty0" type="number" name="actual_qty[]" placeholder="Qty" value="1" class="actual_qty form-control" value="" style="border:1px solid #327da8;">
                      </div>
                      <div class="col-md-2">
                         
                          <input id="price0" type="number" name="price[]" placeholder="Price" class="price form-control" style="border:1px solid #327da8;" value="" readonly>
                      </div>
                      <div class="col-md-2">
                         
                          <input id="amount0" type="number" name="amount[]" placeholder="Amount" class="amount form-control" style="border:1px solid #327da8;" value="" readonly>
                      </div>
                      
                  </div>
                  @endif
                      <div id="actual_row_container" style="margin-top:20px">
                          
                      
                      <div class="row form-group">
                          <div class="col-md-8"></div>
                          <div class="col-md-2" style="text-align:right;">
                              <label>Sub Total</label>
                          </div>
                          <div class="col-md-2">
                            @if($amounts != null)
                              <input id="sub_total" type="number" name="sub_total" placeholder="Amount" class="sub_total form-control" style="border:1px solid #327da8;text-align: right;" value="{{$amounts->sub_total}}" readonly>
                              @else
                              <input id="sub_total" type="number" name="sub_total" placeholder="Sub Total" class="sub_total form-control" style="border:1px solid #327da8;" value="" readonly>
                              @endif
                          </div>
                      </div>
                      <div class="row form-group">
                          <div class="col-md-8"></div>
                          <div class="col-md-2" style="text-align: right;">
                              
                              <label>Install Charge</label>
                          </div>
                          
                          <div class="col-md-2">
                            @if($amounts != null)
                              <input id="install_charge" type="number" name="install_charge" placeholder="Amount" class="install_charge form-control" style="border:1px solid #327da8;text-align: right;" value="{{$amounts->install_charge}}" readonly>
                              @else
                              <input id="install_charge" type="number" name="install_charge" placeholder="Install Charge" class="install_charge form-control" style="border:1px solid #327da8;" value="" readonly>
                              @endif
                          </div>
                      </div>
                      <div class="row form-group">
                          <div class="col-md-8"></div>
                          <div class="col-md-2" style="text-align:right;">
                              <label>Cloud Service</label>
                          </div>
                          
                          
                          <div class="col-md-2">
                            @if($amounts != null)
                              <input id="cloud_charge" type="number" name="cloud_charge" placeholder="Amount" class="cloud_charge form-control" style="border:1px solid #327da8;text-align: right;" value="{{$amounts->cloud_charge}}" readonly>
                              @else
                              <input id="cloud_charge" type="number" name="cloud_charge" placeholder="Cloud Service Charge" class="cloud_charge form-control" style="border:1px solid #327da8;" value="" readonly>
                              @endif
                          </div>

                      </div>
                      <div class="row form-group">
                          <div class="col-md-8"></div>
                          <div class="col-md-2" style="text-align:right;">
                              <label>Service Charge</label>
                          </div>
                          
                          
                          <div class="col-md-2">
                            @if($amounts != null)
                              <input id="service_charge" type="number" name="service_charge" placeholder="Amount" class="service_charge form-control" style="border:1px solid #327da8;text-align: right;" value="{{$amounts->service_charge}}" readonly>
                              @else
                              <input id="service_charge" type="number" name="service_charge" placeholder="Service Charge" class="service_charge form-control" style="border:1px solid #327da8;" value="" readonly>
                              @endif
                          </div>
                      </div>
                      <div class="row form-group">
                          <div class="col-md-8"></div>
                          <div class="col-md-2" style="text-align:right;">
                              <label>On Call Charge</label>
                          </div>
                          
                          
                          <div class="col-md-2">
                            @if($amounts != null)
                              <input id="on_call_charge" type="number" name="on_call_charge" placeholder="Amount" class="on_call_charge form-control" style="border:1px solid #327da8;text-align: right;" value="{{$amounts->on_call_charge}}" readonly>
                              @else
                              <input id="on_call_charge" type="number" name="on_call_charge" placeholder="On Call Charge" class="on_call_charge form-control" style="border:1px solid #327da8;" value="" readonly>
                              @endif
                          </div>
                      </div>
                      <div class="row form-group">
                          <div class="col-md-8"></div>
                          <div class="col-md-2" style="text-align:right;">
                              <label>Discount</label>
                          </div>
                          
                          <div class="col-md-2">
                            @if($amounts != null)
                              <input id="discount" type="number" name="discount" placeholder="Amount" class="discount form-control" style="border:1px solid #327da8;text-align: right;" value="{{$amounts->discount}}" readonly>
                              @else
                              <input id="discount" type="number" name="discount" placeholder="Discount" class="discount form-control" style="border:1px solid #327da8;" value="" readonly>
                              @endif
                          </div>
                      </div>
                      <div class="row form-group">
                          <div class="col-md-8"></div>
                          <div class="col-md-2" style="text-align:right;"> 
                              <label>Total Amount</label>
                          </div>
                          
                          <div class="col-md-2">
                            @if($amounts != null)
                              <input id="total_amt" type="number" name="total_amt" placeholder="Amount" class="total_amt form-control" style="border:1px solid #327da8;text-align: right;" readonly value="{{$amounts->total_amt}}">
                              @else
                              <input id="total_amt" type="number" name="total_amt" placeholder="Amount" class="total_amt form-control" style="border:1px solid #327da8;" readonly value="">
                              @endif
                          </div>
                      </div>
                     
                  </div>
            </div>
           <div id="signature" class="tabcontent">
             <div class="row">
                 @if($signatures->count()>0)
                 <div class="col-md-3">
                    <img src="{{ asset($signatures[0]->path.$signatures[0]->cust_sign) }}" alt="photo" width="200px" height="200px" style="border-radius:10px;">
                    <p>Customer Sign</p>
                </div>
                <div class="col-md-3">
                    <img src="{{ asset($signatures[0]->path.$signatures[0]->tech_sign) }}" alt="photo" width="200px" height="200px" style="border-radius:10px;">
                    <p>Technician Sign</p>
                </div>
                 @endif
             </div>
             <hr>
             <div class="row">
                 @if($signatures->count()>0)
                 <div class="col-md-3">
                    <img src="{{ asset($signatures[0]->path.$signatures[0]->cust_sign_image) }}" alt="photo" width="200px" height="200px" style="border-radius:10px;">
                    <p>Customer Sign</p>
                </div>
                <div class="col-md-3">
                    <img src="{{ asset($signatures[0]->path.$signatures[0]->tech_sign_image) }}" alt="photo" width="200px" height="200px" style="border-radius:10px;">
                    <p>Technician Sign</p>
                </div>
                 @endif
             </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop

@section('js')
<script type="text/javascript">
  $(document).ready(function(){
       document.getElementById("customer_info").style.display = "block"; 

     $("#customer").addClass("active");

    });

    var modal = document.getElementById("photoModal");
   // var img1 = document.getElementById("thumbnail_img");

   var modalImg = document.getElementById("img01");
   var captionText = document.getElementById("caption");

   $('img').click(function(){
       // alert(this.id);
       modal.style.display = "block";
       modalImg.src = this.src;
       captionText.innerHTML = this.alt;
  });

   var span = document.getElementsByClassName("close")[0];

   // When the user clicks on <span> (x), close the modal
   span.onclick = function() { 
     modal.style.display = "none";
   }

  function openTab(evt, tabName) {
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
      evt.currentTarget.className += " active";
      // if (tabName == 'customer_info') {
      //   $("#customer").addClass("active");
      // }
      // if (tabName == 'service_info') {
      //   $("#install_photo").addClass("active");
      // }

      // if (tabName == 'service_photo') {
      //   $("#assign_team").addClass("active");
      // }
    }
</script>
@stop