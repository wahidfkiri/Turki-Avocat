<!-- Modal de création de tâche -->
<div class="modal fade" id="createTaskModal" tabindex="-1" role="dialog" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createTaskModalLabel">
                    <i class="fas fa-plus"></i> Nouvelle Tâche
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="taskCreateForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Titre -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titre">Titre *</label>
                                <input type="text" class="form-control" id="titre" name="titre" 
                                       placeholder="Entrez le titre de la tâche" required>
                                <div class="invalid-feedback" id="titre-error"></div>
                            </div>
                        </div>

                        <!-- Priorité -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="priorite">Priorité *</label>
                                <select class="form-control" id="priorite" name="priorite" required>
                                    <option value="">Sélectionnez une priorité</option>
                                    <option value="basse">Basse</option>
                                    <option value="normale">Normale</option>
                                    <option value="haute">Haute</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                                <div class="invalid-feedback" id="priorite-error"></div>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="statut">Statut *</label>
                                <select class="form-control" id="statut" name="statut" required>
                                    <option value="">Sélectionnez un statut</option>
                                    <option value="a_faire">À faire</option>
                                    <option value="en_cours">En cours</option>
                                    <option value="terminee">Terminée</option>
                                    <option value="en_retard">En retard</option>
                                </select>
                                <div class="invalid-feedback" id="statut-error"></div>
                            </div>
                        </div>

                        <!-- Utilisateur assigné -->
                        @if(auth()->user()->hasRole('admin'))
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="utilisateur_id">Assigné à *</label>
                                <select class="form-control" id="utilisateur_id" name="utilisateur_id" required>
                                    <option value="">Sélectionnez un utilisateur</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name }} ({{ $user->fonction }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="utilisateur_id-error"></div>
                            </div>
                        </div>
                        @else 
                        <input type="hidden" name="utilisateur_id" value="{{ auth()->id() }}">
                        @endif

                        <!-- Date de début -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_debut">Date de début</label>
                                <input type="date" class="form-control" id="date_debut" name="date_debut">
                                <div class="invalid-feedback" id="date_debut-error"></div>
                            </div>
                        </div>

                        <!-- Date de fin -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_fin">Date de fin</label>
                                <input type="date" class="form-control" id="date_fin" name="date_fin">
                                <div class="invalid-feedback" id="date_fin-error"></div>
                            </div>
                        </div>

                        <!-- Dossier -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dossier_id">Dossier</label>
                                <select class="form-control" id="dossier_id" name="dossier_id">
                                    <option value="">Sélectionnez un dossier</option>
                                    @foreach($dossiers as $dossier)
                                        <option value="{{ $dossier->id }}">
                                            {{ $dossier->numero_dossier }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="dossier_id-error"></div>
                            </div>
                        </div>

                        <!-- Intervenant -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="intervenant_id">Intervenant</label>
                                <select class="form-control" id="intervenant_id" name="intervenant_id">
                                    <option value="">Sélectionnez un intervenant</option>
                                    @foreach($intervenants as $intervenant)
                                        <option value="{{ $intervenant->id }}">
                                            {{ $intervenant->identite_fr }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="intervenant_id-error"></div>
                            </div>
                        </div>

                        <!-- Fichier -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="file">Fichier joint</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file" name="file" 
                                           accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.xlsx,.xls">
                                    <label class="custom-file-label" for="file" id="file-label">
                                        Choisir un fichier...
                                    </label>
                                </div>
                                <div class="invalid-feedback" id="file-error"></div>
                                <small class="form-text text-muted">
                                    Formats acceptés: PDF, Word, Excel, TXT, JPG, PNG (Max: 10MB)
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" 
                                  placeholder="Décrivez la tâche en détail..."></textarea>
                        <div class="invalid-feedback" id="description-error"></div>
                    </div>

                    <!-- Note -->
                    <div class="form-group">
                        <label for="note">Notes supplémentaires</label>
                        <textarea class="form-control" id="note" name="note" rows="2" 
                                  placeholder="Ajoutez des notes ou commentaires..."></textarea>
                        <div class="invalid-feedback" id="note-error"></div>
                        <small class="form-text text-muted">
                            Ces notes sont internes et ne seront pas visibles par le client.
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="button" class="btn btn-primary" id="submitCreateTask">
                    <i class="fas fa-save"></i> Créer la tâche
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// JavaScript pour le modal de création
function initTaskCreateModal() {

    // Gestion de l'affichage du nom du fichier
    $('#file').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $('#file-label').text(fileName || 'Choisir un fichier...');
    });

    // Soumission du formulaire de création
    $('#submitCreateTask').on('click', function() {
        createTask();
    });

    // Soumission avec Enter
    $('#taskCreateForm').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            createTask();
        }
    });

    function createTask() {
        // Réinitialiser les erreurs
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        
        // Désactiver le bouton et afficher le loader
        var submitBtn = $('#submitCreateTask');
        var originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Création...');

        // Créer FormData pour gérer les fichiers
        var formData = new FormData($('#taskCreateForm')[0]);

        $.ajax({
            url: '{{ route("tasks.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Fermer le modal
                $('#createTaskModal').modal('hide');
                
                // Recharger la table si elle existe (dans index)
                if (typeof table !== 'undefined') {
                    table.ajax.reload();
                }
                
                // Afficher le message de succès
                showAlert('success', 'Tâche créée avec succès!');
                
                // Réactiver le bouton
                submitBtn.prop('disabled', false).html(originalText);
                
                // Réinitialiser le formulaire
                $('#taskCreateForm')[0].reset();
                $('#file-label').text('Choisir un fichier...');
                $('#priorite, #statut, #utilisateur_id, #dossier_id, #intervenant_id')
                    .val('').trigger('change');
            },
            error: function(xhr) {
                // Réactiver le bouton
                submitBtn.prop('disabled', false).html(originalText);
                
                if (xhr.status === 422) {
                    // Gestion des erreurs de validation
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        var input = $('#' + field);
                        var errorDiv = $('#' + field + '-error');
                        
                        input.addClass('is-invalid');
                        errorDiv.text(messages[0]);
                        
                        // Pour les selects de Select2
                        if (input.hasClass('select2-hidden-accessible')) {
                            input.next('.select2-container').find('.select2-selection')
                                .addClass('is-invalid');
                        }
                    });
                    
                    // Scroll vers la première erreur
                    $('.is-invalid').first().focus();
                } else if (xhr.status === 403) {
                    // Erreur de permission
                    showAlert('error', 'Permission refusée', 'Vous n\'avez pas la permission de créer des tâches.');
                    $('#createTaskModal').modal('hide');
                } else {
                    // Erreur générale
                    showAlert('error', 'Erreur', 'Une erreur est survenue lors de la création de la tâche.');
                }
            }
        });
    }

    // Réinitialiser le formulaire quand le modal est fermé
    $('#createTaskModal').on('hidden.bs.modal', function () {
        $('#taskCreateForm')[0].reset();
        $('#file-label').text('Choisir un fichier...');
        $('.invalid-feedback').text('');
        $('.is-invalid').removeClass('is-invalid');
        
        // Réinitialiser Select2
        $('#priorite, #statut, #utilisateur_id, #dossier_id, #intervenant_id')
            .val('').trigger('change');
    });

    // Validation en temps réel
    $('#titre').on('blur', function() {
        if ($(this).val().length > 0 && $(this).val().length < 3) {
            showFieldError('titre', 'Le titre doit contenir au moins 3 caractères');
        } else {
            clearFieldError('titre');
        }
    });

    $('#date_fin').on('change', function() {
        var dateDebut = $('#date_debut').val();
        var dateFin = $(this).val();
        
        if (dateDebut && dateFin) {
            var start = new Date(dateDebut);
            var end = new Date(dateFin);
            
            if (end < start) {
                showFieldError('date_fin', 'La date de fin doit être postérieure ou égale à la date de début');
            } else {
                clearFieldError('date_fin');
            }
        }
    });

    function showFieldError(field, message) {
        var input = $('#' + field);
        var errorDiv = $('#' + field + '-error');
        
        input.addClass('is-invalid');
        errorDiv.text(message);
    }

    function clearFieldError(field) {
        var input = $('#' + field);
        var errorDiv = $('#' + field + '-error');
        
        input.removeClass('is-invalid');
        errorDiv.text('');
        
        if (input.hasClass('select2-hidden-accessible')) {
            input.next('.select2-container').find('.select2-selection')
                .removeClass('is-invalid');
        }
    }

    // Fonction utilitaire pour afficher les alertes
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fa-check' : 'fa-ban';
        const title = type === 'success' ? 'Succès!' : 'Erreur!';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas ${iconClass}"></i> ${title}</h5>
                ${message}
            </div>
        `;
        
        // Remove any existing alerts
        $('.alert-dismissible').remove();
        
        // Prepend the new alert
        $('.card').before(alertHtml);
        
        // Auto-remove alert after 5 seconds
        setTimeout(function() {
            $('.alert-dismissible').fadeOut(300, function() {
                $(this).remove();
            });
        }, 5000);
    }

    // Définir la date d'aujourd'hui par défaut
    var today = new Date().toISOString().split('T')[0];
    $('#date_debut').val(today);
    
    // Auto-sélection de l'utilisateur connecté si c'est un avocat/secrétaire
    @if(auth()->user()->fonction === 'avocat' || auth()->user()->fonction === 'secrétaire')
        $('#utilisateur_id').val('{{ auth()->id() }}').trigger('change');
    @endif
}

// Initialiser le modal quand le document est prêt
$(document).ready(function() {
    initTaskCreateModal();
});
</script>

<style>
.modal-xl {
    max-width: 900px;
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
.is-invalid .select2-selection {
    border-color: #dc3545 !important;
}
</style>