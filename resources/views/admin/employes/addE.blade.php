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
                    <h1>Ajouter un Employé(e)</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulaire d'Ajout</h3>
            </div>
            <form action="{{ url('admin/employe/add') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Nom Complet</label>
                        <input type="text" name="name" class="form-control" id="name" required placeholder="Saisir le nom" required>
                    </div>
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="number" name="latitude" class="form-control" id="latitudeInput" placeholder="Saisir la latitude" step="0.000001" min="-90" max="90" required>
                    </div>
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="number" name="longitude" class="form-control" id="longitudeInput" placeholder="Saisir la longitude" step="0.000001" min="-180" max="180" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ url('admin/employes/lister') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section("footer")
@include("dashboard.layouts.footer")
@endsection
