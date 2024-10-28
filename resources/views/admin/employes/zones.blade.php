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
                        <h1>Zone de transport pour l'employé : {{ $employe->name }}</h1>
                    </div>
                    <div class="col-sm-6" style="text-align: right;">
                        <a href="{{ url('admin/employes/lister') }}" class="btn btn-secondary">Retour à la liste</a>
                    </div>
                </div>
            </div>
        </section>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <p>L'employé appartient à : <strong>{{ $zoneAppartenance }}</strong></p>

                    @if ($minDistance !== null)
                        <p>Distance à la zone la plus proche : <strong>{{ number_format($minDistance, 2) }} km</strong></p>
                    @else
                        <p>Hors de toutes les zones de transport.</p>
                    @endif

                    <h2>Distances aux autres zones :</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Zone</th>
                                <th>Distance (km)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($distances as $distance)
                                <tr>
                                    <td>{{ $distance['zone'] }}</td>
                                    <td>{{ number_format($distance['distance'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

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
