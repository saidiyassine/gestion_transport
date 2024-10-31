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
            // Coordonnées du transport
            const transportLocation = { lat: {{ $transportCoordinates['lat'] }}, lng: {{ $transportCoordinates['lng'] }} };
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: transportLocation,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            const directionsService = new google.maps.DirectionsService();
            const directionsDisplay = new google.maps.DirectionsRenderer();
            directionsDisplay.setMap(map);

            // Définir la destination
            const destinationLocation = { lat: 34.010168, lng: -5.024848 };

            // Créer une requête
            const request = {
                origin: transportLocation,
                destination: destinationLocation,
                travelMode: google.maps.TravelMode.DRIVING, // Changer en WALKING, BICYCLING, TRANSIT selon le besoin
                unitSystem: google.maps.UnitSystem.IMPERIAL
            };

            // Passer la requête à la méthode de route
            directionsService.route(request, function (result, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    // Afficher l'itinéraire
                    directionsDisplay.setDirections(result);
                } else {
                    // Supprimer l'itinéraire de la carte
                    directionsDisplay.setDirections({ routes: [] });
                    // Optionnellement, centrer la carte sur un emplacement par défaut (par exemple, Londres)
                    map.setCenter({ lat: 51.5074, lng: -0.1278 }); // Exemple pour Londres
                    console.error('La demande de directions a échoué en raison de ' + status);
                }
            });
        }

        // Appeler initMap après le chargement de l'API Google Maps
        window.onload = initMap;
    </script>
    <script src="https://maps.gomaps.pro/maps/api/js?key=AlzaSywOM020DE8GaBc_yr52_o0EGXVFzh65r6C&libraries=places" async defer></script>
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
