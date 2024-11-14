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
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        // Array of colors for each transport route
        const routeColors = ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF']; // Red, Green, Blue, Yellow, Magenta

        // Loop through each transport data to plot routes
        @foreach($transportsData as $transportData)
            // Ensure transportLocation is scoped correctly by using a unique variable for each transport
            let transportLocation{{ $loop->index }} = { lat: {{ $transportData['transport']->center_lat }}, lng: {{ $transportData['transport']->center_lng }} };

            // Create a marker for the transport location
            new google.maps.Marker({
                position: transportLocation{{ $loop->index }},
                map: map,
                title: 'Transport: {{ $transportData['transport']->name }}'
            });

            // Directions Service for each transport, scoped with a unique variable name
            const directionsService{{ $loop->index }} = new google.maps.DirectionsService();

            // Ensure waypoints have a unique name
            const waypoints{{ $loop->index }} = [
                @foreach($transportData['distances'] as $employe)
                    { lat: {{ $employe['latitude'] }}, lng: {{ $employe['longitude'] }}, title: '{{ $employe['name'] }}' },
                @endforeach
            ];

            // Create a unique color for each transport route (based on the loop index)
            const routeColor{{ $loop->index }} = routeColors[{{ $loop->index }} % routeColors.length];

            // Function to draw the route between points with a color
            function drawSegment(origin, destination, title, directionsService, color) {
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
                            suppressMarkers: true, // Avoid auto markers
                            polylineOptions: {
                                strokeColor: color, // Set the route color
                                strokeWeight: 5 // You can adjust the line width
                            }
                        });
                        segmentDisplay.setDirections(result);

                        // Create marker for destination
                        const marker = new google.maps.Marker({
                            position: destination,
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

            // Draw route for each transport to its employees and final destination
            if (waypoints{{ $loop->index }}.length > 0) {
                drawSegment(transportLocation{{ $loop->index }}, waypoints{{ $loop->index }}[0], waypoints{{ $loop->index }}[0].title, directionsService{{ $loop->index }}, routeColor{{ $loop->index }});
            }

            for (let i = 0; i < waypoints{{ $loop->index }}.length - 1; i++) {
                drawSegment(waypoints{{ $loop->index }}[i], waypoints{{ $loop->index }}[i + 1], waypoints{{ $loop->index }}[i + 1].title, directionsService{{ $loop->index }}, routeColor{{ $loop->index }});
            }

            // Ensure finalDestination has a unique name for each transport
            const finalDestination{{ $loop->index }} = { lat: 33.989766, lng: -5.075888 };
            if (waypoints{{ $loop->index }}.length > 0) {
                drawSegment(waypoints{{ $loop->index }}[waypoints{{ $loop->index }}.length - 1], finalDestination{{ $loop->index }}, 'Betycor', directionsService{{ $loop->index }}, routeColor{{ $loop->index }});
            }
        @endforeach

        // Add markers for non-affect employees
        @foreach($nonAffectes as $nonAffecte)
            @if(!is_null($nonAffecte->latitude) && !is_null($nonAffecte->longitude))
                new google.maps.Marker({
                    position: { lat: {{ $nonAffecte->latitude }}, lng: {{ $nonAffecte->longitude }} },
                    map: map,
                    title: '{{ $nonAffecte->name }}', // Name of non-affected employee
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
                        <h1 class="m-0">Tous les Trajets</h1>
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
