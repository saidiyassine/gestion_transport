@extends("dashboard.master")
@section("head")
    @include("dashboard.layouts.head")
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
    <script>
       function initMap() {
            const transportLocation = { lat: {{ $transport->center_lat}}, lng: {{ $transport->center_lng }} };
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: transportLocation,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            const directionsService = new google.maps.DirectionsService();
            const directionsDisplay = new google.maps.DirectionsRenderer();
            directionsDisplay.setMap(map);

            const waypoints = [
                @foreach($distances as $employe)
                    { lat: {{ $employe['latitude'] }}, lng: {{ $employe['longitude'] }} },
                @endforeach
            ];

            // Fonction pour dessiner un segment de trajet
            function drawSegment(origin, destination) {
                const request = {
                    origin: origin,
                    destination: destination,
                    travelMode: google.maps.TravelMode.DRIVING,
                    unitSystem: google.maps.UnitSystem.IMPERIAL
                };

                directionsService.route(request, function(result, status) {
                    if (status === google.maps.DirectionsStatus.OK) {
                        const segmentDisplay = new google.maps.DirectionsRenderer();
                        segmentDisplay.setMap(map);
                        segmentDisplay.setDirections(result);
                    } else {
                        console.error('Direction request failed due to ' + status);
                    }
                });
            }

            // Dessiner le segment initial à partir du point de transport
            if (waypoints.length > 0) {
                drawSegment(transportLocation, waypoints[0]);
            }

            // Dessiner les segments entre chaque paire de points
            for (let i = 0; i < waypoints.length - 1; i++) {
                drawSegment(waypoints[i], waypoints[i + 1]);
            }

            // Dessiner le segment final vers la destination
            const finalDestination = { lat: 33.989766, lng: -5.075888};
            if (waypoints.length > 0) {
                drawSegment(waypoints[waypoints.length - 1], finalDestination);
            }

            // Ajouter des marqueurs avec des titres
            new google.maps.Marker({
                position: transportLocation,
                map: map,
                title: 'Point de départ'
            });

            new google.maps.Marker({
                position: finalDestination,
                map: map,
                title: 'Betycor'
            });
        }

        function loadScript() {
            const script = document.createElement('script');
            script.src = "https://maps.gomaps.pro/maps/api/js?key=AlzaSywOM020DE8GaBc_yr52_o0EGXVFzh65r6C&libraries=places&callback=initMap";
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        }

        window.onload = loadScript;

    </script>
@endsection

@section("content")
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Trajet pour le Transport : {{ $transport->name }}</h1>
                    </div>
                </div>
            </div>
        </section>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("footer-script")
    @include("dashboard.layouts.footer-script")
@endsection
@section("nav")
    @include("dashboard.layouts.navbar")
@endsection
@section("aside")
    @include("dashboard.layouts.aside")
@endsection
@section("footer")
    @include("dashboard.layouts.footer")
@endsection
