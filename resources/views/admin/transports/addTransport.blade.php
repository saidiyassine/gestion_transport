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
                    <h1>Ajouter un Transport</h1>
                </div>
                <div class="col-sm-6" style="text-align: right;">
                    <a href="{{ url('/admin/transport/lister') }}" class="btn btn-secondary">Retour à la liste</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Formulaire d'ajout de Transport</h3>
                        </div>
                        <form action="{{ url('/admin/transports/add') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <!-- Nom du Transport -->
                                    <div class="form-group col-md-6">
                                        <label for="name">Nom du Transport</label>
                                        <input required type="text" class="form-control" id="name" name="name" placeholder="Saisir le nom du transport" required>
                                    </div>

                                    <!-- Capacité des Employés -->
                                    <div class="form-group col-md-6">
                                        <label for="capacity">Capacité des Employés</label>
                                        <input required type="number" class="form-control" id="capacity" name="capacity" placeholder="Saisir la capacité du transport" required>
                                    </div>

                                    <!-- Coordonnées de la zone (Polygone) -->
                                    <div class="form-group col-md-12">
                                        <label for="zone_coordinates">Coordonnées de la Zone (Polygone)</label>
                                        <textarea required class="form-control" id="zone_coordinates" name="zone_coordinates" rows="3" placeholder="Saisir les coordonnées du polygone (format: [[lat1, lng1], [lat2, lng2], ...])"></textarea>
                                    </div>

                                    <!-- Centre du Cercle: Latitude -->
                                    <div class="form-group col-md-6">
                                        <label for="center_lat">Latitude du Centre</label>
                                        <input required type="number" class="form-control" id="center_lat" name="center_lat" step="0.000001" min="-90" max="90" placeholder="Saisir la latitude du centre">
                                    </div>

                                    <!-- Centre du Cercle: Longitude -->
                                    <div class="form-group col-md-6">
                                        <label for="center_lng">Longitude du Centre</label>
                                        <input required type="number" class="form-control" id="center_lng" name="center_lng" step="0.000001" min="-180" max="180" placeholder="Saisir la longitude du centre">
                                    </div>

                                    <!-- Rayon du Cercle -->
                                    <div class="form-group col-md-6">
                                        <label for="radius">Rayon (en mètres)</label>
                                        <input required type="number" class="form-control" id="radius" name="radius" placeholder="Saisir le rayon en mètres">
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                <a href="{{ url('/admin/transports/lister') }}" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
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
