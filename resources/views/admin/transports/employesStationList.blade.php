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
                    <h1>Employés de la Station</h1>
                    <p><strong>Total des employés : </strong>{{ $count }}</p> <!-- Ajout du compteur ici -->
                </div>
                <div class="col-sm-6 text-right">
                    <!-- Bouton de retour -->
                    <form action="{{ url('/admin/stations/listStations') }}" method="GET">
                        @csrf
                        <input name="transport_id" type="hidden" value="{{ $transportId }}">
                        <button class="btn btn-secondary" type="submit">Retour à la liste des stations</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Liste des Employés</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Nom Complet</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->Mat }}</td>
                                <td>{{ $employee->name }}</td>
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
