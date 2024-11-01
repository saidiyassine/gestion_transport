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
                    <h1>Affecter un employé à un transport</h1>
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

            <!-- Formulaire d'affectation -->
            <form action="{{ url('admin/employes/affecter/'.$employe->id) }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <!-- Input caché pour l'ID de l'employé -->
                        <input type="hidden" name="employee_id" value="{{ $employe->id }}">

                        <!-- Matricule et Nom Complet de l'Employé -->
                        <div class="form-group col-md-6">
                            <label for="matriculeInput">Matricule</label>
                            <input type="text" class="form-control" id="matriculeInput" value="{{ $employe->Mat }}" disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nameInput">Nom Complet</label>
                            <input type="text" class="form-control" id="nameInput" value="{{ $employe->name }}" disabled>
                        </div>

                        <!-- Sélection du Transport -->
                        <div class="form-group col-md-6">
                            <label for="transportSelect">Choisir le Transport</label>
                            <select name="transport_id" class="form-control" id="transportSelect" required>
                                <option value="">-- Sélectionner un Transport --</option>
                                @foreach ($transports as $transport)
                                    <option value="{{ $transport->id }}">{{ $transport->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Boutons de soumission et d'annulation -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Affecter Employé(e)</button>
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
