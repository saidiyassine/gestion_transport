@extends("dashboard.master")

@section("head")
    @include("dashboard.layouts.head")
    <script>
        // Define the initMap function in the global scope
        function initMap() {
            const transportLocation = { lat: {{ $transport->center_lat }}, lng: {{ $transport->center_lng }} };

            // Initialize the map centered on transport location
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: transportLocation
            });

            // Add a marker for the transport
            const marker = new google.maps.Marker({
                position: transportLocation,
                map: map,
                title: "{{ $transport->name }}"
            });

            // Add a circle (radius) around the marker
            const circle = new google.maps.Circle({
                map: map,
                radius: {{ $transport->radius }},  // Radius in meters
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

@section("nav")
    @include("dashboard.layouts.navbar")
@endsection

@section("aside")
    @include("dashboard.layouts.aside")
@endsection

@section("content")
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Localisation de Transport : {{ $transport->name }}</h1>
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
