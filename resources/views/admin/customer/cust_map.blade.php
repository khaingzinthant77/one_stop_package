@extends('adminlte::page')

@section('title', 'Customer Map')

@section('content')
  @section('content_header')
  <link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }}"/>
    <h5 style="color: blue;">Employee's Map</h5>
    <style type="text/css">
      /* Set the size of the div element that contains the map */
      #map {
        height: 800px;
        /* The height is 400 pixels */
        width: 100%;
        /* The width is the width of the web page */
      }

      .select2-container .select2-selection--single {
        box-sizing: border-box;
        cursor: pointer;
        display: block;
        height: 35px;
        user-select: none;
        -webkit-user-select: none; 
        }
        .select2-container--default .select2-selection--single .select2-selection_ {
        height: 30px;
        position: absolute;
        top: 2px;
        right: 0px;
        left: 365px;
        width: 100px;
        color: black; 
    }
    </style>

  @stop
  <div class="row">
          <div class="col-md-8">
            @php
              $keyword = (isset($_GET['keyword']))?$_GET['keyword']:'';
              $tsh_id = (isset($_GET['tsh_id']))?$_GET['tsh_id']:'';
            @endphp
            <form action="{{route('customer_map')}}" method="get" accept-charset="utf-8" class="form-horizontal">
              <div class="row form-group">
                <div class="col-md-3  col-xs-12  col-sm-12 input-group  mb-2 mb-md-0 d-md-none d-xl-flex">
                    <input type="text" name="keyword" class="form-control" value="{{ $keyword }}" placeholder="Search ...">
                    
                </div>
                
              
                <div class="col-md-3  col-xs-12 col-sm-12 input-group  mb-2 mb-md-0 d-md-none d-xl-flex">
                  <select class="form-control" name="tsh_id" id="tsh_id">
                      <option value="">Select Township</option>
                      @foreach($townships as $township)
                        <option value="{{$township->id}}" {{ (old('tsh_id',$tsh_id)==$township->id)?'selected':'' }}>
                            {{$township->town_name}}
                        </option>
                      @endforeach
                  </select>
                </div>

                
                
              </div> 
            </form>
          </div>
  </div>

    <div id="map"></div>
@stop 

@section('js')
  <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAd-rizPtjtbmnJbTozn8ip7lPWFuyWaG8&callback=initMap&libraries=&v=weekly"
      defer
    ></script>
    <script type="text/javascript" src="{{ asset('select2/js/select2.min.js') }}"></script>
  <script>
      $(function(){
        $('#tsh_id').change(function(){
          this.form.submit();
        });
        
      });
      var locations = <?php print_r(json_encode($locations)) ?>;
      // console.log(locations);
      let baseurl = "<?php echo URL('/') ?>";

      let map;
      if (locations.length != 0) {
        if (locations[0].lat == null && locations[0].lng == null) {
          var lat = 19.7546006;
          var lng = 96.2032954;
        }else{
          var lat = locations[0].lat;
          var lng = locations[0].lng;
        }
      }else{
        var lat = 19.7546006;
        var lng = 96.2032954;
      }
      
      // Initialize and add the map
      function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: new google.maps.LatLng(lat,lng),
            zoom: 16,
         });

        // The marker, positioned at Uluru
        // const marker = new google.maps.Marker({
        //   position: baseurl,
        //   map: map,
        // });

        $.each( locations, function( index, value ){
          // console.log(value);
            const contentString =
            '<div id="content" style="width:250px">' +
            '<div id="siteNotice">' +
            '</div><br>' +
            // '<h5 id="firstHeading" class="firstHeading">'+ value.name +'</h5><hr>' +
           
            '<div id="bodyContent" style="margin-top:10px;">' +
            '<p> Name : '+value.name+'</p>'+
            '<p> Phone No : '+value.town_name+'</p>'+
            '<p> Township : '+value.town_name+'</p>'+
            '<p> Address : '+value.address+'</p>'+
             '<p><a target="_bank" href="'+baseurl+'/customer/'+value.id+'">' +
            "View more</a> " +"</p>" +
            
            "</div>" +
            "</div><br>";

            const infowindow = new google.maps.InfoWindow({
                content:contentString
            });
            const marker = new google.maps.Marker({
              position: new google.maps.LatLng(value.lat, value.lng),
              icon: baseurl+"/uploads/loc.png",
              title:name,
              map: map,
              optimized: true 
            });

            marker.addListener("click", () => {
                infowindow.open(map, marker);
            });
        });
      }

  
    </script>
@stop