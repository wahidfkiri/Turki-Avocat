<!-- Modal -->
<div class="modal fade" id="timesheetModal" tabindex="-1" role="dialog" aria-labelledby="timesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="timesheetModalLabel">Nouvelle Feuille de Temps</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Container pour les messages d'alerte AJAX -->
                <div id="ajaxAlertContainer"></div>

                <!-- Le formulaire -->
                <form id="timesheetForm" method="POST">
                    @csrf
                    <div class="card-body" style="padding: 0;">
                        <div class="row">
                            <!-- Date -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date_timesheet">Date *</label>
                                    <input type="date" class="form-control" 
                                           id="date_timesheet" name="date_timesheet" 
                                           value="{{ date('Y-m-d') }}" required>
                                    <div class="invalid-feedback" id="date_timesheet_error"></div>
                                </div>
                            </div>
                            <!-- Dossier -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="dossier_id">Dossier</label>
                                    <select class="form-control" 
                                            id="dossier_id" name="dossier_id">
                                        <option value="{{ $dossier->id }}" selected>
                                            {{ $dossier->numero_dossier }} - {{ $dossier->nom_dossier ?? 'N/A' }}
                                        </option>
                                    </select>
                                    <div class="invalid-feedback" id="dossier_id_error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Utilisateur -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="utilisateur_id">Utilisateur *</label>
                                    <select class="form-control" 
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
                                    <div class="invalid-feedback" id="utilisateur_id_error"></div>
                                </div>
                            </div>

                            <!-- Catégorie -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="categorie">Catégorie *</label>
                                    <select class="form-control" 
                                            id="categorieList" name="categorie" required>
                                        <option value="">Chargement des catégories...</option>
                                    </select>
                                    <div class="invalid-feedback" id="categorie_error"></div>
                                </div>
                            </div>
                            <!-- Type -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select class="form-control" 
                                            id="type" name="type">
                                        <option value="">Sélectionnez d'abord une catégorie</option>
                                    </select>
                                    <div class="invalid-feedback" id="type_error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Quantité -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="quantite">Quantité *</label>
                                    <input type="number" class="form-control" 
                                           id="quantite" name="quantite" value="1" 
                                           min="0" step="0.01" placeholder="0.00" required>
                                    <div class="invalid-feedback" id="quantite_error"></div>
                                </div>
                            </div>

                            <!-- Prix -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="prix">Prix (DT) *</label>
                                    <input type="number" class="form-control" 
                                           id="prix" name="prix" value="0" 
                                           min="0" step="0.01" placeholder="0.00" required>
                                    <div class="invalid-feedback" id="prix_error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_calcule">Total calculé</label>
                                    <input type="text" class="form-control" id="total_calcule" 
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
                            <textarea class="form-control" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Décrivez l'activité réalisée..." required></textarea>
                            <div class="invalid-feedback" id="description_error"></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
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
    // Variables globales
    let currentCategorieId = null;
    
    // Fonction pour afficher les alertes
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;
        
        $('#ajaxAlertContainer').html(alertHtml);
        
        // Supprimer automatiquement après 5 secondes
        setTimeout(() => {
            $('#ajaxAlertContainer .alert').alert('close');
        }, 5000);
    }

    // Fonction pour effacer les erreurs de validation
    function clearValidationErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
    }

    // Fonction pour afficher les erreurs de validation
    function showValidationErrors(errors) {
        clearValidationErrors();
        
        $.each(errors, function(field, messages) {
            const input = $(`[name="${field}"]`);
            const errorDiv = $(`#${field}_error`);
            
            if (input.length) {
                input.addClass('is-invalid');
                if (errorDiv.length) {
                    errorDiv.text(messages[0]);
                }
            }
        });
    }

    // Fonction pour charger les catégories
    function loadCategories() {
        $.ajax({
            url: '{{ route("categories.ajax") }}',
            type: 'GET',
            success: function(response) {
                const categorieSelect = $('#categorieList');
                categorieSelect.empty().append('<option value="">Sélectionnez une catégorie</option>');
                
                response.forEach(function(categorie) {
                    categorieSelect.append(
                        `<option value="${categorie.id}">${categorie.nom}</option>`
                    );
                });
                
                // Si une catégorie était sélectionnée avant le rechargement, la reselectionner
                if (currentCategorieId) {
                    categorieSelect.val(currentCategorieId).trigger('change');
                }
            },
            error: function() {
                showAlert('danger', 'Erreur lors du chargement des catégories');
            }
        });
    }

    // Fonction pour charger les types d'une catégorie
    function loadTypes(categorieId) {
        if (!categorieId) {
            $('#type').empty().append('<option value="">Sélectionnez d\'abord une catégorie</option>');
            return;
        }
        
        currentCategorieId = categorieId;
        
        $.ajax({
            url: `{{ url("categories") }}/${categorieId}/types`,
            type: 'GET',
            success: function(response) {
                const typeSelect = $('#type');
                typeSelect.empty().append('<option value="">Sélectionnez un type (optionnel)</option>');
                
                if (response.length > 0) {
                    response.forEach(function(type) {
                        typeSelect.append(
                            `<option value="${type.id}">${type.nom}</option>`
                        );
                    });
                }
            },
            error: function() {
                showAlert('danger', 'Erreur lors du chargement des types');
            }
        });
    }

    // Gestion du changement de catégorie
    $('#categorieList').change(function() {
        const categorieId = $(this).val();
        loadTypes(categorieId);
    });

    // Calcul automatique du total
    function calculateTotal() {
        const quantite = parseFloat($('#quantite').val()) || 0;
        const prix = parseFloat($('#prix').val()) || 0;
        const total = quantite * prix;
        
        $('#total_calcule').val(total.toFixed(2) + ' DT');
    }

    // Écouter les changements sur quantité et prix
    $('#quantite, #prix').on('input', function() {
        calculateTotal();
    });

    // Gérer la soumission du formulaire via AJAX
    $('#timesheetForm').on('submit', function(e) {
        e.preventDefault();
        
        // Désactiver le bouton de soumission
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Création en cours...');
        
        // Récupérer les données du formulaire
        const formData = new FormData(this);
        
        // Récupérer l'URL depuis l'attribut action du formulaire original
        const url = '{{ route("dossiers.timesheets.store", ["dossier" => $dossier->id]) }}';
        
        // Envoyer la requête AJAX
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Réactiver le bouton
                submitBtn.prop('disabled', false).html(originalText);
                
                if (response.success) {
                    // Afficher le message de succès
                    showAlert('success', response.message);
                    
                    // Réinitialiser le formulaire
                    $('#timesheetForm')[0].reset();
                    $('#total_calcule').val('0.00 DT');
                    loadTypes('');
                    
                    // Rafraîchir le tableau des feuilles de temps si il existe
                    // if (typeof window.refreshTimesheetsTable === 'function') {
                    //     window.refreshTimesheetsTable();
                    // }
                    
                    // Rafraîchir les totaux si la fonction existe
                    // if (typeof window.refreshTotals === 'function') {
                    //     window.refreshTotals();
                    // }
                    
                    // Fermer le modal après 2 secondes
                    setTimeout(function() {
                        $('#timesheetModal').modal('hide');
                    }, 2000);
                }
            },
            error: function(xhr) {
                // Réactiver le bouton
                submitBtn.prop('disabled', false).html(originalText);
                
                if (xhr.status === 422) {
                    // Erreurs de validation
                    const errors = xhr.responseJSON.errors;
                    showValidationErrors(errors);
                    showAlert('danger', 'Veuillez corriger les erreurs dans le formulaire');
                } else {
                    // Autres erreurs
                    const message = xhr.responseJSON?.message || 'Une erreur est survenue lors de la création';
                    showAlert('danger', message);
                }
            }
        });
    });

    // Reset form when modal is closed
    $('#timesheetModal').on('hidden.bs.modal', function () {
        clearValidationErrors();
        $('#ajaxAlertContainer').empty();
        $('#timesheetForm')[0].reset();
        $('#total_calcule').val('0.00 DT');
        $('#type').empty().append('<option value="">Sélectionnez d\'abord une catégorie</option>');
        currentCategorieId = null;
        
        // Réinitialiser le bouton
        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Créer la feuille de temps');
    });

    // Auto-sélection de l'utilisateur connecté si c'est un avocat/secrétaire
    @if(auth()->user()->fonction === 'avocat' || auth()->user()->fonction === 'secrétaire')
        $('#utilisateur_id').val('{{ auth()->id() }}').trigger('change');
    @endif

    // Initialisation
    loadCategories();
    calculateTotal();
});

// Fonctions globales pour rafraîchir les données (à définir dans votre page principale)
window.refreshTimesheetsTable = function() {
    // Cette fonction doit être définie dans votre page principale
    // pour rafraîchir le tableau des feuilles de temps
    console.log('Rafraîchissement du tableau des feuilles de temps');
    // Exemple: $('#timesheets-table').DataTable().ajax.reload();
};

window.refreshTotals = function() {
    // Cette fonction doit être définie dans votre page principale
    // pour rafraîchir les totaux affichés
    console.log('Rafraîchissement des totaux');
    // Exemple: $.get('/dossiers/{{ $dossier->id }}/timesheets/totals', function(data) { ... });
};
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
    #ajaxAlertContainer {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        max-width: 400px;
    }
</style>