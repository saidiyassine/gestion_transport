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
                    <h1>Sélectionner un Transport</h1>
                </div>
            </div>
        </div>
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
                <h3 class="card-title">Liste des Transports</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ url('admin/list/listEmploye') }}">
                    <div class="form-group">
                        <label for="transportSelect">Sélectionnez un transport:</label>
                        <select name="transport_id" id="transportSelect" class="form-control">
                            <option value="">-- Sélectionnez un transport --</option>
                            @foreach ($transports as $transport)
                                <option value="{{ $transport->id }}">{{ $transport->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Valider</button>
                </form>
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
