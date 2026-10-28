{{--*/
    $tracking_route = $data;
    $user = $tracking_route->user;
    $tasks = $tracking_route->tasks;
    $locationHistoriesJSON = $user->locationHistories;
    $locationIncidences = $user->locationHistories()->whereNotNull('incident_type_id')->orderBy('created_at', 'DESC')->get();
    $hasLastPosition = ($tracking_route->hasLastPosition()) ? 'yes' : 'no';
    $lastLat = ($tracking_route->hasLastPosition()) ? $tracking_route->hasLastPosition()->lat : '';
    $lastLng = ($tracking_route->hasLastPosition()) ? $tracking_route->hasLastPosition()->lng : '';
    $tasksJSON = json_encode($tasks->toArray(),JSON_UNESCAPED_UNICODE); //se imprime directamente sobre el javascript
/*--}}



@extends('layouts.dashboard-base')

@section('content')
	<div class="row">
		<div class="col-lg-5">
			<h2 class="page-subtitle text-left">{{--Ruta del Usuario<br>--}}{{$user->getFullName()}}</h2>
            <div class="hidden" id="displayCustomers" iconProgrammed="{{asset('assets/images/place_purple_35.png')}}" iconPostponed="{{asset('assets/images/place_orange_35.png')}}" iconDone="{{asset('assets/images/place_green_35.png')}}" iconCancelled="{{asset('assets/images/place_gray_35.png')}}" iconReprogramed="{{asset('assets/images/place_pistacho_35.png')}}"></div>

            <div class="hidden" id="lastPosition" hasLastPosition="{{$hasLastPosition}}" lat="{{$lastLat}}" lng="{{$lastLng}}" plate="{{$data->plate}}" icon="{{URL::route('getHome')}}/assets/images/man_red_25.png" locationHistoriesJSON='{{$locationHistoriesJSON}}' {{--tasksJSON="{{$tasksJSON}}"--}}></div>

            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                <li class="active"><a href="#information" data-toggle="tab">Detalle</a></li>
                <li><a href="#tasks" data-toggle="tab">Clientes</a></li>
                <li><a href="#comments" data-toggle="tab">Comentarios</a></li>
                <li><a href="#incidents" data-toggle="tab">Incidencias</a></li>
            </ul>
            <div id="tab-content" class="tab-content">
                <div class="tab-pane active" id="information">

                    <div class="panel-group" id="information-panel">
                        <br>
                        <p>Toda la información refente a la ruta actual se muestra a continuación:</p>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#information-panel" href="#route-details-panel">Detalles del recorrido actual</a>
                                </h3>
                            </div>
                            <div id="route-details-panel" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <p><span class="iris-description-label">Código de la ruta:</span>{{$tracking_route->id}}</p>
                                    <p><span class="iris-description-label">Fecha de ejecución:</span>{{$tracking_route->getDate()}}</p>
                                    <p><span class="iris-description-label">Registrado el:</span>{{$tracking_route->created_at}}</p>
                                    <p><span class="iris-description-label">Actualizado el:</span>{{$tracking_route->updated_at}}</p>
                                    <div id="directionsTranscript"></div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#information-panel" href="#vehicle-details-panel">Detalles del usuario</a>
                                </h3>
                            </div>
                            <div id="vehicle-details-panel" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <p><span class="iris-description-label">Nombres:</span>{{$user->first_name}}</p>
                                    <p><span class="iris-description-label">Apellidos:</span>{{$user->last_name}}</p>
                                    <p><span class="iris-description-label">Tipo:</span>{{$user->type->name}}</p>
                                    <p><span class="iris-description-label">E-mail:</span>{{$user->email}}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="tab-pane" id="tasks">                    
                    <h3>Clientes</h3>
                    <p>los clientes a visitar en la presente ruta son:</p>
                    <div class="panel-group" id="tasks-panel">
                        @foreach( $tasks as $task)
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#tasks-panel" href="#customer_id_{{$task->customer->id}}">{{$task->customer->name}}</a>
                                        </h4>
                                    </div>
                                    <div id="customer_id_{{$task->customer->id}}" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <p><span class="iris-description-label2">Estado de tarea:</span>{{$task->state->name}}</p>
                                            <p><span class="iris-description-label2">Código de cliente:</span>{{$task->customer->customer_code}}</p>
                                            <p><span class="iris-description-label2">Dirección:</span>{{$task->customer->address}}</p>
                                            <p><span class="iris-description-label2">Teléfono:</span>{{$task->customer->phone}}</p>
                                            <p><span class="iris-description-label2">Detalle:</span>{{$task->description}}</p>
                                        </div>
                                    </div>
                                </div>                       
                        @endforeach
                    </div> 

                </div>
                <div class="tab-pane" id="comments">
                    <h3>Comentarios</h3>
                    @forelse ($tracking_route->comments as $comment)
                    <p><strong>{{$comment->user->getFullName()}}<br><small>{{$comment->getCreatedAt()}}</small></strong><br>{{$comment->comment}}</p>
                    @empty
                    <p>No hay comentarios</p>
                    @endforelse
                    <form action="{{ route('postCreateRouteComment') }}" method="POST">
                        <div class="form-group">
                            <label>Comentario</label>
                            <textarea class="form-control" rows="3" name="comment"></textarea>
                            <input type="hidden" name="tracking_route_id" value="{{$tracking_route->id}}">
                        </div>
                        <button type="submit" class="btn btn-default">Agregar comentario</button>
                    </form>
                </div>
                <div class="tab-pane" id="incidents">
                    <h3>Incidencias</h3>
                    @forelse ($locationIncidences as $incidence)
                    <p><strong>{{$incidence->incidentType->name}}<br><small>{{$incidence->getCreatedAt()}}</small></strong><br>{{$incidence->incident_description}}</p>
                    @empty
                    <p>No se registraron incidencias</p>
                    @endforelse
                </div>
            </div>

		</div>
		<div class="col-lg-7">
			<h2 class="page-subtitle">Mapa</h2>
            <div id="map-canvas">
                
            </div>
		</div>
	</div>
@stop

@section('js')

    <script src="https://maps.googleapis.com/maps/api/js?libraries=drawing,places,geometry"></script>

    <script>
        var apiKey = 'AIzaSyACCbYJOLaS_guP41OHX2LBP_2FQWf7__g';
        var map;
        var markers = [];
        var allMarkers = [];
        var vehiclePathCoordinates = [];
        var center = new google.maps.LatLng(-12.0879227, -77.0159834);

        //Native!!

        function handleNoGeolocation(errorFlag) {
            if (errorFlag) {
                var content = 'Error: El servicio de Geolocalización ha fallado.';
            } else {
                var content = 'Error: Tu navegador no soporta geolocalización.';
            }

            var options = {
                map: map,
                position: center,
                content: content
            };

            var infowindow = new google.maps.InfoWindow(options);
            mapObject.setCenter(options.position);
        }

        function initialize() {

            // Try HTML5 geolocation
            /*
            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                var center = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
            }, function() {
                handleNoGeolocation(true);
            });
            } else {
                // Browser doesn't support Geolocation
                handleNoGeolocation(false);
            }
            */

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

            setListeners();
            displayCustomers();
            //playDEMO();
            //setSuggestedRoute();
            //drawVehiclePath();
            displayLastPosition(); //Activar en version oficial
        }
        //End Native

        function setListeners() //listeners
        {
            $('#displayCustomers').click(function(){

                displayCustomers();
            });
        }

        function playDEMO()
        {
            DEMOsetVehiclePath();
        }

        function displayCustomers()
        {
            //tasksJSON = jQuery.parseJSON($('#lastPosition').attr('tasksJSON'));
            tasksJSON = {{$tasksJSON}};
            $.each(tasksJSON, function(i, task) {
                lat = task.customer.lat;
                lng = task.customer.lng;
                name = task.customer.name;
                state= task.state.name;
                switch (state) {
                    case 'Programada':
                        iconType = "iconProgrammed";
                        break;
                    case 'Realizada':
                        iconType = "iconDone";
                        break;
                    case 'Pospuesta':
                        iconType = "iconPostponed";
                        break;
                    case 'Cancelada':
                        iconType = "iconCancelled";
                        break;
                    case 'Reprogramar':
                        iconType = "iconReprogramed";
                        break;
                }

                icon = $('#displayCustomers').attr(iconType);

                data = ['', bodyMakerPositionCustomer(name, state)]; // title, body
                addMarker(lat, lng, data, icon);
            })
            //map.setCenter(center);
            map.setZoom(11);
        }

        function drawSuggestedRoute(origin, destination, waypoints)
        {
            var directionsService = new google.maps.DirectionsService();

            var directionsRequest = {
                //punto de origen
                origin: origin,
                //punto de destino
                destination: destination,
                travelMode: google.maps.DirectionsTravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC,
                //puntos de historicos paso si los hay
                waypoints: waypoints,
                optimizeWaypoints: true
            };

            directionsService.route(
                directionsRequest,
                function(response, status)
                {
                    if (status == google.maps.DirectionsStatus.OK)
                    {
                        var lineSymbol = {
                            path: 'M 0,-1 0,1',
                            strokeOpacity: 1,
                            scale: 3
                        };
                        directionsDisplay = new google.maps.DirectionsRenderer({
                            map: map,
                            directions: response,
                            suppressMarkers: true,
                            polylineOptions: {
                                strokeColor: '#3f51b5', // recorrido del vehiculo #424242 ruta sugerida #3f51b5
                                strokeOpacity: 1,
                                strokeWeight: 3
                            }
                        });

                        //directionsDisplay.setPanel(document.getElementById("directionsTranscript"));
                    }
                    else
                    {
                        $("#error").append("Unable to retrieve your route<br />");
                    }
                }
            );            
        }        

        function drawVehiclePath()
        {
            /*DEMO
            var directionsService = new google.maps.DirectionsService();

            var directionsRequest = {
                //punto de origen
                origin: origin,
                //punto de destino
                destination: destination,
                travelMode: google.maps.DirectionsTravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC,
                //puntos de historicos paso si los hay
                waypoints: waypoints,
                optimizeWaypoints: false
            };

            directionsService.route(
                directionsRequest,
                function(response, status)
                {
                    if (status == google.maps.DirectionsStatus.OK)
                    {
                        var lineSymbol = {
                            path: 'M 0,-1 0,1',
                            strokeOpacity: 1,
                            scale: 3
                        };
                        new google.maps.DirectionsRenderer({
                            map: map,
                            directions: response,
                            suppressMarkers: true,
                            polylineOptions: {
                                strokeColor: '#FFA000', // recorrido del vehiculo #424242 ruta sugerida #3f51b5
                                strokeOpacity: 0.8,
                                strokeWeight: 3,
                                zIndex: 100
                            }
                        });
                    }
                    else
                    {
                        $("#error").append("Unable to retrieve your route<br />");
                    }
                }
            );
            */
            locationHistoriesJSON = jQuery.parseJSON($('#lastPosition').attr('locationHistoriesJSON'));

            count = locationHistoriesJSON.length;

            origin = '';
            destination = '';
            array = [];

            $.each(locationHistoriesJSON, function(i, location) {
                lat = location.lat;
                lng = location.lng;
                array.push([lat, lng]);
            })

            //
            var vehiclePath = new google.maps.Polyline({
                path: latLngArrayer(array),
                geodesic: true,
                strokeColor: '#FFA000', // recorrido del vehiculo #424242 ruta sugerida #3f51b5
                strokeOpacity: 0.8,
                strokeWeight: 3,
                zIndex: 100
            });

            vehiclePath.setMap(map);
            //
            
            //batchSnapToRoad(array); 
   
        }

        function batchSnapToRoad(array)
        {
            while(array.length >= 1)
            {
                cutedArray = array.slice(0, 100);

                array = array.slice(cutedArray.length, array.length);
                /*
                $.get('https://roads.googleapis.com/v1/snapToRoads', {
                    interpolate: true,
                    key: apiKey,
                    path: latLngPiper(cutedArray)
                }, function(data) {
                    processSnapToRoadResponse(data);
                });*/

                $.ajax({
                    type: 'GET',
                    async: false,
                    url: "https://roads.googleapis.com/v1/snapToRoads",
                    data: {
                        interpolate: true,
                        key: apiKey,
                        path: latLngPiper(cutedArray)
                    }
                }).done(function(data) {
                    processSnapToRoadResponse(data);
                });
            }
            drawSnappedPolyline();
        }

        // Store snapped polyline returned by the snap-to-road method.
        function processSnapToRoadResponse(data) {
          for (var i = 0; i < data.snappedPoints.length; i++) {
            var latlng = new google.maps.LatLng(
                data.snappedPoints[i].location.latitude,
                data.snappedPoints[i].location.longitude);
            vehiclePathCoordinates.push(latlng);
            //placeIdArray.push(data.snappedPoints[i].placeId);
          }
        }

        // Draws the snapped polyline (after procesMath.sing snap-to-road response).
        function drawSnappedPolyline() {
          var snappedPolyline = new google.maps.Polyline({
            path: vehiclePathCoordinates,
            geodesic: true,
            strokeColor: '#FFA000', // recorrido del vehiculo #424242 ruta sugerida #3f51b5
            strokeOpacity: 0.8,
            strokeWeight: 3,
            zIndex: 100
          });

          snappedPolyline.setMap(map);
        }

        function setSuggestedRoute()
        {
            tasksJSON = jQuery.parseJSON($('#lastPosition').attr('tasksJSON'));

            count = tasksJSON.length;

            origin = '';
            destination = '';
            waypoints = [];

            $.each(tasksJSON, function(i, task) {
                lat = task.customer.lat;
                lng = task.customer.lng;
                latLngString = lat+","+lng;

                if(i == 0)
                {
                    origin = latLngString;
                }
                else if(i==(count-1))
                {
                    destination = latLngString;
                }else{
                    waypoints.push({location:latLngString,stopover:true});
                }
            })

            drawSuggestedRoute(origin, destination, waypoints);
        }

        function DEMOsetVehiclePath()
        {
            tasksJSON = jQuery.parseJSON($('#lastPosition').attr('tasksJSON'));
            lat = '';
            lng = '';

            count = tasksJSON.length;

            maxWaypoints = getRandomInt(2, count);

            origin = '';
            destination = '';
            waypoints = [];

            for (i = 0; i < maxWaypoints; i++) {
                lat = tasksJSON[i].customer.lat;
                lng = tasksJSON[i].customer.lng;
                latLngString = lat+","+lng;

                if(i == 0)
                {
                    origin = latLngString;
                }
                else if(i==(maxWaypoints-1))
                {
                    lat2 = tasksJSON[(i-1)].customer.lat;
                    lng2 = tasksJSON[(i-1)].customer.lng;
                    newPts = pointCreator(lat, lng, lat2, lng2, 1250);
                    latLngString = newPts[0]+","+newPts[1];
                    destination = latLngString;
                }else{
                    waypoints.push({location:latLngString,stopover:true});
                }
            }

            drawVehiclePath(origin, destination, waypoints);
            DEMOdisplayLastPosition(newPts[0], newPts[1]);
        }

        function getRandomInt(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        function deg2rad(angle) {
            //  discuss at: http://phpjs.org/functions/deg2rad/
            // original by: Enrique Gonzalez
            // improved by: Thomas Grainger (http://graingert.co.uk)
            //   example 1: deg2rad(45);
            //   returns 1: 0.7853981633974483

            return angle * .017453292519943295; // (angle / 180) * Math.PI;
        }

        function pointCreator(lat, lng, lat2, lng2, dist)
        {
            lat = parseFloat(lat);
            lng = parseFloat(lng);
            lat2 = parseFloat(lat2);
            lng2 = parseFloat(lng2);
            dist = parseFloat(dist);
            radius = 6378100; // radius of earth in meters
            latDist = lat - lat2;
            lngDist = lng - lng2;
            latDistRad = deg2rad(latDist);
            lngDistRad = deg2rad(lngDist);
            sinLatD = Math.sin(latDistRad);
            sinLngD = Math.sin(lngDistRad);
            cosLat1 = Math.cos(deg2rad(lat));
            cosLat2 = Math.cos(deg2rad(lat2));
            a = (sinLatD/2)*(sinLatD/2) + cosLat1*cosLat2*(sinLngD/2)*(sinLngD/2);
            if(a<0) a = -1*a;
            c = 2*Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            distance = radius*c;
            console.log(distance/1000);

            ratio =  dist / distance;

            res = [];

            newLat = lat + ((lat2 - lat) * ratio);
            console.log('lat: '+lat+' lat2: '+lat2+' newLat: '+newLat);
            res.push(newLat);
            newLng = lng + ((lng2 - lng) * ratio);
            console.log('lng: '+lng+' lng2: '+lng2+' newLng: '+newLng);
            res.push(newLng);
            return res;
        }

        function displayLastPosition() //ültima posicion del Vehículo
        {            
            hasLastPosition = $('#lastPosition').attr('hasLastPosition');
            if(hasLastPosition == "yes")
            {
                lat = $('#lastPosition').attr('lat');
                lng = $('#lastPosition').attr('lng');
                plate = $('#lastPosition').attr('plate');
                icon = $('#lastPosition').attr('icon');
                link = $('#lastPosition').attr('link');
                data = [plate, bodyMakerPosition(plate, link)]; // title, body
                addMarker(lat, lng, data, icon);           

                map.setCenter(new google.maps.LatLng(lat, lng));
            }
        }

        function addMarker(lat, lng, data, icon) {

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

            //map.setCenter(new google.maps.LatLng(lat, lng));
            map.setZoom(11);
        }       

        function bodyMakerPosition(plate, link)
        {
            return "<div><h4>"+plate+"</h2><p>posición actual</p>";
        }

        function bodyMakerPositionCustomer(name, state)
        {
            return "<div><h4>"+name+"</h2><p>"+state+"</p></div>";
        }

        function latLngArrayer(array){ //array must be like this [[lat,lng]]
            var latLngArray = [];
            for (var i = 0; i < array.length; i++) {
                latLngArray.push(new google.maps.LatLng(array[i][0],array[i][1]));
            };
            return latLngArray;
        }

        function latLngPiper(array){ //array must be like this [[lat,lng]]
            latLngPiped = '';
            for (var i = 0; i < array.length; i++) {
                piped = (i==array.length-1) ? '' : '|';
                latLngPiped += array[i][0]+','+array[i][1]+piped;
            }
            return latLngPiped;
        }

        $(window).load(initialize);
    </script>
@stop