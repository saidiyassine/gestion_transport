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
                    <h1>Liste des Employés (Total: {{ $total_employees }})</h1>
                </div>
                <div class="col-sm-6" style="text-align: right;">
                    <a href="{{url('/admin/employes/add')}}" class="btn btn-primary">Ajouter Employé(e)</a>
                    <a href="{{url('/admin/employes/points')}}" class="btn btn-warning">Afficher les points</a>
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
                <h3 class="card-title">Rechercher un Employé</h3>
            </div>
            <form action="{{ url('admin/employes/lister') }}" method="GET" onsubmit="return validateForm()">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <label for="latitudeInput">Matricule</label>
                            <input type="text" name="mat" class="form-control" id="matInput" placeholder="Saisir le matricule">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="nameInput">Nom Complet</label>
                            <input type="text" name="name" class="form-control" id="nameInput" placeholder="Saisir le nom">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="mode">Mode de transport</label>
                            <select name="motorise" class="form-control" id="motorizedSelect" >
                                <option selected disabled value="">Sélectionner le mode</option>
                                <option value="1">Motorisé</option>
                                <option value="0">Non motorisé</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3 d-flex" style="margin-top: 32px;">
                            <button class="btn btn-primary me-2" type="submit">Chercher</button>
                            <a style="margin-left: 4px" href="{{ url('admin/employes/lister') }}" class="btn btn-success">Réinitialiser</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Liste des Employés</h3>
            </div>
            <div class="card-body p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Transport</th>
                            <th>Mode de transport</th>
                            <th>Opérations</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                        <tr>
                            <td>{{ $employee->Mat }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>  @if($employee->transports->isNotEmpty())
                                @foreach($employee->transports as $transport)
                                    {{ $transport->name }}<br>
                                @endforeach
                            @else
                                Aucun transport
                            @endif</td>
                            <td>{{ $employee->moto == "1" ? "Motorisé" : "Non motorisé" }}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ url('admin/employes/editer/'.$employee->id) }}" title="Modifier cet employé">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a class="btn btn-danger" href="{{ url('admin/employes/supprimer/'.$employee->id) }}" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet Employé ?');" title="Supprimer cet employé">
                                    <i class="fas fa-trash"></i>
                                </a>
                               @if ($employee->moto == "0")
                                    <a class="btn btn-success" href="{{ url('admin/employes/localisation/'.$employee->id) }}" title="Afficher la localisation de cet employé">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </a>
                                    <a class="btn btn-warning" href="{{ url('admin/employes/zone/'.$employee->id) }}" title="Afficher la zone de cet employé">
                                        <i class="fas fa-bullseye"></i>
                                    </a>
                                    <a class="btn btn-info" href="{{ url('admin/employes/affecter/'.$employee->id) }}" title="Affecter Employé">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                               @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="padding: 10px; float: right;">
                    {{ $employees->links() }}
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
        var mat = document.getElementById('matInput').value.trim();
        var mode = document.getElementById('motorizedSelect').value.trim();

        if (name === "" && mat === "" && mode == '') {
            alert("Veuillez remplir au moins un champ.");
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }
</script>
@endsection
