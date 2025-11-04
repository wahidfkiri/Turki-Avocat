@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Nouvelle Tâche</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Tâches</a></li>
                        <li class="breadcrumb-item active">Nouvelle</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                         @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Succès!</h5>
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-ban"></i> Erreur!</h5>
                {{ session('error') }}
            </div>
            @endif
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Informations de la tâche</h3>
                        </div>
                        <!-- form start -->
                        <form action="{{ route('dossiers.tasks.store', ['dossier' => $dossier->id]) }}" method="POST" id="taskForm">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <!-- Titre -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="titre">Titre *</label>
                                            <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                                                   id="titre" name="titre" value="{{ old('titre') }}" 
                                                   placeholder="Entrez le titre de la tâche" required>
                                            @error('titre')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Priorité -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="priorite">Priorité *</label>
                                            <select class="form-control @error('priorite') is-invalid @enderror" 
                                                    id="priorite" name="priorite" required>
                                                <option value="">Sélectionnez une priorité</option>
                                                <option value="basse" {{ old('priorite') == 'basse' ? 'selected' : '' }}>Basse</option>
                                                <option value="normale" {{ old('priorite') == 'normale' ? 'selected' : '' }}>Normale</option>
                                                <option value="haute" {{ old('priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
                                                <option value="urgente" {{ old('priorite') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                                            </select>
                                            @error('priorite')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Statut -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="statut">Statut *</label>
                                            <select class="form-control @error('statut') is-invalid @enderror" 
                                                    id="statut" name="statut" required>
                                                <option value="">Sélectionnez un statut</option>
                                                <option value="a_faire" {{ old('statut') == 'a_faire' ? 'selected' : '' }}>À faire</option>
                                                <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                                <option value="terminee" {{ old('statut') == 'terminee' ? 'selected' : '' }}>Terminée</option>
                                                <option value="en_retard" {{ old('statut') == 'en_retard' ? 'selected' : '' }}>En retard</option>
                                            </select>
                                            @error('statut')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Utilisateur assigné -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="utilisateur_id">Assigné à *</label>
                                            <select class="form-control @error('utilisateur_id') is-invalid @enderror" 
                                                    id="utilisateur_id" name="utilisateur_id" required>
                                                <option value="">Sélectionnez un utilisateur</option>
                                            @if(auth()->user()->hasRole('admin'))
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ old('utilisateur_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }} ({{ $user->fonction }})
                                                    </option>
                                                @endforeach
                                                @else
                                                    <option value="{{ auth()->user()->id }}" selected>
                                                        {{ auth()->user()->name }} ({{ auth()->user()->fonction }})
                                                    </option>
                                              @endif
                                            </select>
                                            @error('utilisateur_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Date de début -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date_debut">Date de début</label>
                                            <input type="date" class="form-control @error('date_debut') is-invalid @enderror" 
                                                   id="date_debut" name="date_debut" value="{{ old('date_debut') }}">
                                            @error('date_debut')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Date de fin -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date_fin">Date de fin</label>
                                            <input type="date" class="form-control @error('date_fin') is-invalid @enderror" 
                                                   id="date_fin" name="date_fin" value="{{ old('date_fin') }}">
                                            @error('date_fin')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Dossier -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="dossier_id">Dossier</label>
                                            <select class="form-control @error('dossier_id') is-invalid @enderror" 
                                                    id="dossier_id" name="dossier_id">
                                                <option value="">Sélectionnez un dossier</option>
                                                    <option value="{{ $dossier->id }}" selected>
                                                        {{ $dossier->numero_dossier }}
                                                    </option>
                                            </select>
                                            @error('dossier_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Intervenant -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="intervenant_id">Intervenant</label>
                                            <select class="form-control @error('intervenant_id') is-invalid @enderror" 
                                                    id="intervenant_id" name="intervenant_id">
                                                <option value="">Sélectionnez un intervenant</option>
                                                @foreach($dossier->intervenants as $intervenant)
                                                    <option value="{{ $intervenant->id }}" {{ old('intervenant_id') == $intervenant->id ? 'selected' : '' }}>
                                                        {{ $intervenant->identite_fr }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('intervenant_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="Décrivez la tâche en détail...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Note -->
                                <div class="form-group">
                                    <label for="note">Notes supplémentaires</label>
                                    <textarea class="form-control @error('note') is-invalid @enderror" 
                                              id="note" name="note" rows="3" 
                                              placeholder="Ajoutez des notes ou commentaires...">{{ old('note') }}</textarea>
                                    @error('note')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Ces notes sont internes et ne seront pas visibles par le client.
                                    </small>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Créer la tâche
                                </button>
                                <a href="{{url()->previous() }}" class="btn btn-default btn-lg">
                                    <i class="fas fa-arrow-left"></i> Retour à la liste
                                </a>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // Set today's date as default for date fields
        var today = new Date().toISOString().split('T')[0];
        $('#date_debut').val(today);

        // Validation côté client
        $('#taskForm').validate({
            rules: {
                titre: {
                    required: true,
                    minlength: 3
                },
                priorite: {
                    required: true
                },
                statut: {
                    required: true
                },
                utilisateur_id: {
                    required: true
                },
                date_fin: {
                    greaterThan: "#date_debut"
                }
            },
            messages: {
                titre: {
                    required: "Le titre est obligatoire",
                    minlength: "Le titre doit contenir au moins 3 caractères"
                },
                priorite: {
                    required: "La priorité est obligatoire"
                },
                statut: {
                    required: "Le statut est obligatoire"
                },
                utilisateur_id: {
                    required: "L'utilisateur assigné est obligatoire"
                },
                date_fin: {
                    greaterThan: "La date de fin doit être postérieure ou égale à la date de début"
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                // Afficher un loader ou désactiver le bouton pendant la soumission
                $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Création en cours...');
                form.submit();
            }
        });

        // Custom validation rule for date comparison
        $.validator.addMethod("greaterThan", function(value, element, param) {
            if (!value) return true; // Si date_fin est vide, c'est OK
            var startDate = $(param).val();
            if (!startDate) return true; // Si date_debut est vide, c'est OK
            
            var start = new Date(startDate);
            var end = new Date(value);
            return end >= start;
        }, "La date de fin doit être postérieure ou égale à la date de début");

        // Auto-sélection de l'utilisateur connecté si c'est un avocat/secrétaire
        @if(auth()->user()->fonction === 'avocat' || auth()->user()->fonction === 'secrétaire')
            $('#utilisateur_id').val('{{ auth()->id() }}').trigger('change');
        @endif
    });
</script>
<style>
    .select2-container .select2-selection--single {
        height: 38px;
    }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
    }
    .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }
</style>
@endsection