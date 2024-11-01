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
                    <h1>Modifier le Transport</h1>
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

            <form action="{{ url('admin/transports/update/'.$transport->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="nameInput">Nom du Transport</label>
                            <input type="text" name="name" class="form-control" id="nameInput" value="{{ $transport->name }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="latitudeInput">Latitude</label>
                            <input type="number" step="0.000001" min="-90" max="90" name="center_lat" class="form-control" id="latitudeInput" value="{{ $transport->center_lat }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="longitudeInput">Longitude</label>
                            <input type="number" step="0.000001" min="-180" max="180" name="center_lng" class="form-control" id="longitudeInput" value="{{ $transport->center_lng }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="capacityInput">Capacité</label>
                            <input type="number" name="capacity" class="form-control" id="capacityInput" value="{{ $transport->capacity }}" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <a href="{{ url('admin/transports/lister') }}" class="btn btn-secondary">Annuler</a>
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
