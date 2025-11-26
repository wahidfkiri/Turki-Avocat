<!-- Modal combiné pour visualisation et édition de facture -->
<div class="modal fade" id="viewFactureModal" tabindex="-1" role="dialog" aria-labelledby="viewFactureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewFactureModalLabel">
                    <i class="fas fa-eye"></i> Détails de la Facture
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="viewFactureModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Chargement...</span>
                    </div>
                    <p class="mt-2 text-muted">Chargement des détails...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Fermer
                </button>
                <button type="button" class="btn btn-warning" id="editFactureBtn" style="display: none;">
                    <i class="fas fa-edit"></i> Modifier
                </button>
                <button type="button" class="btn btn-primary" id="saveFactureBtn" style="display: none;">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <button type="button" class="btn btn-secondary" id="cancelEditFactureBtn" style="display: none;">
                    <i class="fas fa-undo"></i> Annuler
                </button>
            </div>
        </div>
    </div>
</div>