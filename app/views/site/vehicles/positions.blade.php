@extends('layouts.dashboard-base')

@section('content')
	<div class="row">
		<div class="col-lg-6">
			<h2 class="page-subtitle">Vehículos <small><a href="javascript:void(0)" class="displayAllMarkers">ver todos</a></small></h2>

            <table class="table table-striped table-bordered table-hover" id="all-elements-datatable">
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Últ. Pos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $vehicle)
                    <tr class="">
                        <td>{{$vehicle->plate}}</td>
                        <td>{{(is_null($vehicle->hasLastPosition())) ? '-' : $vehicle->hasLastPosition()->getCreatedAt()}}</td>
                        <td class="center">
                            @if(!is_null($vehicle->hasLastPosition()))
                            <a class="iris-link vehicleMarker" href="javascript:void(0)" title="ver la última posicion registrada" lat="{{$vehicle->locations->first()->lat}}" lng="{{$vehicle->locations->first()->lng}}" plate="{{$vehicle->plate}}" icon="{{URL::route('getHome')}}/assets/images/car-rojo_25.png" id="{{$vehicle->id}}" path_link="{{ (!is_null($vehicle->hasLocationHistory())) ? route('getVehiclePathByDate', $vehicle->plate) : ''  }}" route_link="{{ (!is_null($vehicle->hasTrackingRoute())) ? route('getTrackingRouteById', $vehicle->hasTrackingRoute()->id) : '' }}">
                                <i class="fa fa-eye fa-fw"></i>
                            </a>
                            @endif
                            @if(!is_null($vehicle->hasTrackingRoute()))
                            <a class="iris-link" href="{{ route('getTrackingRouteById', $vehicle->hasTrackingRoute()->id) }}" title="ver la ruta de hoy">
                                <i class="fa fa-map-marker fa-fw"></i>
                            </a>
                            @endif
                            @if(!is_null($vehicle->hasLocationHistory()))
                            <a class="iris-link" href="{{ route('getVehiclePathByDate', $vehicle->plate) }}" title="ver el recorrido de hoy">
                                <i class="fa fa-road fa-fw"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

		</div>
		<div class="col-lg-6">
			<h2 class="page-subtitle">Mapa</h2>
            <div id="map-canvas">
                
            </div>
		</div>
	</div>
@stop

@section('js')
    <script src="https://maps.googleapis.com/maps/api/js?libraries=drawing,places,geometry"></script>

    @include('javascript.datatable-init')

    <script>
        var map;
        var markers = [];
        var allMarkers = [];
        var center = new google.maps.LatLng(-12.0879227, -77.0159834);

        function initialize() {
            var styles = [
              {
                stylers: [
                  { hue: "#607D8B" },
                  { saturation: -20 }
                ]
              },{
                featureType: "road",
                elementType: "geometry",
                stylers: [
                  { lightness: 100 },
                  { visibility: "simplified" }
                ]
              },{
                featureType: "road",
                elementType: "labels",
                stylers: [
                  { visibility: "on" }
                ]
              }
            ];

            var mapOptions = {
                zoom: 11,
                center: center,
                styles: styles,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

            // Adds a Places search box. Searching for a place will center the map on that
            // location.

            setListeners(); //incializa los listeners
            getAllVehicleMarkers();
        }

        function setListeners() //listeners
        {
            $('.vehicleMarker').click(function(){
                id = $(this).attr('id');
                lat = $(this).attr('lat');
                lng = $(this).attr('lng');
                plate = $(this).attr('plate');
                icon = $(this).attr('icon');
                route_link = $(this).attr('route_link');
                path_link = $(this).attr('path_link');
                data = [plate, bodyMakerPosition(plate, route_link, path_link)]; // title, body
                addMarker(lat, lng, data, icon);
            });

            $('.displayAllMarkers').click(function(){
                displayAll();
            });
        }

        function createMarker(lat, lng, data, icon) {

            var coordinate = new google.maps.LatLng(lat, lng);

            var marker = new google.maps.Marker({
                position: coordinate,
                map: map,
                icon:  icon,
                animation: google.maps.Animation.DROP,
                title: data[0]
            });

            var infowindow = new google.maps.InfoWindow({
                content: data[1]
            });

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map,marker);
            });

            return marker;
        }

        function addMarker(lat, lng, data, icon) {
            deleteMarkers();

            var coordinate = new google.maps.LatLng(lat, lng);

            var marker = new google.maps.Marker({
                position: coordinate,
                map: map,
                icon:  icon,
                animation: google.maps.Animation.DROP,
                title: data[0]
            });

            markers.push(marker);

            var infowindow = new google.maps.InfoWindow({
                content: data[1]
            });

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(map,marker);
            });

            map.setCenter(new google.maps.LatLng(lat, lng));
            map.setZoom(13);
            console.log(markers.length);
        }

        // Sets the map on all markers in the array.
        function setAllMap(map) {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }

        // Removes the markers from the map, but keeps them in the array.
        function clearMarkers() {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            for (var i = 0; i < allMarkers.length; i++) {
                allMarkers[i].setMap(null);
            }
        }

        // Deletes all markers in the array by removing references to them.
        function deleteMarkers() {
            clearMarkers();
            markers = [];
        }        

        function deleteAllMarkers() {
            clearMarkers();
            markers = [];
            allMarkers = [];
        }

        function getAllVehicleMarkers()
        {
            deleteAllMarkers();

            $(".vehicleMarker").each(function(index, obj){
                vid = $(obj).attr('id');
                lat = $(obj).attr('lat');
                lng = $(obj).attr('lng');
                plate = $(obj).attr('plate');
                icon = $(obj).attr('icon');
                route_link = $(this).attr('route_link');
                path_link = $(this).attr('path_link');
                data = [plate, bodyMakerPosition(plate, route_link, path_link)]; // title, body
                marker = createMarker(lat, lng, data, icon);
                allMarkers.push(marker);
            });
        }

        function displayAll()
        {
            clearMarkers();
            markers = allMarkers;
            console.log(allMarkers.length);
            setAllMap(map);
            map.setCenter(center);
            map.setZoom(11);
        }

        function bodyMakerPosition(plate, route_link, path_link)
        {
            if (typeof(route_link)==='undefined') route_link = '';
            if (typeof(path_link)==='undefined') path_link = '';
            var cad = "<div><h4>" + plate + "</h2>";

            if(route_link != '')
            {
                cad += "<a href='" + route_link + "' target=_blank>ver ruta</a><br>";
            }

            if(path_link != '')
            {
                console.log('entro');
                cad += "<a href='" + path_link + "' target=_blank>ver recorrido</a>";
            }

            cad += "</div>";
            return cad;
        }

        $(window).load(initialize);
    </script>
@stop