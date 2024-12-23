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
                    <h1>Liste des Transports (Total: {{ $total_transports }})</h1>
                </div>
                <div class="col-sm-6" style="text-align: right;">
                    <a href="{{url('/admin/transports/add')}}" class="btn btn-primary">Ajouter Transport</a>
                    <a href="{{url('/admin/transports/showAllZones')}}" class="btn btn-success">Afficher toutes les zones</a>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <div class="col-md-12">
        <div class="card">
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
            <div class="card-header">
                <h3 class="card-title">Rechercher un Transport</h3>
            </div>
            <form action="{{ url('admin/transports/lister') }}" method="GET" onsubmit="return validateForm()">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="nameInput">Nom du Transport</label>
                            <input type="text" name="name" class="form-control" id="nameInput" placeholder="Saisir le nom du transport">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="latitudeInput">Latitude</label>
                            <input type="text" name="latitude" class="form-control" id="latitudeInput" placeholder="Saisir la latitude">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="longitudeInput">Longitude</label>
                            <input type="text" name="longitude" class="form-control" id="longitudeInput" placeholder="Saisir la longitude">
                        </div>
                        <div class="form-group col-md-3 d-flex" style="margin-top: 32px;">
                            <button class="btn btn-primary me-2" type="submit">Chercher</button>
                            <a style="margin-left: 4px" href="{{ url('admin/transports/lister') }}" class="btn btn-success">Réinitialiser</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Liste des Transports</h3>
            </div>
            <div class="card-body p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Capacité</th>
                            <th>Opérations</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transports as $transport)
                        <tr>
                            <td>{{ $transport->name }}</td>
                            <td>{{ $transport->center_lat }}</td>
                            <td>{{ $transport->center_lng }}</td>
                            <td>{{ $transport->capacity }}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ url('admin/transports/editer/'.$transport->id) }}" title="Modifier ce transport">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a class="btn btn-danger" href="{{ url('admin/transports/supprimer/'.$transport->id) }}" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce transport ?');" title="Supprimer ce transport">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a class="btn btn-success" href="{{ url('admin/transports/localisation/'.$transport->id) }}" title="Afficher la localisation de ce transport">
                                    <i class="fas fa-map-marker-alt"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="padding: 10px; float: right;">
                    {{ $transports->links() }}
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
<script>
    function validateForm() {
        var name = document.getElementById('nameInput').value.trim();
        var latitude = document.getElementById('latitudeInput').value.trim();
        var longitude = document.getElementById('longitudeInput').value.trim();

        if (name === "" && latitude === "" && longitude === "") {
            alert("Veuillez remplir au moins un champ.");
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }
</script>
@endsection
