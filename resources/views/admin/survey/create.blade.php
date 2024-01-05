 @extends('adminlte::page')

@section('title', 'Survey ')

@section('content_header')

@stop

@section('content')
<div class="container-fluid">
  <div class="fade-in">
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h4>Survey Form</h4></div>
            <form action="{{route('surveys.store')}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
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
                        <button class="tablinks"  id="customer" active>Customer Information</button>
                        <button class="tablinks" id="install_photo">Install Photos</button>
                        <button class="tablinks" id="assign_team">Assign Team</button>
                        <button class="tablinks" id="install_material">Install Materials</button>
                    </div>
                    <div id="customer_info" class="tabcontent">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <div class="row form-group" style="margin-top:20px;">
                                    <label class="col-md-2 unicode">Name*</label>
                                        <div class="col-md-7 {{ $errors->first('name', 'has-error') }}">
                                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control unicode" placeholder="Mg Mg" style="border:1px solid #327da8;">
                                        </div>    
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-2 unicode">Phone No*</label>
                                        <div class="col-md-7 {{ $errors->first('name', 'has-error') }}">
                                            <input type="number" name="ph_no" id="ph_no" value="{{ old('ph_no') }}" class="form-control unicode" placeholder="09XXXXXXXXX" style="border:1px solid #327da8;">
                                        </div>    
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-2 unicode">Latitude</label>
                                        <div class="col-md-7">
                                            <input type="text" name="lat" id="lat" value="{{ old('lat') }}" class="form-control unicode" placeholder="19.XXXXXX" style="border:1px solid #327da8;">
                                        </div>    
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-2 unicode">Longitude</label>
                                        <div class="col-md-7">
                                            <input type="text" name="lng" id="lng" value="{{ old('lng') }}" class="form-control unicode" placeholder="96.XXXXXX" style="border:1px solid #327da8;">
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
                                                @foreach($townships as $key=>$township)
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
                                <div class="row form-group" style="margin-top:20px;">
                                    <label class="col-md-2 unicode">Remark</label>
                                        <div class="col-md-7">
                                            <textarea class="form-control" id="remark" name="remark" style="border:1px solid #327da8;"></textarea>
                                        </div>    
                                </div>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <a class="btn btn-success" id="cust_next">Next</a>
                        </div>
                    </div>

                    <div id="install_photos" class="tabcontent">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group my-2">
                                <input type="file" id="images" name="images[]" class="form-control"
                                accept="image/jpg, image/jpeg, image/png" onchange="readURL2(this);" multiple required style="margin-right:3px; border:1px solid #327da8;">
                                <button type="button" onclick="addImageField()" class="btn btn-primary"><i class="fa fa-plus"></i></button>
                                </div>
                                <div id="images_container"></div>
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

                    <div id="assign_teams" class="tabcontent">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row form-group" style="margin-top:20px;">
                                    <label class="col-md-3 unicode">Teams*</label>
                                        <div class="col-md-7">
                                            <select class="form-control" id="team_id" name="team_id" style="border:1px solid #327da8;">
                                                <option value="">Select Teams</option>
                                                @foreach($teams as $key=>$team)
                                                <option value="{{$team->id}}">{{$team->group_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>    
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-3 unicode">Assign Date</label>
                                        <div class="col-md-7">
                                            <input type="text" name="assign_date" id="assign_date" class="form-control unicode" style="border:1px solid #327da8;" value="{{date('d-m-Y')}}">
                                        </div>    
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-3 unicode">Appointment Date</label>
                                        <div class="col-md-7">
                                            <input type="text" name="appoint_date" id="appoint_date" class="form-control unicode" style="border:1px solid #327da8;" value="{{date('d-m-Y')}}">
                                        </div>    
                                </div>
                            </div>
                        </div>
                        <div>
                            
                        </div>
                       
                        <div class="row">
                            <div class="col-md-6">
                                <a class="btn btn-info" onclick="openTab(event, 'install_photos')" style="float:left;">Back</a>
                            </div>
                            
                            <div class="col-md-6">
                                <a class="btn btn-success" id="assign_next" style="float:right;">Next</a>
                            </div>
                            

                        </div>
                    </div>
                      
                    <div id="install_materials" class="tabcontent">
                            <div class="row" style="text-align: right;">
                                <div class="col-md-11">
                                    
                                </div>
                                <div>
                                    <button type="button" onclick="addActualRow()" class="btn btn-primary"><i class="fa fa-plus" style="float:right;"></i></button>
                                </div>
                                    
                            </div>
                            <div class="row options" id="actualField0">
                              
                            <div class="col-md-3">
                                <label>Materials</label>
                                <input id="actual_item0" type="text" name="actual_item[]" placeholder="Items" class="actual_item form-control" style="border:1px solid #327da8;">
                                <input type="hidden" name="actual_items" id="actual_item" value="{{json_encode($items)}}">
                                <input type="hidden" id="install_amt0" name="install_amt[]" class="install_amt">
                                <input type="hidden" id="cat_id0" name="cat_id[]" class="cat_id">
                                <input type="hidden" id="cat_price0" name="cat_price[]" class="cat_price">
                            </div>
                            <div class="col-md-2">
                                <label>Unit</label>
                                <input id="unit0" type="text" name="unit[]" placeholder="unit" class="unit form-control" style="border:1px solid #327da8;" readonly>
                                
                            </div>
                            <div class="col-md-2">
                                <label>Qty</label>
                                <input id="actual_qty0" type="number" name="actual_qty[]" placeholder="Qty" value="1" data-id="0" class="actual_qty form-control" style="border:1px solid #327da8;">
                            </div>
                            <div class="col-md-2">
                                <label>Price</label>
                                <input id="price0" type="number" name="price[]" placeholder="Price" class="price form-control" style="border:1px solid #327da8;" readonly>
                            </div>
                            <div class="col-md-2">
                                <label>Amount</label>
                                <input id="amount0" type="number" name="amount[]" placeholder="Amount" class="amount form-control" style="border:1px solid #327da8;text-align: right;" readonly>
                            </div>
                            <div class="col-md-1" style="margin-top: 35px;">
                                <button type="button" onclick="removeRow(0)" class="btn btn-danger"><i class="fa fa-minus"></i></button>
                            </div>
                            
                        </div>
                            <div id="actual_row_container" style="margin-top:20px">
                                
                            </div>
                            <div class="row form-group">
                                <div class="col-md-7"></div>
                                <div class="col-md-2" style="text-align:right;">
                                    <label>Sub Total</label>
                                </div>
                                <div class="col-md-2">
                                    <input id="sub_total" type="number" name="sub_total" placeholder="Amount" class="sub_total form-control" style="border:1px solid #327da8;text-align: right;" readonly>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-7"></div>
                                <div class="col-md-2" style="text-align: right;">
                                    
                                    <label>Install Charge</label>
                                </div>
                                
                                <div class="col-md-2">
                                    <input id="install_charge" type="number" name="install_charge" placeholder="Amount" class="install_charge form-control" style="border:1px solid #327da8;text-align: right;" readonly>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-5"></div>
                                <div class="col-md-2" style="text-align:right;margin-left: 137px;">
                                    <input type="checkbox" name="cloud_check" style="width:20px;height: 20px;margin-top: 7px;" id="cloud_check">
                                    
                                </div>
                                <div>
                                    <label>Cloud Service</label>
                                </div>
                                
                                
                                <div class="col-md-2">
                                    <input id="cloud_charge" type="number" name="cloud_charge" placeholder="Amount" class="cloud_charge form-control" style="border:1px solid #327da8;text-align: right;" readonly>
                                </div>
                            </div>
                            <div class="row form-group" >
                                <div class="col-md-7"></div>
                                <div class="col-md-2" style="text-align: right;">
                                    
                                    <label>Cabling Charge</label>
                                </div>
                                
                                <div class="col-md-2">
                                    <input id="cabling_charge" type="number" name="cabling_charge" placeholder="Cabling Charge" class="cabling_charge form-control" style="border:1px solid #327da8;text-align: right;">
                                </div>
                            </div>
                            
                            <div class="row form-group">
                                <div class="col-md-7"></div>
                                <div class="col-md-2" style="text-align:right;">
                                    <label>Discount</label>
                                </div>
                                
                                <div class="col-md-2">
                                    <input id="discount" type="number" name="discount" placeholder="Amount" class="discount form-control" style="border:1px solid #327da8;text-align: right;">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-7"></div>
                                <div class="col-md-2" style="text-align:right;"> 
                                    <label>Total Amount</label>
                                </div>
                                
                                <div class="col-md-2">
                                    <input id="total_amt" type="number" name="total_amt" placeholder="Amount" class="total_amt form-control" style="border:1px solid #327da8;text-align: right;" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <a class="btn btn-info" onclick="openTab(event, 'assign_teams')" style="float:left;">Back</a>
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
<script src="{{asset('js/bootstrap-datepicker.min.js')}}"></script>

<script>
    $(function () {
                $("#assign_date").datepicker({ format: 'dd-mm-yyyy' });
                $("#appoint_date").datepicker({ format: 'dd-mm-yyyy' });
        });

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
      if (tabName == 'install_photos') {
        $("#install_photo").addClass("active");
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
                document.getElementById('install_photos').style.display = "block";
                $("#install_photo").addClass("active");
            }
        });

        $('#photo_next').on('click',function(){
            var img = $('#images').val();
            if (!img) {
                alert('Image empty!');
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
                document.getElementById('assign_teams').style.display = "block";
                 $("#assign_team").addClass("active");
            }

            
        });

        $('#assign_next').on('click',function(){
            // var team = $("#team_id option:selected" ).text();
            // if (team == 'Select Teams') {
            //     alert('Team empty!');
            // }else{

            //     var i, tabcontent, tablinks;
            //       tabcontent = document.getElementsByClassName("tabcontent");
            //       for (i = 0; i < tabcontent.length; i++) {
            //         tabcontent[i].style.display = "none";
            //       }
            //       tablinks = document.getElementsByClassName("tablinks");
            //           for (i = 0; i < tablinks.length; i++) {
            //             tablinks[i].className = tablinks[i].className.replace(" active", "");
            //           }
            //     document.getElementById('install_materials').style.display = "block";
            //      $("#install_material").addClass("active"); 
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
        function addActualRow(){
            j++;
            let html = '<div class="row options" id="actualField'+j+'"><div class="col-md-3 form-group"><input type="hidden" id="install_amt'+j+'" class="install_amt" name="install_amt[]"><input type="hidden" id="cat_id'+j+'" name="cat_id[]" class="cat_id"><input type="hidden" id="cat_price'+j+'" name="cat_price[]" class="cat_price"><input id="actual_item'+j+'" type="text" name="actual_item[]" placeholder="Items" class="actual_item form-control" style="border:1px solid #327da8;"></div><div class="col-md-2"><input id="unit'+j+'" type="text" name="unit[]" placeholder="unit" class="unit form-control" style="border:1px solid #327da8;" readonly></div><div class="col-md-2"><input id="actual_qty'+j+'" type="number" data-id="'+j+'" name="actual_qty[]" placeholder="Qty" value="1" class="actual_qty form-control" style="border:1px solid #327da8;"></div><div class="col-md-2"><input id="price'+j+'" type="number" name="price[]" placeholder="Price" class="price form-control" style="border:1px solid #327da8;" readonly></div><div class="col-md-2"><input id="amount'+j+'" type="number" name="amount[]" placeholder="Amount" class="amount form-control" style="border:1px solid #327da8;text-align:right" readonly></div><div class="col-md-1"><button type="button" onclick="removeRow('+j+')" class="btn btn-danger"><i class="fa fa-minus"></i></button></div></div>'
            $("#actual_row_container").append(html);

            actual_autocomplete(document.getElementById("actual_item"+j), data);

            $(".actual_qty").on("change",function(){
                var id = $(this).attr('data-id');
                var qty = $(this).val();
                // alert($(this).closest('tr').find('input[type=checkbox]').val());
                cal_install_charge();

                var price = $(this).closest("div.options").find("input[name='price[]']").val();
                var amt = qty * price;

                var val = $(this).closest("div.options").find("input[name='amount']").attr('id');
                // alert(val);
                $("#amount"+id).val(amt);

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
        // console.log(data);
        actual_autocomplete(document.getElementById("actual_item0"), data);

            var k = 0;
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

        

        $("#actual_qty0").on("change",function(){
                var qty = $(this).val();
                cal_install_charge();
                // var rowindex = $(this).closest('tr').attr("data_index");
                var price = $("#price0").val();
                var amt = qty * price;

                $("#amount0").val(amt);
                var sum = 0;
                var total = 0;
                $('.amount').each(function () {
                    var amt = parseInt(this.value);
                    total = total + amt;
                });
                $('#sub_total').val(total);
                calculate_total();
                
        });

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

        $('#discount').on('change', function() {
            calculate_total();
        });

        $('#cabling_charge').on('change', function() {
            calculate_total();
        });
        

        function calculate_total() {
        var sub_total = $('#sub_total').val() == "" || $('#sub_total').val() == null ? 0 : $('#sub_total').val();
        
        var install_charge = $('#install_charge').val() == "" || $('#install_charge').val() == null ? 0 : $('#install_charge').val();

        var cloud_charge = $('#cloud_charge').val() == "" || $('#cloud_charge').val() == null ? 0 : $('#cloud_charge').val();

        var cabling_charge = $('#cabling_charge').val() == "" || $('#cabling_charge').val() == null ? 0 : $('#cabling_charge').val();

        var total = parseInt(sub_total) + parseInt(install_charge) + parseInt(cloud_charge) + parseInt(cabling_charge);

        var discount = $('#discount').val() == "" || $('#discount').val() == null ? 0 : $('#discount').val();
        
        var net_pay = parseInt(total) - parseInt(discount);

        $('#total_amt').val(net_pay);

    }


        function removeRow(id){
            // var total_amt = parseInt($('#sub_total').val());
            // // alert(total_amt);
            // // alert(total_amt);
            // var remove_amt = $("#actual_amount"+id).val();
            // // alert(id);  
            // // var remove_amt = $("#"+id).val();
            // // alert(remove_amt);
            // var net_amt = total_amt - remove_amt; 
            //  $('#sub_total').val(net_amt);

            // if (total_amt != 0 && id != 0) {
            //     // alert('hi');
            //     $('#actualField'+id).remove();
            // }else if (total_amt != 0 && id == 0) {
            //     $('#actualField'+id).remove();
            // }else{
            //     return;
            // }
            // $('#actualField'+id).remove();
            // $('#break'+id).remove();
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