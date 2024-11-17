@extends("dashboard.master")

@section("head")
    @include("dashboard.layouts.head")
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
                    <h1>Stations pour le Transport : {{ $transportData['transport']->name }}</h1>
                    <p>Total des stations: <strong>{{ number_format($employesStations) }}</strong></p>
                </div>
                <div class="col-sm-6 text-right">
                    <!-- Bouton de retour -->
                    <a href="{{ url('/admin/stations/lister') }}" class="btn btn-secondary">
                        Retour à la liste des transports
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Liste des Stations</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($transportData['distances'] as $index => $station)
                        <div class="col-md-3 mb-3">
                            <a href="{{ url('admin/stations/getEmployes', ['latitude' => $station['latitude'], 'longitude' => $station['longitude'], 'transportId' => $transportData['transport']->id]) }}" class="btn btn-primary btn-block">
                                Station {{ $index + 1 }} (Distance : {{ number_format($station['distance'], 2) }} km)
                            </a>
                        </div>
                    @endforeach
                </div>
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
