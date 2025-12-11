<!-- Modal pour créer une tâche -->
<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">Nouvelle Tâche</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Container pour les messages AJAX -->
                <div id="ajax-messages" style="display: none;">
                    <!-- Les messages seront insérés ici dynamiquement -->
                </div>

                <!-- Formulaire -->
                <form id="taskForm" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body" style="padding: 0;">
                        <div class="row">
                            <!-- Titre -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="titre">Titre *</label>
                                    <input type="text" class="form-control" 
                                           id="titre" name="titre" 
                                           placeholder="Entrez le titre de la tâche" required>
                                    <span class="invalid-feedback" id="titre-error" role="alert"></span>
                                </div>
                            </div>

                            <!-- Priorité -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priorite">Priorité *</label>
                                    <select class="form-control" 
                                            id="priorite" name="priorite" required>
                                        <option value="">Sélectionnez une priorité</option>
                                        <option value="basse">Basse</option>
                                        <option value="normale">Normale</option>
                                        <option value="haute">Haute</option>
                                        <option value="urgente">Urgente</option>
                                    </select>
                                    <span class="invalid-feedback" id="priorite-error" role="alert"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Statut -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="statut">Statut *</label>
                                    <select class="form-control" 
                                            id="statut" name="statut" required>
                                        <option value="">Sélectionnez un statut</option>
                                        <option value="a_faire">À faire</option>
                                        <option value="en_cours">En cours</option>
                                        <option value="terminee">Terminée</option>
                                        <option value="en_retard">En retard</option>
                                    </select>
                                    <span class="invalid-feedback" id="statut-error" role="alert"></span>
                                </div>
                            </div>

                            <!-- Utilisateur assigné -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="utilisateur_id">Assigné à *</label>
                                    <select class="form-control" 
                                            id="utilisateur_id" name="utilisateur_id" required>
                                        <option value="">Sélectionnez un utilisateur</option>
                                        @if(auth()->user()->hasRole('admin'))
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $user->name }} ({{ $user->fonction }})
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="{{ auth()->user()->id }}">
                                                {{ auth()->user()->name }} ({{ auth()->user()->fonction }})
                                            </option>
                                        @endif
                                    </select>
                                    <span class="invalid-feedback" id="utilisateur_id-error" role="alert"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Date de début -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_debut">Date de début</label>
                                    <input type="date" class="form-control" 
                                           id="date_debut" name="date_debut">
                                    <span class="invalid-feedback" id="date_debut-error" role="alert"></span>
                                </div>
                            </div>

                            <!-- Date de fin -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_fin">Date de fin</label>
                                    <input type="date" class="form-control" 
                                           id="date_fin" name="date_fin">
                                    <span class="invalid-feedback" id="date_fin-error" role="alert"></span>
                                </div>
                            </div>

                            <!-- Dossier -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="dossier_id">Dossier</label>
                                    <select class="form-control" 
                                            id="dossier_id" name="dossier_id">
                                        <option value="">Sélectionnez un dossier</option>
                                            <option value="{{ $dossier->id }}" selected>
                                                {{ $dossier->numero_dossier }}
                                            </option>
                                    </select>
                                    <span class="invalid-feedback" id="dossier_id-error" role="alert"></span>
                                </div>
                            </div>

                            <!-- Intervenant -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="intervenant_id">Intervenant</label>
                                    <select class="form-control" 
                                            id="intervenant_id" name="intervenant_id">
                                        <option value="">Sélectionnez un intervenant</option>
                                        @foreach($dossier->intervenants as $intervenant)
                                            <option value="{{ $intervenant->id }}">
                                                {{ $intervenant->identite_fr }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback" id="intervenant_id-error" role="alert"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Décrivez la tâche en détail..."></textarea>
                            <span class="invalid-feedback" id="description-error" role="alert"></span>
                        </div>

                        <!-- Note -->
                        <div class="form-group">
                            <label for="note">Notes supplémentaires</label>
                            <textarea class="form-control" 
                                      id="note" name="note" rows="2" 
                                      placeholder="Ajoutez des notes ou commentaires..."></textarea>
                            <span class="invalid-feedback" id="note-error" role="alert"></span>
                            <small class="form-text text-muted">
                                Ces notes sont internes et ne seront pas visibles par le client.
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="file">Fichier joint</label>
                            <div class="custom-file">
                                <input type="file" class="form-control" 
                                       id="file" name="file" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.xlsx,.xls">
                                <label class="custom-file-label" for="file" id="file-label">
                                    Choisir un fichier...
                                </label>
                            </div>
                            <span class="invalid-feedback d-block" id="file-error" role="alert"></span>
                            <small class="form-text text-muted">
                                Formats acceptés: PDF, Word, Excel, TXT, JPG, PNG (Max: 10MB)
                            </small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtnTask">
                            <i class="fas fa-save"></i> Créer la tâche
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap4'
        });

        // Fonction pour afficher les messages
        function showMessage(type, message) {
            $('#ajax-messages').html(`
                <div class="alert alert-${type} alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    ${type === 'success' ? '<h5><i class="icon fas fa-check"></i> Succès!</h5>' : '<h5><i class="icon fas fa-ban"></i> Erreur!</h5>'}
                    ${message}
                </div>
            `).show();
            
            // Cacher le message après 5 secondes
            setTimeout(function() {
                $('#ajax-messages').fadeOut();
            }, 5000);
        }

        // Fonction pour réinitialiser les erreurs
        function resetErrors() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').html('');
            $('#ajax-messages').hide();
        }

        // Fonction pour afficher les erreurs de validation
        function showValidationErrors(errors) {
            resetErrors();
            $.each(errors, function(field, messages) {
                var input = $('[name="' + field + '"]');
                var errorElement = $('#' + field + '-error');
                
                input.addClass('is-invalid');
                errorElement.html('<strong>' + messages[0] + '</strong>');
            });
        }

        // Set today's date as default for date fields
        function setDefaultDates() {
            var today = new Date().toISOString().split('T')[0];
            $('#date_debut').val(today);
        }

        // Gérer l'affichage du nom du fichier
        $('#file').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $('#file-label').text(fileName || 'Choisir un fichier...');
        });

        // Soumission du formulaire avec AJAX
        $('#taskForm').submit(function(e) {
            e.preventDefault();
            
            // Réinitialiser les erreurs
            resetErrors();
            
            // Désactiver le bouton de soumission
            var submitBtn = $('#submitBtnTask');
            var originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Création en cours...');
            
            // Créer FormData pour gérer les fichiers
            var formData = new FormData(this);
            
            // Ajouter le token CSRF si nécessaire
            formData.append('_token', '{{ csrf_token() }}');
            
            // AJAX request
            $.ajax({
                url: '{{ route("dossiers.tasks.store", ["dossier" => $dossier->id]) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Afficher message de succès
                        showMessage('success', response.message);
                        
                        // Réinitialiser le formulaire
                        $('#taskForm')[0].reset();
                        $('#file-label').text('Choisir un fichier...');
                        
                        // Fermer la modal après un délai
                        setTimeout(function() {
                            $('#taskModal').modal('hide');
                            
                           table.ajax.reload();
                            // Rafraîchir la page ou mettre à jour la liste des tâches
                            // location.reload();
                        }, 1500);
                    } else {
                        showMessage('error', response.message || 'Une erreur est survenue.');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        // Erreurs de validation
                        var errors = xhr.responseJSON.errors;
                        showValidationErrors(errors);
                    } else {
                        showMessage('error', 'Une erreur est survenue lors de la création de la tâche.');
                    }
                },
                complete: function() {
                    // Réactiver le bouton
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Auto-sélection de l'utilisateur connecté si c'est un avocat/secrétaire
        @if(auth()->user()->fonction === 'avocat' || auth()->user()->fonction === 'secrétaire')
            $('#utilisateur_id').val('{{ auth()->id() }}').trigger('change');
        @endif

        // Reset form when modal is opened
        $('#taskModal').on('show.bs.modal', function () {
            // Réinitialiser le formulaire
            $('#taskForm')[0].reset();
            $('#file-label').text('Choisir un fichier...');
            
            // Réinitialiser les erreurs
            resetErrors();
            
            // Réinitialiser Select2
            $('.select2').val(null).trigger('change');
            
            // Réactiver le bouton de soumission
            $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Créer la tâche');
            
            // Définir les dates par défaut
            setDefaultDates();
            
            // Auto-sélection de l'utilisateur connecté si c'est un avocat/secrétaire
            @if(auth()->user()->fonction === 'avocat' || auth()->user()->fonction === 'secrétaire')
                $('#utilisateur_id').val('{{ auth()->id() }}').trigger('change');
            @endif
            
            // Sélectionner le dossier par défaut
            $('#dossier_id').val('{{ $dossier->id }}').trigger('change');
        });

        // Gérer la fermeture de la modal
        $('#taskModal').on('hidden.bs.modal', function () {
            // S'assurer que le formulaire est réinitialisé
            $('#taskForm')[0].reset();
            resetErrors();
        });

        // Validation des dates
        $('#date_fin').on('change', function() {
            var startDate = $('#date_debut').val();
            var endDate = $(this).val();
            
            if (startDate && endDate) {
                var start = new Date(startDate);
                var end = new Date(endDate);
                
                if (end < start) {
                    $(this).addClass('is-invalid');
                    $('#date_fin-error').html('<strong>La date de fin doit être postérieure ou égale à la date de début</strong>');
                } else {
                    $(this).removeClass('is-invalid');
                    $('#date_fin-error').html('');
                }
            }
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
    .form-group {
        margin-bottom: 1rem;
    }
    .modal-body .card-body {
        max-height: 70vh;
        overflow-y: auto;
    }
    #ajax-messages {
        margin-bottom: 15px;
    }
    .is-invalid {
        border-color: #dc3545;
    }
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: .25rem;
        font-size: 80%;
        color: #dc3545;
    }
</style>