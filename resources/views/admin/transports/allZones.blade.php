@extends("dashboard.master")

@section("nav")
@include("dashboard.layouts.navbar")
@endsection

@section("aside")
@include("dashboard.layouts.aside")
@endsection
@section("head")
    @include("dashboard.layouts.head")
    <script>
        let map;

        // Initialize the map function
        function initMap() {
            // Get all transports data from the server
            const transports = @json($transports);

            // Dynamically set the initial map center based on the first transport location
            if (transports.length > 0) {
                const initialCenter = {
                    lat: parseFloat(transports[0].center_lat),
                    lng: parseFloat(transports[0].center_lng)
                };

                // Initialize the map centered on the first transport's location
                map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 12,
                    center: initialCenter
                });

                // Loop through each transport zone from the backend
                transports.forEach(transport => {
                    addTransportZone(
                        parseFloat(transport.center_lat),
                        parseFloat(transport.center_lng),
                        transport.name,
                        parseInt(transport.radius)
                    );
                });
            }
        }

        // Function to add a transport zone
        function addTransportZone(lat, lng, name, radius) {
            const location = { lat: lat, lng: lng };

            // Create a marker for the transport location
            const marker = new google.maps.Marker({
                position: location,
                map: map,
                title: name
            });

            // Create a circle with the defined radius around the marker
            const circle = new google.maps.Circle({
                map: map,
                radius: radius,
                fillColor: '#AA0000',
                strokeColor: '#AA0000',
                strokeOpacity: 0.5,
                strokeWeight: 2,
                fillOpacity: 0.2
            });

            // Center the circle around the marker
            circle.bindTo('center', marker, 'position');
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfWEW7jJT0pmSNtyOLH_NNkYXJVK40-zc&callback=initMap" async defer></script>
@endsection

@section("content")
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Localisation de toutes les zones de transport</h1>
                    </div>
                    <div class="col-sm-6" style="text-align: right;">
                        <a href="{{ url('admin/transports/lister') }}" class="btn btn-secondary">Retour à la liste</a>
                    </div>
                </div>
            </div>
        </section>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div id="map" style="height: 500px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("footer")
    @include("dashboard.layouts.footer")
@endsection

@section("footer-script")
    @include("dashboard.layouts.footer-script")
@endsection
