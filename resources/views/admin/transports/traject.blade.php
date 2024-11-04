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
            const transportLocation = { lat: {{ $transport->center_lat }}, lng: {{ $transport->center_lng }} };
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: transportLocation,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            const directionsService = new google.maps.DirectionsService();

            const waypoints = [
                @foreach($distances as $employe)
                    { lat: {{ $employe['latitude'] }}, lng: {{ $employe['longitude'] }}, title: '{{ $employe['name'] }}' },
                @endforeach
            ];

            // Fonction pour tracer un segment de route avec infobulle pour chaque point de destination
            function drawSegment(origin, destination, title) {
                const request = {
                    origin: origin,
                    destination: destination,
                    travelMode: google.maps.TravelMode.DRIVING,
                    unitSystem: google.maps.UnitSystem.IMPERIAL
                };

                directionsService.route(request, function(result, status) {
                    if (status === google.maps.DirectionsStatus.OK) {
                        const segmentDisplay = new google.maps.DirectionsRenderer({
                            map: map,
                            suppressMarkers: true // Empêche l'ajout automatique de marqueurs pour le segment
                        });
                        segmentDisplay.setDirections(result);

                        // Ajouter un marqueur au point de destination avec le nom de l'employé
                        const marker = new google.maps.Marker({
                            position: destination, // Utilise destination ici
                            map: map,
                            title: title
                        });

                        const infoWindow = new google.maps.InfoWindow({
                            content: `<b>${title}</b>`
                        });

                        marker.addListener('click', () => {
                            infoWindow.open(map, marker);
                        });
                    } else {
                        console.error('Direction request failed due to ' + status);
                    }
                });
            }

            // Dessiner les segments de route entre chaque paire de points
            if (waypoints.length > 0) {
                drawSegment(transportLocation, waypoints[0], waypoints[0].title);
            }

            for (let i = 0; i < waypoints.length - 1; i++) {
                drawSegment(waypoints[i], waypoints[i + 1], waypoints[i + 1].title);
            }

            // Ajouter le segment final vers la destination
            const finalDestination = { lat: 33.989766, lng: -5.075888 };
            if (waypoints.length > 0) {
                drawSegment(waypoints[waypoints.length - 1], finalDestination, 'Betycor');
            }

            // Ajouter un marqueur pour le point de départ
            new google.maps.Marker({
                position: transportLocation,
                map: map,
                title: 'Point de départ'
            });

            // Ajouter un marqueur pour la destination finale
            new google.maps.Marker({
                position: finalDestination,
                map: map,
                title: 'Betycor'
            });

             // Ajouter des marqueurs pour les employés non affectés
        @foreach($nonAffectes as $nonAffecte)
            @if(!is_null($nonAffecte->latitude) && !is_null($nonAffecte->longitude))
                new google.maps.Marker({
                    position: { lat: {{ $nonAffecte->latitude }}, lng: {{ $nonAffecte->longitude }} },
                    map: map,
                    title: '{{ $nonAffecte->name }}', // Utiliser le nom de l'employé non affecté pour le titre
                    icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                });
            @endif
        @endforeach
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
