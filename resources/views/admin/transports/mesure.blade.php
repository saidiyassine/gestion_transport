@extends("dashboard.master")

@section("head")
    @include("dashboard.layouts.head")
@endsection

@section("navbar")
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
                    <h1>Mesure des Distances de Trajectoire des Transports</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="col-md-12">
        <div class="card">
            <!-- Notifications de succès ou d'erreur -->
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif

            <!-- Table for Transport Distances -->
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nom du Transport</th>
                            <th>Distance Totale du Trajet (km)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transportsData as $data)
                            <tr>
                                <td>{{ $data['transport']->name }}</td>
                                <td>{{ number_format($data['total_trajectory_distance'], 2) }} km</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
