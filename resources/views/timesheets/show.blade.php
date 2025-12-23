<!-- Modal de visualisation -->
<div class="modal fade" id="showTimesheetModal" tabindex="-1" role="dialog" aria-labelledby="showTimesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="showTimesheetModalLabel">
                    <i class="fas fa-eye"></i> Détails de la Feuille de Temps
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="show-modal-content">
                    <!-- Le contenu sera chargé ici via AJAX -->
                    <div class="text-center p-4" id="show-loading-section">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p class="mt-2">Chargement des détails...</p>
                    </div>
                    
                    <!-- Les détails seront injectés ici -->
                    <div id="show-details-section" style="display: none;"></div>
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

<style>
#showTimesheetModal .modal-dialog {
    max-width: 1000px;
}

#showTimesheetModal .modal-header.bg-primary {
    background-color: #007bff !important;
}

#showTimesheetModal .card {
    border: 1px solid #dee2e6;
    margin-bottom: 1rem;
}

#showTimesheetModal .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

#showTimesheetModal .section-title {
    color: #495057;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
    border-bottom: 2px solid #007bff;
    font-weight: 600;
}

#showTimesheetModal .table th {
    background-color: #f8f9fa;
    font-weight: 600;
    width: 40%;
}

#showTimesheetModal .badge {
    font-size: 0.85em;
}

#showTimesheetModal .text-success {
    color: #28a745 !important;
    font-weight: bold;
}

#showTimesheetModal .text-muted {
    color: #6c757d !important;
}
</style>