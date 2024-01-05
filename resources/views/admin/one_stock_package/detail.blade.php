@extends('adminlte::page')

@section('title', 'Customer')

@section('content_header')
    <style type="text/css">
    </style>
@stop
@section('content')
    <div class="container-fluid">
         <div class="card">
            <div class="card-header">
                <div class="row d-flex justify-content-between">
                    <h5 style="color: #009879;">Customer Detail</h5>
                   <a class="btn btn-success unicode" href="{{route('package_customers')}}">Back</a>
                </div>
                
            </div>
             <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <img src="{{ asset('avater.jpg')}}" class="img img-fluid img-thumbnail detail-img" style="width: 200px;height: 200px;">
                    </div>
                    <div class="col-md-8">
                            <div class="row mt-3 col-md-12">
                            @foreach($customer_package as $package)
                            <div class="col-md-3">
                                <div class="card bg-light p-3  shadow-md" style="border: 1px solid #4d8fc6">
                                    <div class="d-flex">
                                        <div class="col-md-3">
                                            @if($package->type == 'home')
                                            <i class="fas fa-home" style="color:#4d8fc6"></i>
                                            @elseif($package->type == 'shop')
                                            <i class="fas fa-store" style="color:#3FC9C1"></i>
                                            @else
                                            <i class="fas fa-hotel" style="color:#814786"></i>
                                            @endif
                                        </div>
                                        <div class="col-md-9">
                                            @if($package->type == 'home')
                                            <div class="text text-primary mb-2">{{Str::upper($package->type)}}</div>
                                            <div  style="color:#4d8fc6">{{$package->count}}</div>
                                            @elseif($package->type == 'shop')
                                            <div class="text mb-2" style="color:#3FC9C1">{{Str::upper($package->type)}}</div>
                                            <div  style="color:#3FC9C1">{{$package->count}}</div>
                                            @else
                                            <div class="text mb-2" style="color:#814786">{{Str::upper($package->type)}}</div>
                                            <div  style="color:#814786">{{$package->count}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                <div class="tab">
                    <button class="tablinks"  id="customer" onclick="openTab(event, 'customer_info')" active>Customer Information</button>
                    <button class="tablinks" onclick="openTab(event, 'package_list')">Package List</button>
                    <button class="tablinks" onclick="openTab(event, 'map')">View Map</button>
                </div>
                <div id="customer_info" class="tabcontent">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Phone Number</th>
                                <th>Township</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                           <tr>
                               <td>{{$customer->name}}</td>
                               <td>{{$customer->phone_no}}</td>
                               <td>{{$customer->town_name}}</td>
                               <td>{{$customer->address}}</td>
                           </tr>
                        </tbody>
                    </table>
                </div>
                </div>
                <div id="package_list" class="tabcontent">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Install Location</th>
                                <th>Packages</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer_package as $package)
                           <tr>
                               <td>{{Str::upper($package->type)}}</td>
                               <td>
                                   @foreach(getPackageList($package->type,$customer->id) as $package_list)
                                   <span class="btn badge bg-success">{{$package_list->package}}</span>
                                   @endforeach
                               </td>
                           </tr>
                           @endforeach
                        </tbody>
                    </table>
                </div>
                </div>

                <div id="map" class="tabcontent">
                     <div id="customer_map" style="width:100%;height:400px;margin-bottom: 20px;"></div>
                </div>
             </div>
         </div>
    </div>
@stop 

@section('css')

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

    });

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

      function initMap() {

        var customer_data = <?php print_r(json_encode($customer)) ?>;
         let baseurl = "<?php echo URL('/') ?>";

        let map;

       if (customer_data.lat == null && locations.lng == null) {
           var lat = 19.7546006;
           var lng = 96.2032954;
         }else{
           var lat = customer_data.lat;
           var lng = customer_data.lng;
         }

            map = new google.maps.Map(document.getElementById("customer_map"), {
                center: new google.maps.LatLng(lat,lng),
                zoom: 13,
             });

                const name = 'Name';
                const contentString =
                '<div id="content" style="width:250px">' +
                '<div id="siteNotice">' +
                '</div><br>' +
                '<div id="bodyContent" style="margin-top:10px;">' +
                '<p> Name : '+customer_data.name+'</p>'+
                '<p> Phone No : '+customer_data.phone_no+'</p>'+
                '<p> Township : '+customer_data.town_name+'</p>'+
                '<p> Address : '+customer_data.address+'</p>'+
                "</div>" +
                "</div><br>";

                const infowindow = new google.maps.InfoWindow({
                    content:contentString
                });
                const marker = new google.maps.Marker({
                  position: new google.maps.LatLng(customer_data.lat, customer_data.lng),
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