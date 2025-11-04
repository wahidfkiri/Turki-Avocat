@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Nouvelle Feuille de Temps</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('time-sheets.index') }}">Feuilles de Temps</a></li>
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Informations de la feuille de temps</h3>
                        </div>
                        <!-- form start -->
                        <form action="{{ route('time-sheets.store') }}" method="POST" id="timesheetForm" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <!-- Date -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_timesheet">Date *</label>
                                            <input type="date" class="form-control @error('date_timesheet') is-invalid @enderror" 
                                                   id="date_timesheet" name="date_timesheet" 
                                                   value="{{ old('date_timesheet', date('Y-m-d')) }}" required>
                                            @error('date_timesheet')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Utilisateur -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="utilisateur_id">Utilisateur *</label>
                                            <select class="form-control @error('utilisateur_id') is-invalid @enderror" 
                                                    id="utilisateur_id" name="utilisateur_id" required>
                                                <option value="">Sélectionnez un utilisateur</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ old('utilisateur_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }} ({{ $user->fonction }})
                                                    </option>
                                                @endforeach
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
                                    <!-- Dossier -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="dossier_id">Dossier</label>
                                            <select class="form-control @error('dossier_id') is-invalid @enderror" 
                                                    id="dossier_id" name="dossier_id">
                                                <option value="">Sélectionnez un dossier</option>
                                                @foreach($dossiers as $dossier)
                                                    <option value="{{ $dossier->id }}" {{ old('dossier_id') == $dossier->id ? 'selected' : '' }}>
                                                        {{ $dossier->numero_dossier }} - {{ $dossier->nom_dossier ?? 'N/A' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('dossier_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Catégorie -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="categorie">Catégorie</label>
                                            <select class="form-control @error('categorie') is-invalid @enderror" 
                                                    id="categorie" name="categorie">
                                                <option value="">Chargement des catégories...</option>
                                            </select>
                                            @error('categorie')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Type -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            <select class="form-control @error('type') is-invalid @enderror" 
                                                    id="type" name="type" disabled>
                                                <option value="">Sélectionnez d'abord une catégorie</option>
                                            </select>
                                            @error('type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Quantité -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="quantite">Quantité *</label>
                                            <input type="number" class="form-control @error('quantite') is-invalid @enderror" 
                                                   id="quantite" name="quantite" value="{{ old('quantite', 1) }}" 
                                                   min="0" step="0.01" placeholder="0.00" required>
                                            @error('quantite')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Prix -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="prix">Prix (DT) *</label>
                                            <input type="number" class="form-control @error('prix') is-invalid @enderror" 
                                                   id="prix" name="prix" value="{{ old('prix', 0) }}" 
                                                   min="0" step="0.01" placeholder="0.00" required>
                                            @error('prix')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Total (calculé automatiquement) -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_calcule">Total calculé</label>
                                            <input type="text" name="total" class="form-control" id="total_calcule" 
                                                   value="0.00 DT" readonly style="background-color: #f8f9fa; font-weight: bold;">
                                            <small class="form-text text-muted">
                                                Calcul automatique : Quantité × Prix
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="piece_jointe">Pièce Jointe</label>
                                            <input type="file" name="file" class="form-control" id="piece_jointe">
                                            <small class="form-text text-muted">
                                                Téléchargez une pièce jointe (PDF, image, etc.)
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label for="description">Description *</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="Décrivez l'activité réalisée..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Créer la feuille de temps
                                </button>
                                <a href="{{ route('time-sheets.index') }}" class="btn btn-default btn-lg">
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
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Auto-sélection de l'utilisateur connecté si c'est un avocat/secrétaire
        @if(auth()->user()->fonction === 'avocat' || auth()->user()->fonction === 'secrétaire')
            $('#utilisateur_id').val('{{ auth()->id() }}').trigger('change');
        @endif

        // Dynamic category-type functionality
        const categorieSelect = document.getElementById('categorie');
        const typeSelect = document.getElementById('type');

        // Load categories from the server
        function loadCategories() {
            fetch('/get/categories')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur lors du chargement des catégories');
                    }
                    return response.json();
                })
                .then(data => {
                    categorieSelect.innerHTML = '<option value="">Sélectionnez une catégorie</option>';
                    data.forEach(categorie => {
                        const option = document.createElement('option');
                        option.value = categorie.id;
                        option.textContent = categorie.nom;
                        categorieSelect.appendChild(option);
                    });
                    
                    // Set old value if exists
                    @if(old('categorie'))
                        categorieSelect.value = '{{ old('categorie') }}';
                        if (categorieSelect.value) {
                            loadTypes(categorieSelect.value);
                        }
                    @endif
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    categorieSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                });
        }

        // Load types based on selected category
        function loadTypes(categorieId) {
            if (!categorieId) {
                typeSelect.innerHTML = '<option value="">Sélectionnez d\'abord une catégorie</option>';
                typeSelect.disabled = true;
                return;
            }

            typeSelect.disabled = true;
            typeSelect.innerHTML = '<option value="">Chargement des types...</option>';

            fetch(`/get/types?categorie_id=${categorieId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur lors du chargement des types');
                    }
                    return response.json();
                })
                .then(data => {
                    typeSelect.innerHTML = '<option value="">Sélectionnez un type</option>';
                    data.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.id;
                        option.textContent = type.nom;
                        typeSelect.appendChild(option);
                    });
                    typeSelect.disabled = false;
                    
                    // Set old value if exists
                    @if(old('type'))
                        typeSelect.value = '{{ old('type') }}';
                    @endif
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    typeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                    typeSelect.disabled = false;
                });
        }

        // Event listener for category change
        categorieSelect.addEventListener('change', function() {
            loadTypes(this.value);
        });

        // Initialize by loading categories
        loadCategories();
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
    #total_calcule {
        color: #28a745;
        font-size: 1.1em;
    }
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }
</style>
@endsection