<!-- Modal de visualisation de tâche -->
<div class="modal fade" id="viewTaskModal" tabindex="-1" role="dialog" aria-labelledby="viewTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="viewTaskModalLabel">
                    <i class="fas fa-eye"></i> Détails de la Tâche
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="viewTaskModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-info" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2 text-muted">Chargement des détails de la tâche...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Gestionnaire pour le bouton de visualisation
    $(document).on('click', '.view-task-btn', function() {
        const taskId = $(this).data('task-id');
        loadTaskDetails(taskId);
    });

    function loadTaskDetails(taskId) {
        // Afficher le loader
        $('#viewTaskModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-info" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Chargement des détails de la tâche...</p>
            </div>
        `);

        // Ouvrir le modal
        $('#viewTaskModal').modal('show');

        // Charger les données via le custom endpoint
        $.ajax({
            url: '/tasks/' + taskId + '/data',
            type: 'GET',
            success: function(response) {
                if (response.success && response.data) {
                    const task = response.data;
                    displayTaskForm(task);
                } else {
                    showError('Erreur lors du chargement des données.');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Une erreur est survenue lors du chargement.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                showError(errorMessage);
            }
        });
    }

    function displayTaskForm(task) {
        const formHtml = `
            <form>
                <div class="row">
                    <!-- Titre -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="view_titre" class="font-weight-bold">Titre</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   id="view_titre" value="${escapeHtml(task.titre || '')}" readonly>
                        </div>
                    </div>

                    <!-- Priorité -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="view_priorite" class="font-weight-bold">Priorité</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   id="view_priorite" value="${getPriorityLabel(task.priorite)}" readonly>
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="view_statut" class="font-weight-bold">Statut</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   id="view_statut" value="${getStatusLabel(task.statut)}" readonly>
                        </div>
                    </div>

                    <!-- Utilisateur assigné -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="view_utilisateur" class="font-weight-bold">Assigné à</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   id="view_utilisateur" value="${escapeHtml(task.user?.name || 'Non assigné')}" readonly>
                        </div>
                    </div>

                    <!-- Date de début -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="view_date_debut" class="font-weight-bold">Date de début</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   id="view_date_debut" value="${formatDate(task.date_debut)}" readonly>
                        </div>
                    </div>

                    <!-- Date de fin -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="view_date_fin" class="font-weight-bold">Date de fin</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   id="view_date_fin" value="${formatDate(task.date_fin)}" readonly>
                        </div>
                    </div>

                    <!-- Dossier -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="view_dossier" class="font-weight-bold">Dossier</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   id="view_dossier" value="${escapeHtml(task.dossier?.numero_dossier || 'Non lié')}" readonly>
                        </div>
                    </div>

                    <!-- Intervenant -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="view_intervenant" class="font-weight-bold">Intervenant</label>
                            <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                   id="view_intervenant" value="${escapeHtml(task.intervenant?.identite_fr || 'Non lié')}" readonly>
                        </div>
                    </div>

                    <!-- Fichier -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="font-weight-bold">Fichier joint</label>
                            ${task.file_path ? `
                                <div class="input-group">
                                    <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                           value="${escapeHtml(task.file_name)}" readonly>
                                    <div class="input-group-append">
                                        <a href="/tasks/${task.id}/download" 
                                           class="btn btn-outline-success" download>
                                            <i class="fas fa-download"></i> Télécharger
                                        </a>
                                    </div>
                                </div>
                            ` : `
                                <input type="text" class="form-control-plaintext bg-light p-2 rounded" 
                                       value="Aucun fichier joint" readonly>
                            `}
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="view_description" class="font-weight-bold">Description</label>
                    <textarea class="form-control-plaintext bg-light p-2 rounded" 
                              id="view_description" rows="4" readonly>${escapeHtml(task.description || 'Aucune description')}</textarea>
                </div>

                <!-- Note -->
                <div class="form-group">
                    <label for="view_note" class="font-weight-bold">Notes supplémentaires</label>
                    <textarea class="form-control-plaintext bg-light p-2 rounded" 
                              id="view_note" rows="3" readonly>${escapeHtml(task.note || 'Aucune note')}</textarea>
                </div>

                <!-- Informations de suivi -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-info text-black">
                            <h6 class="alert-heading"><i class="fas fa-info-circle"></i> Informations de suivi</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <small>
                                        <strong>Créé le:</strong> ${formatDateTime(task.created_at)}<br>
                                        <strong>Par:</strong> ${escapeHtml(task.user?.name || 'Inconnu')}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small>
                                        <strong>Modifié le:</strong> ${formatDateTime(task.updated_at)}<br>
                                        <strong>ID:</strong> ${task.id}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        `;
        
        $('#viewTaskModalBody').html(formHtml);
    }

    function showError(message) {
        $('#viewTaskModalBody').html(`
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                <h5>Erreur</h5>
                <p>${message}</p>
                <button class="btn btn-primary mt-2" onclick="loadTaskDetails(${taskId})">
                    <i class="fas fa-redo"></i> Réessayer
                </button>
            </div>
        `);
    }

    // Fonctions utilitaires
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function getPriorityLabel(priority) {
        const labels = {
            'basse': 'Basse',
            'normale': 'Normale', 
            'haute': 'Haute',
            'urgente': 'Urgente'
        };
        return labels[priority] || priority || 'Non définie';
    }

    function getStatusLabel(status) {
        const labels = {
            'a_faire': 'À faire',
            'en_cours': 'En cours',
            'terminee': 'Terminée',
            'en_retard': 'En retard'
        };
        return labels[status] || status || 'Non défini';
    }

    function formatDate(dateString) {
        if (!dateString) return 'Non définie';
        const date = new Date(dateString);
        return date.toLocaleDateString('fr-FR');
    }

    function formatDateTime(dateTimeString) {
        if (!dateTimeString) return 'Non définie';
        const date = new Date(dateTimeString);
        return date.toLocaleDateString('fr-FR') + ' à ' + date.toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'});
    }

    // Réinitialiser le modal quand il est fermé
    $('#viewTaskModal').on('hidden.bs.modal', function () {
        $('#viewTaskModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-info" role="status">
                    <span class="sr-only">Chargement...</span>
                </div>
                <p class="mt-2 text-muted">Chargement des détails de la tâche...</p>
            </div>
        `);
    });
});
</script>

<style>
.form-control-plaintext.bg-light {
    border: 1px solid #dee2e6;
    min-height: 38px;
    display: flex;
    align-items: center;
}
.form-control-plaintext.bg-light:focus {
    background-color: #e9ecef !important;
    border-color: #ced4da;
    outline: 0;
    box-shadow: none;
}
.text-black {
    color: black !important;
}
</style>