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
                    <h1>Modifier l'Employé</h1>
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

            <form action="{{ url('admin/employes/update/'.$employe->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="nameInput">Matricule</label>
                            <input type="text" name="mat" class="form-control" id="nameInput" value="{{ $employe->Mat }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nameInput">Nom Complet</label>
                            <input type="text" name="name" class="form-control" id="nameInput" value="{{ $employe->name }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="latitudeInput">Latitude</label>
                            <input type="number" step="0.000001" min="-90" max="90" name="latitude" class="form-control" id="latitudeInput" value="{{ $employe->latitude }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="longitudeInput">Longitude</label>
                            <input type="number" step="0.000001" min="-180" max="180" name="longitude" class="form-control" id="longitudeInput" value="{{ $employe->longitude }}" required>
                        </div>
                        <div class="form-group">
                            <label for="motorise">Mode de transport</label>
                            <select name="motorise" class="form-control" id="motorizedSelect" required>
                                <option value="1" {{ $employe->moto == 1 ? 'selected' : '' }}>Motorisé</option>
                                <option value="0" {{ $employe->moto == 0 ? 'selected' : '' }}>Non motorisé</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
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

@section("footer-script")
@include("dashboard.layouts.footer-script")
@endsection
