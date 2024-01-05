 @extends('adminlte::page')

@section('title', 'One Stop Package ')

@section('content_header')

@stop

@section('content')
<div class="container-fluid">
  <div class="fade-in">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h4>One Stop Package Form</h4></div>
            <form action="{{route('one_stop.store')}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="card-body">
                    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                      <div class="modal-content">
                  
                        <div class="modal-header">
                          <h5 class="modal-title h4" id="myExtraLargeModalLabel">Map</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div id="map" style="width:100%;height:600px;"></div>

                          <script>
                            // In the following example, markers appear when the user clicks on the map.
                            // The markers are stored in an array.
                            // The user can then click an option to hide, show or delete the markers.
                            let curentLat='';
                            let currentLng = '';
                            
                            let map;
                            let markers = [];

                            function initMap() {
                              const myLatLng = {  lat: 19.754438, lng: 96.202217 };
                              map = new google.maps.Map(document.getElementById("map"), {
                                zoom: 15,
                                center: myLatLng,
                                // mapTypeId: "terrain",
                              });
                              // This event listener will call addMarker() when the map is clicked.
                              map.addListener("click", (event) => {
                                addMarker(event.latLng);
                              });
                              // Adds a marker at the center of the map.
                              addMarker(myLatLng);
                            }

                            // Adds a marker to the map and push to the array.
                            function addMarker(location) {
                              if(string !=null || string !='' ){   
                                  var string = JSON.stringify(location);
                                  var slice =  location.toString().slice(1,-1);
                                  var myArray = slice.split(",");
                                  document.getElementById("lat").value = (!isNaN(parseFloat(myArray[0])))?parseFloat(myArray[0]):'';
                                  document.getElementById("lng").value =(!isNaN(parseFloat(myArray[1])))?parseFloat(myArray[1]):'';
                              }
                              deleteMarkers();
                              const marker = new google.maps.Marker({
                                position: location,
                                map: map,
                              });
                              markers.push(marker);
                            }

                            // Sets the map on all markers in the array.
                            function setMapOnAll(map) {
                              for (let i = 0; i < markers.length; i++) {
                                markers[i].setMap(map);
                              }
                            }

                            // Removes the markers from the map, but keeps them in the array.
                            function clearMarkers() {
                              setMapOnAll(null);
                            }

                            // Shows any markers currently in the array.
                            function showMarkers() {
                              setMapOnAll(map);
                            }

                            // Deletes all markers in the array by removing references to them.
                            function deleteMarkers() {
                              clearMarkers();
                              markers = [];
                            }

                          </script>
                          <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd-rizPtjtbmnJbTozn8ip7lPWFuyWaG8&callback=initMap&libraries=&v=weekly" async ></script>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-xs btn-success" data-dismiss="modal"><i class="btn-icon-prepend" data-feather="save"></i>Save</button>
                        </div>
                      </div>
                    </div>
                  </div>

                    <div class="tab">
                        <button class="tablinks"  id="customer" active disabled>Customer Information</button>
                        <button class="tablinks" id="install_package_info" disabled>Install Package</button>
                    </div>
                    <div id="customer_info" class="tabcontent">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row form-group" style="margin-top:20px;">
                                    <label class="col-md-2 unicode">Name*</label>
                                        <div class="col-md-7 {{ $errors->first('name', 'has-error') }}">
                                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control unicode" placeholder="Mg Mg" style="border:1px solid #327da8;" autocomplete="off">
                                        </div>    
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-2 unicode">Phone No*</label>
                                        <div class="col-md-7 {{ $errors->first('name', 'has-error') }}">
                                            <input type="number" name="ph_no" id="ph_no" value="{{ old('ph_no') }}" class="form-control unicode" placeholder="09XXXXXXXXX" style="border:1px solid #327da8;"  autocomplete="off">
                                        </div>    
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-2 unicode">Latitude</label>
                                        <div class="col-md-7">
                                            <input type="text" name="lat" id="lat" value="{{ old('lat') }}" class="form-control unicode" placeholder="19.XXXXXX" style="border:1px solid #327da8;"  autocomplete="off">
                                        </div>    
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-2 unicode">Longitude</label>
                                        <div class="col-md-7">
                                            <input type="text" name="lng" id="lng" value="{{ old('lng') }}" class="form-control unicode" placeholder="96.XXXXXX" style="border:1px solid #327da8;"  autocomplete="off">
                                        </div>    
                                </div>
                                <button type="button"  class="btn btn-sm  btn-primary" data-toggle="modal" data-target=".bd-example-modal-xl"><i data-feather="map-pin"></i> Map</button>
                  
                            </div>
                            <div class="col-md-6">
                                <div class="row form-group" style="margin-top:20px;">
                                    <label class="col-md-2 unicode">Township*</label>
                                        <div class="col-md-7">
                                            <select class="form-control" id="tsh_id" name="tsh_id" style="border:1px solid #327da8;">
                                                <option value="">Select Township</option>
                                                @foreach(get_townsips() as $key=>$township)
                                                <option value="{{$township->id}}">{{$township->town_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                </div>
                                <div class="row form-group" style="margin-top:20px;">
                                    <label class="col-md-2 unicode">Address*</label>
                                        <div class="col-md-7">
                                            <textarea class="form-control" id="address" name="address" style="border:1px solid #327da8;"></textarea>
                                        </div>    
                                </div>
                               
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <a class="btn btn-success" id="cust_next">Next</a>
                        </div>
                    </div>

                    <div id="install_package" class="tabcontent">
                        <div class="row form-group col-md-12" >
                          <div class="col-md-6">
                              <select class="form-control" id="install_type" name="install_type[]">
                                  <option value="home">Home</option>
                                 
                              </select>
                          </div>
                          <div  class="col-md-6">
                              <select class="form-control select2" id="home_package"  name="home_package[]" multiple >
                                <option value="CCTV">CCTV</option>
                                <option value="Smart Home">Smart Home</option>
                                <option value="mm-link Wifi">mm-link Wifi</option>
                                <option value="Fiber Internet">Fiber Internet</option>
                                <option value="Computer & Mobile">Computer & Mobile</option>
                                <option value="Electronic">Electronic</option>
                            </select>
                          </div>
                          
                        </div>
                        <div class="row form-group col-md-12" >
                          <div class="col-md-6">
                              <select class="form-control" id="install_type" name="install_type[]">
                                  <option value="shop">Shop</option>
                              </select>
                          </div>
                          <div  class="col-md-6">
                              <select class="form-control select2" id="shop_package"  name="shop_package[]" multiple >
                                <option value="CCTV">CCTV</option>
                                <option value="Smart Home">Smart Home</option>
                                <option value="mm-link Wifi">mm-link Wifi</option>
                                <option value="Fiber Internet">Fiber Internet</option>
                                <option value="Computer & Mobile">Computer & Mobile</option>
                                <option value="Electronic">Electronic</option>
                            </select>
                          </div>
                          
                        </div>
                        <div class="row form-group col-md-12" >
                          <div class="col-md-6">
                              <select class="form-control" id="install_type" name="install_type[]">
                                  <option value="office">Office</option>
                              </select>
                          </div>
                          <div  class="col-md-6">
                              <select class="form-control select2" id="office_package"  name="office_package[]" multiple >
                                <option value="CCTV">CCTV</option>
                                <option value="Smart Home">Smart Home</option>
                                <option value="mm-link Wifi">mm-link Wifi</option>
                                <option value="Fiber Internet">Fiber Internet</option>
                                <option value="Computer & Mobile">Computer & Mobile</option>
                                <option value="Electronic">Electronic</option>
                            </select>
                          </div>
                        </div>

                        <div class="row form-group col-md-12" >
                          <div class="col-md-6">
                              <label>Created Date</label>
                              <input type="text" name="created_date" class="form-control" id="created_date" value="{{date('d-m-Y')}}">
                          </div>
                          <div  class="col-md-6">
                              <label>Remark</label>
                              <textarea class="form-control" id="remark" name="remark" style="border:1px solid #327da8;"></textarea>
                          </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <a class="btn btn-info" onclick="openTab(event, 'customer_info')">Next</a>
                            </div>
                            <div class="col-md-6" style="text-align:right">
                                <button type="submit" class="btn btn-success unicode">Save</button>
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
<link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }}"/>

<link id="bsdp-css" href="https://unpkg.com/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
<style type="text/css">
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: black;
    }
    
    .select2-container--default .select2-selection--single .select2-selection_{
    height: 30px;
    position: absolute;
    top: 2px;
    right: 0px;
    left: 365px;
   
    }
</style>
@stop

@section('js')
<script type="text/javascript" src="{{ asset('select2/js/select2.min.js') }}"></script>
 <script type="text/javascript" src="{{ asset('jquery-ui.js') }}"></script>

 <script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>
<script>
    $(function () {
                $("#created_date").datepicker({ format: 'dd-mm-yyyy' });
        });

    $(document).ready(function(){
       document.getElementById("customer_info").style.display = "block"; 

     $("#customer").addClass("active");

        
     //    document.getElementById("install_package").style.display = "block"; 

     // $("#install_package_info").addClass("active");

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
      if (tabName == 'install_package') {
        $("#install_package_info").addClass("active");
      }

      if (tabName == 'assign_teams') {
        $("#assign_team").addClass("active");
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
                document.getElementById('install_package').style.display = "block";
                $("#install_package_info").addClass("active");
            }
        });

   });


    function removeImageField(id){
        $('#inputFile'+id).remove();
    }

    $('#home_package').select2({
        placeholder: 'Select Package',
        width:"100%"
        });

   $('#shop_package').select2({
        placeholder: 'Select Package',
        width:"100%"
        });

   $('#office_package').select2({
        placeholder: 'Select Package',
        width:"100%"
        });
</script>
@stop