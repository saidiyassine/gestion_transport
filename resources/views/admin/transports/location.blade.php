@extends("dashboard.master")

@section("head")
@include("dashboard.layouts.head")
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCfWEW7jJT0pmSNtyOLH_NNkYXJVK40-zc&callback=initMap"></script> <!-- Replace with your API Key -->
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

<script>
    // Ensure that the initMap function is defined globally
    window.initMap = function() {
        const transportLocation = {
            lat: {{ $transport->latitude }},
            lng: {{ $transport->longitude }},
        };

        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 12,
            center: transportLocation,
        });

        // Marker for the transport location
        new google.maps.Marker({
            position: transportLocation,
            map: map,
            title: '{{ $transport->name }}'
        });

        // Circle representing the zone
        const radiusInMeters = {{ $transport->radius }};
        new google.maps.Circle({
            strokeColor: "#FF0000",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#FF0000",
            fillOpacity: 0.35,
            map: map,
            center: transportLocation,
            radius: radiusInMeters,
        });
    };
</script>
@endsection

@section("footer")
@include("dashboard.layouts.footer")
@endsection

@section("footer-script")
@include("dashboard.layouts.footer-script")
@endsection
