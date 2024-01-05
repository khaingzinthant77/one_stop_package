 @extends('adminlte::page')

@section('title', 'Survey ')

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
                   <a class="btn btn-success unicode" href="{{route('surveys.index')}}">Back</a>
              </div>
              <div class="row col-md-11">
                <div class="col-md-10"></div>
                <div class="col-md-2 row">
                  <a class="btn btn-sm btn-primary" href="{{route('surveys.edit',$survey->id)}}"><i class="fa fa-fw fa-edit" /></i></a>
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
                  <button class="tablinks" onclick="openTab(event, 'install_photos')">Install Photos</button>
                  
                  <button class="tablinks" onclick="openTab(event, 'assign_teams')">Assign Team</button>
                 
                  <button class="tablinks" onclick="openTab(event, 'install_materials')">Install Materials</button>
                   @if($survey->lat != null && $survey->lng)
                  <button class="tablinks" onclick="openTab(event, 'map')">Map View</button>
                   @endif
              </div>
              <div id="customer_info" class="tabcontent">
                  <div class="row form-group">
                      <div class="col-md-6">
                          <div class="row form-group" style="margin-top:20px;">
                              <label class="col-md-2 unicode">Name*</label>
                                  <div class="col-md-7 {{ $errors->first('name', 'has-error') }}">
                                      <input type="text" name="name" id="name" value="{{$survey->name}}" class="form-control unicode" placeholder="Mg Mg" style="border:1px solid #327da8;" readonly>
                                  </div>    
                          </div>
                          <div class="row form-group">
                              <label class="col-md-2 unicode">Phone No*</label>
                                  <div class="col-md-7 {{ $errors->first('name', 'has-error') }}">
                                      <input type="number" name="ph_no" id="ph_no" value="{{ $survey->phone_no}}" class="form-control unicode" placeholder="09XXXXXXXXX" style="border:1px solid #327da8;" readonly>
                                  </div>    
                          </div>
                          <div class="row form-group">
                              <label class="col-md-2 unicode">Latitude</label>
                                  <div class="col-md-7">
                                      <input type="text" name="lat" id="lat" value="{{$survey->lat}}" class="form-control unicode" placeholder="19.XXXXXX" style="border:1px solid #327da8;" readonly>
                                  </div>    
                          </div>
                          <div class="row form-group">
                              <label class="col-md-2 unicode">Longitude</label>
                                  <div class="col-md-7">
                                      <input type="text" name="lng" id="lng" value="{{$survey->lng}}" class="form-control unicode" placeholder="96.XXXXXX" style="border:1px solid #327da8;" readonly>
                                  </div>    
                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="row form-group" style="margin-top:20px;">
                              <label class="col-md-2 unicode">Township*</label>
                                  <div class="col-md-7">
                                      <input type="text" name="lng" id="lng" value="{{$survey->town_name}}" class="form-control unicode" style="border:1px solid #327da8;" readonly>
                                  </div>    
                          </div>
                          <div class="row form-group" style="margin-top:20px;">
                              <label class="col-md-2 unicode">Address*</label>
                                  <div class="col-md-7">
                                      <textarea class="form-control" id="address" name="address" style="border:1px solid #327da8;" readonly>{{$survey->address}}</textarea>
                                  </div>    
                          </div>
                          <div class="row form-group" style="margin-top:20px;">
                              <label class="col-md-2 unicode">Remark</label>
                                  <div class="col-md-7">
                                      <textarea class="form-control" id="remark" name="remark" style="border:1px solid #327da8;" readonly>{{$survey->remark}}</textarea>
                                  </div>    
                          </div>
                      </div>
                  </div>
                
              </div>

              <div id="install_photos" class="tabcontent">
                  <div class="row">
                      @foreach($photos as $key=>$photo)
                        <div class="col-md-3">
                          <img src="{{ asset($photo->path.$photo->photo_name) }}" alt="photo" width="200px" height="200px" style="border-radius:10px;" id="image{{++$key}}">
                        </div> 
                      @endforeach
                  </div>
                  
              </div>

              <div id="assign_teams" class="tabcontent">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="row form-group" style="margin-top:20px;">
                              <label class="col-md-3 unicode">Teams*</label>
                                  <div class="col-md-7">
                                    @if($assign != null)
                                      <input type="text" name="lng" id="lng" value="{{$assign->group_name}}" class="form-control unicode" style="border:1px solid #327da8;" readonly>
                                     @else 
                                      <input type="text" name="lng" id="lng" class="form-control unicode" style="border:1px solid #327da8;" readonly>
                                     @endif
                                  </div>    
                          </div>
                          <div class="row form-group">
                              <label class="col-md-3 unicode">Assign Date</label>
                                  <div class="col-md-7">
                                    @if($assign != null)
                                      @if($assign->assign_date)
                                        <input type="text" name="assign_date" id="assign_date" class="form-control unicode" style="border:1px solid #327da8;" value="{{date('d-m-Y',strtotime($assign->assign_date))}}" readonly>
                                      @else
                                        <input type="text" name="assign_date" id="assign_date" class="form-control unicode" style="border:1px solid #327da8;" readonly>
                                      @endif
                                    @else
                                      <input type="text" name="assign_date" id="assign_date" class="form-control unicode" style="border:1px solid #327da8;" readonly>
                                    @endif
                                  </div>    
                          </div>
                          <div class="row form-group">
                              <label class="col-md-3 unicode">Appointment Date</label>
                                  <div class="col-md-7">
                                    @if($assign != null)
                                      @if($assign->appoint_date)
                                        <input type="text" name="appoint_date" id="appoint_date" class="form-control unicode" style="border:1px solid #327da8;" value="{{date('d-m-Y',strtotime($assign->appoint_date))}}" readonly>
                                        @else
                                          <input type="text" name="appoint_date" id="appoint_date" class="form-control unicode" style="border:1px solid #327da8;" readonly>
                                        @endif
                                      @else
                                        <input type="text" name="appoint_date" id="appoint_date" class="form-control unicode" style="border:1px solid #327da8;" readonly>
                                      @endif
                                  </div>    
                          </div>
                      </div>
                  </div>
                  <div>
                      
                  </div>
                 
                  
              </div>
              @if(count($survey_install_items)>0)    
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

                  @foreach($survey_install_items as $key=>$install_item)
                    <div class="row options form-group" id="actualField0">
                      <!-- <div class="col-md-2"></div> -->
                      <div class="col-md-4">
                          
                          <input id="actual_item0" type="text" name="actual_item" placeholder="Items" class="actual_item form-control" style="border:1px solid #327da8;" value="{{$install_item->model}}" readonly>

                      </div>
                      <div class="col-md-2">
                          
                          <input id="unit0" type="text" name="unit[]" placeholder="unit" class="unit form-control" style="border:1px solid #327da8;" value="{{$install_item->unit}}" readonly>
                          
                      </div>
                      <div class="col-md-2">
                          
                          <input id="actual_qty0" type="number" name="actual_qty[]" placeholder="Qty" class="actual_qty form-control" value="{{$install_item->qty}}" style="border:1px solid #327da8;">
                      </div>
                      <div class="col-md-2">
                         
                          <input id="price0" type="text" name="price[]" placeholder="Price" class="price form-control" style="border:1px solid #327da8;text-align: right;" value="{{number_format($install_item->item_price)}}" readonly>
                      </div>
                      <div class="col-md-2">
                         
                          <input id="amount0" type="text" name="amount[]" placeholder="Amount" class="amount form-control" style="border:1px solid #327da8;text-align: right;" value="{{number_format($install_item->amount)}}" readonly>
                      </div>
                      
                  </div>
                  @endforeach
                      <div id="actual_row_container" style="margin-top:20px">
                          
                      
                      <div class="row form-group">
                          <div class="col-md-8"></div>
                          <div class="col-md-2" style="text-align:right;">
                              <label>Sub Total</label>
                          </div>
                          <div class="col-md-2">
                              <input id="sub_total" type="text" name="sub_total" placeholder="Amount" class="sub_total form-control" style="border:1px solid #327da8;text-align: right;" value="{{number_format($amounts->sub_total)}}" readonly>
                          </div>
                      </div>
                      <div class="row form-group">
                          <div class="col-md-8"></div>
                          <div class="col-md-2" style="text-align: right;">
                              
                              <label>Install Charge</label>
                          </div>
                          
                          <div class="col-md-2">
                              <input id="install_charge" type="text" name="install_charge" placeholder="Amount" class="install_charge form-control" style="border:1px solid #327da8;text-align: right;" value="{{number_format($amounts->install_charge)}}" readonly>
                          </div>
                      </div>
                      <div class="row form-group">
                          <div class="col-md-8"></div>
                          <div class="col-md-2" style="text-align:right;">
                              <label>Cloud Service</label>
                          </div>
                          
                          
                          <div class="col-md-2">
                              <input id="cloud_charge" type="text" name="cloud_charge" placeholder="Amount" class="cloud_charge form-control" style="border:1px solid #327da8;text-align: right;" value="{{number_format($amounts->cloud_charge)}}" readonly>
                          </div>
                      </div>
                      <div class="row form-group">
                          <div class="col-md-8"></div>
                          <div class="col-md-2" style="text-align:right;">
                              <label>Cabling Charge</label>
                          </div>
                          
                          
                          <div class="col-md-2">
                              <input id="cloud_charge" type="text" name="cloud_charge" placeholder="Amount" class="cloud_charge form-control" style="border:1px solid #327da8;text-align: right;" value="{{number_format($amounts->cabling_charge)}}" readonly>
                          </div>
                      </div>
                      <div class="row form-group">
                          <div class="col-md-8"></div>
                          <div class="col-md-2" style="text-align:right;">
                              <label>Discount</label>
                          </div>
                          
                          <div class="col-md-2">
                              <input id="discount" type="text" name="discount" placeholder="Amount" class="discount form-control" style="border:1px solid #327da8;text-align: right;" value="{{number_format($amounts->discount)}}" readonly>
                          </div>
                      </div>
                      <div class="row form-group">
                          <div class="col-md-8"></div>
                          <div class="col-md-2" style="text-align:right;"> 
                              <label>Total Amount</label>
                          </div>
                          
                          <div class="col-md-2">
                              <input id="total_amt" type="text" name="total_amt" placeholder="Amount" class="total_amt form-control" style="border:1px solid #327da8;text-align: right;" readonly value="{{number_format($amounts->total_amt)}}">
                          </div>
                      </div>
                     
                  </div>
            </div>
            @endif

            <div id="map" class="tabcontent">
              <div id="customer_map" style="width:100%;height:400px;margin-bottom: 20px;"></div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop

@section('js')
<script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd-rizPtjtbmnJbTozn8ip7lPWFuyWaG8&callback=initMap&libraries=&v=weekly"
      defer
    ></script>

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
      
    }

    var locations = <?php print_r(json_encode($survey)) ?>;
     // console.log(locations.viewBranch.name);
     let baseurl = "<?php echo URL('/') ?>";

    let map;

     function initMap() {
        map = new google.maps.Map(document.getElementById("customer_map"), {
            center: new google.maps.LatLng(locations.lat,locations.lng),
            zoom: 13,
         });

        // The marker, positioned at Uluru
        // const marker = new google.maps.Marker({
        //   position: baseurl,
        //   map: map,
        // });

            const name = 'Name';
            const contentString =
            '<div id="content" style="width:250px">' +
            '<div id="siteNotice">' +
            '</div><br>' +
            // '<h5 id="firstHeading" class="firstHeading">'+ value.name +'</h5><hr>' +
            // '<img id="theImg" src="'+baseurl+'/uploads/employeePhoto/'+locations.photo+'" style="width:50px;height:50px;margin-left:100px;"/><br>'+
            '<div id="bodyContent" style="margin-top:10px;">' +
            '<p> Name : '+locations.name+'</p>'+
            '<p> Phone No : '+locations.phone_no+'</p>'+
            '<p> Township : '+locations.town_name+'</p>'+
            '<p> Address : '+locations.address+'</p>'+
            
            // '<p><a target="_bank" href="'+baseurl+'/employee/'+locations.id+'">' +
            // "View more</a> " +"</p>" +
            "</div>" +
            "</div><br>";

            const infowindow = new google.maps.InfoWindow({
                content:contentString
            });
            const marker = new google.maps.Marker({
              position: new google.maps.LatLng(locations.lat, locations.lng),
              icon: baseurl+"/uploads/home1.png",
              title:name,
              map: map,
              optimized: true 
            });

            marker.addListener("click", () => {
                infowindow.open(map, marker);
            });
      }
</script>
@stop