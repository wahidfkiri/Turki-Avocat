<div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
    <div class="p-3">
        <!-- Alert container -->
        <div id="notesAlertContainer" class="mb-3" style="display: none;"></div>
        
        <div class="form-group">
            <label>Notes et observations</label>
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-sticky-note"></i> Notes du dossier</h6>
                    <button type="button" class="btn btn-sm btn-primary ml-auto" id="editNotesBtn">
                        <i class="fas fa-edit"></i> Modifier
                    </button>
                </div>
                <div class="card-body">
                    <!-- Mode lecture -->
                    <div id="notesViewMode" class="bg-light p-3 rounded" style="min-height: 200px;">
                        @if($dossier->note)
                            {!! nl2br(e($dossier->note)) !!}
                        @else
                            <span class="text-muted">Aucune note ou observation. Cliquez sur "Modifier" pour ajouter des notes.</span>
                        @endif
                    </div>
                    
                    <!-- Mode édition -->
                    <div id="notesEditMode" style="display: none;">
                        <form id="notesForm">
                            @csrf
                            <div class="form-group">
                                <textarea class="form-control" id="notesTextarea" 
                                          rows="10" 
                                          placeholder="Saisissez vos notes et observations ici...">{{ $dossier->note }}</textarea>
                                <small class="form-text text-muted">
                                    Vous pouvez utiliser du texte simple. Les retours à la ligne seront conservés.
                                </small>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary mr-2" id="cancelEditNotesBtn">
                                    <i class="fas fa-times"></i> Annuler
                                </button>
                                <button type="submit" class="btn btn-primary" id="saveNotesBtn">
                                    <i class="fas fa-save"></i> Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <small>
                        <i class="fas fa-info-circle"></i> Dernière modification : 
                        <span id="lastUpdatedAt">{{ $dossier->updated_at ? $dossier->updated_at->format('d/m/Y à H:i') : 'Jamais' }}</span>
                    </small>
                </div>
            </div>
        </div>

        <!-- Informations de suivi -->
        <h5 class="text-primary mb-3 mt-4"><i class="fas fa-history"></i> Informations de suivi</h5>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info" style="color:black;">
                    <small>
                        <strong>Créé le:</strong> {{ $dossier->created_at->format('d/m/Y à H:i') }}<br>
                        <strong>Modifié le:</strong> <span id="dossierUpdatedAt">{{ $dossier->updated_at->format('d/m/Y à H:i') }}</span><br>
                        @if($dossier->domaine)
                            <strong>Domaine:</strong> {{ $dossier->domaine->nom }}<br>
                        @endif
                        @if($dossier->sousDomaine)
                            <strong>Sous-domaine:</strong> {{ $dossier->sousDomaine->nom ?? 'N/A' }}
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript pour la gestion des notes -->
<script>
$(document).ready(function() {
    // Variables
    const dossierId = {{ $dossier->id }};
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    // ==================== GESTION DES NOTES ====================
    
    // Activer le mode édition
    $('#editNotesBtn').click(function() {
        $('#notesViewMode').hide();
        $('#notesEditMode').show();
        $(this).hide();
        $('#notesTextarea').focus();
    });
    
    // Annuler l'édition
    $('#cancelEditNotesBtn').click(function() {
        $('#notesEditMode').hide();
        $('#notesViewMode').show();
        $('#editNotesBtn').show();
        
        // Réinitialiser le texte avec la valeur originale
        $('#notesTextarea').val(`{!! addslashes($dossier->note) !!}`);
    });
    
    // Soumettre le formulaire de notes
    $('#notesForm').submit(function(e) {
        e.preventDefault();
        saveNotes();
    });
    
    // Fonction pour sauvegarder les notes
    function saveNotes() {
        const notes = $('#notesTextarea').val().trim();
        
        $.ajax({
            url: `/dossiers/${dossierId}/update-notes`,
            type: 'POST',
            data: {
                _token: csrfToken,
                notes: notes
            },
            beforeSend: function() {
                $('#saveNotesBtn').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...');
            },
            success: function(response) {
                if (response.success) {
                    // Mettre à jour l'affichage en mode lecture
                    if (notes) {
                        $('#notesViewMode').html(notes.replace(/\n/g, '<br>'));
                    } else {
                        $('#notesViewMode').html('<span class="text-muted">Aucune note ou observation. Cliquez sur "Modifier" pour ajouter des notes.</span>');
                    }
                    
                    // Mettre à jour la date de dernière modification
                    if (response.data.updated_at) {
                        const updatedAt = new Date(response.data.updated_at);
                        const formattedDate = updatedAt.toLocaleDateString('fr-FR', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        $('#lastUpdatedAt').text(formattedDate);
                        $('#dossierUpdatedAt').text(formattedDate);
                    }
                    
                    // Retourner en mode lecture
                    $('#notesEditMode').hide();
                    $('#notesViewMode').show();
                    $('#editNotesBtn').show();
                    
                    showAlert('success', response.message || 'Notes enregistrées avec succès', '#notesAlertContainer');
                } else {
                    showAlert('danger', response.message || 'Erreur lors de l\'enregistrement', '#notesAlertContainer');
                }
            },
            error: function(xhr) {
                let errorMsg = 'Une erreur est survenue lors de l\'enregistrement';
                
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    errorMsg = Object.values(errors).flat().join(', ');
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                showAlert('danger', errorMsg, '#notesAlertContainer');
            },
            complete: function() {
                $('#saveNotesBtn').prop('disabled', false)
                    .html('<i class="fas fa-save"></i> Enregistrer');
            }
        });
    }
    
    // ==================== FONCTIONS UTILITAIRES ====================
    
    // Fonction pour afficher une alerte
    function showAlert(type, message, container) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;
        
        $(container).html(alertHtml).show();
        
        setTimeout(() => {
            $(container + ' .alert').alert('close');
        }, 5000);
    }
    
    // Permettre d'appuyer sur Ctrl+Enter pour sauvegarder dans le textarea
    $('#notesTextarea').keydown(function(e) {
        if ((e.ctrlKey || e.metaKey) && e.keyCode === 13) {
            saveNotes();
        }
    });
});
</script>

<style>
/* Styles pour les notes */
#notesViewMode {
    line-height: 1.6;
    white-space: pre-line;
}

#notesTextarea {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    line-height: 1.5;
    resize: vertical;
    min-height: 200px;
}

#notesViewMode .text-muted {
    font-style: italic;
    color: #6c757d !important;
}

/* Animation pour le bouton d'édition */
#editNotesBtn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Style pour les alertes */
#notesAlertContainer .alert {
    margin-bottom: 0;
}
</style>