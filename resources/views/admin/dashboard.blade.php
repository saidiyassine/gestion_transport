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
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $employes }}</h3>
                            <p>Employés(e)</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $transports }}</h3>
                            <p>Transports</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-bus"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $non_mo }}</h3>
                            <p>Non motorisé(e)</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $mo }}</h3>
                            <p>Motorisé(e)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-motorcycle"></i> <!-- Icône moto -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-12">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $stationsCount }}</h3>
                            <p>Stations</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transport List -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h2>Liste des Transports</h2>
                        </div>
                        <div class="col-sm-6" style="text-align: right;">
                            <a href="{{url('/admin/transports/allTrajects')}}" class="btn btn-success">Tous les trajects</a>
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nom du Transport</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transports_table as $transport)
                                <tr>
                                    <td>{{ $transport->name }}</td>
                                    <td>{{ $transport->center_lat }}</td>
                                    <td>{{ $transport->center_lng }}</td>
                                    <td>
                                        <a href="{{ url('admin/transports/showTraject/' . $transport->id) }}" class="btn btn-primary">Voir trajet</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section("footer")
@include("dashboard.layouts.footer")
@endsection

@section("footer-script")
@include("dashboard.layouts.footer-script")
@endsection
