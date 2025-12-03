

<!-- Modal -->
<div class="modal fade" id="timesheetModal" tabindex="-1" role="dialog" aria-labelledby="timesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="timesheetModalLabel">Nouvelle Feuille de Temps</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Les messages d'alerte -->
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

                <!-- Le formulaire -->
                <form action="{{ route('dossiers.timesheets.store', ['dossier' => $dossier->id]) }}" method="POST" id="timesheetForm">
                    @csrf
                    <div class="card-body" style="padding: 0;">
                        <div class="row">
                            <!-- Date -->
                            <div class="col-md-4">
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
                            <!-- Dossier -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="dossier_id">Dossier</label>
                                    <select class="form-control @error('dossier_id') is-invalid @enderror" 
                                            id="dossier_id" name="dossier_id">
                                        <option value="">Sélectionnez un dossier</option>
                                            <option value="{{ $dossier->id }}" selected>
                                                {{ $dossier->numero_dossier }} - {{ $dossier->nom_dossier ?? 'N/A' }}
                                            </option>
                                    </select>
                                    @error('dossier_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <!-- Utilisateur -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="utilisateur_id">Utilisateur *</label>
                                    <select class="form-control @error('utilisateur_id') is-invalid @enderror" 
                                            id="utilisateur_id" name="utilisateur_id" required>
                                        <option value="">Sélectionnez un utilisateur</option>
                                        @if(auth()->user()->hasRole('admin'))
                                        @foreach(\App\Models\User::all() as $user)
                                            <option value="{{ $user->id }}" {{ auth()->user()->id == $user->id ? 'selected' : '' }}>
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

                            <!-- Catégorie -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="categorie">Catégorie</label>
                                    <select class="form-control @error('categorie') is-invalid @enderror" 
                                            id="categorieList" name="categorie">
                                        <option value="">Chargement des catégories...</option>
                                    </select>
                                    @error('categorie')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- Type -->
                            <div class="col-md-4">
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
                        </div>

                        <div class="row">

                            <!-- Quantité -->
                            <div class="col-md-4">
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
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_calcule">Total calculé</label>
                                    <input type="text" name="total" class="form-control" id="total_calcule" 
                                           value="0.00 DT" readonly style="background-color: #f8f9fa; font-weight: bold;">
                                    <small class="form-text text-muted">
                                        Calcul automatique : Quantité × Prix
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Décrivez l'activité réalisée..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Créer la feuille de temps
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Auto-sélection de l'utilisateur connecté si c'est un avocat/secrétaire
        @if(auth()->user()->fonction === 'avocat' || auth()->user()->fonction === 'secrétaire')
            $('#utilisateur_id').val('{{ auth()->id() }}').trigger('change');
        @endif

        // Dynamic category-type functionality
        const categorieSelect = document.getElementById('categorieList');
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

        // Auto-calculate total
        function calculateTotal() {
            var quantite = parseFloat($('#quantite').val()) || 0;
            var prix = parseFloat($('#prix').val()) || 0;
            var total = quantite * prix;
            
            $('#total_calcule').val(total.toFixed(2) + ' DT');
        }

        // Listen for changes on quantity and price
        $('#quantite, #prix').on('input', function() {
            calculateTotal();
        });

        // Initial calculation
        calculateTotal();

        // Reset form when modal is closed
        $('#timesheetModal').on('hidden.bs.modal', function () {
            $('#timesheetForm')[0].reset();
            $('#total_calcule').val('0.00 DT');
            typeSelect.innerHTML = '<option value="">Sélectionnez d\'abord une catégorie</option>';
            typeSelect.disabled = true;
            loadCategories(); // Recharger les catégories
        });

        // Gérer la soumission du formulaire via AJAX (optionnel)
        $('#timesheetForm').on('submit', function(e) {
            // Vous pouvez ajouter une soumission AJAX ici si nécessaire
            // Sinon, le formulaire se soumet normalement
        });
    });
</script>

<style>
    .modal-lg {
        max-width: 800px;
    }
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
</style>