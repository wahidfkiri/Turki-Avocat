<!-- Modal d'édition -->
<div class="modal fade" id="editTimesheetModal" tabindex="-1" role="dialog" aria-labelledby="editTimesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTimesheetModalLabel">
                    <i class="fas fa-edit"></i> Modifier la Feuille de Temps
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editTimesheetForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_timesheet_id" name="id">
                
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Date -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_date_timesheet">Date *</label>
                                    <input type="date" class="form-control" 
                                           id="edit_date_timesheet" name="date_timesheet" required>
                                    <div class="invalid-feedback" id="edit_date_timesheet_error"></div>
                                </div>
                            </div>

                            <!-- Utilisateur -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_utilisateur_id">Utilisateur *</label>
                                    <select class="form-control " 
                                            id="edit_utilisateur_id" name="utilisateur_id" required>
                                        <option value="">Sélectionnez un utilisateur</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->fonction }})</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="edit_utilisateur_id_error"></div>
                                </div>
                            </div>
                            <!-- Dossier -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_dossier_id">Dossier</label>
                                    <select class="form-control " 
                                            id="edit_dossier_id" name="dossier_id">
                                        <option value="">Sélectionnez un dossier</option>
                                        @foreach($dossiers as $dossier)
                                            <option value="{{ $dossier->id }}">{{ $dossier->numero_dossier }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="edit_dossier_id_error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Catégorie -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_categorie">Catégorie</label>
                                    <select class="form-control " 
                                            id="edit_categorie" name="categorie">
                                        <option value="">Sélectionnez une catégorie</option>
                                        @foreach($categories as $categorie)
                                            <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="edit_categorie_error"></div>
                                </div>
                            </div>
                            <!-- Type -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_type">Type</label>
                                    <select class="form-control " 
                                            id="edit_type" name="type">
                                        <option value="">Sélectionnez un type</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->nom }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="edit_type_error"></div>
                                </div>
                            </div>

                        </div>

                        <!-- Total calculé -->
                        <div class="row">
                            <!-- Quantité -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_quantite">Quantité *</label>
                                    <input type="number" class="form-control" 
                                           id="edit_quantite" name="quantite" 
                                           min="0" step="0.01" placeholder="0.00" required>
                                    <div class="invalid-feedback" id="edit_quantite_error"></div>
                                </div>
                            </div>

                            <!-- Prix -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_prix">Prix (DT) *</label>
                                    <input type="number" class="form-control" 
                                           id="edit_prix" name="prix" 
                                           min="0" step="0.01" placeholder="0.00" required>
                                    <div class="invalid-feedback" id="edit_prix_error"></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="edit_total_calcule">Total calculé</label>
                                    <input type="text" class="form-control" id="edit_total_calcule" 
                                           value="0,00 DT" readonly 
                                           style="background-color: #f8f9fa; font-weight: bold;">
                                    <small class="form-text text-muted">
                                        Calcul automatique : Quantité × Prix
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="edit_description">Description *</label>
                            <textarea class="form-control" 
                                      id="edit_description" name="description" rows="3" 
                                      placeholder="Décrivez l'activité réalisée..." required></textarea>
                            <div class="invalid-feedback" id="edit_description_error"></div>
                        </div>

                        <!-- Informations de suivi -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Informations de suivi</label>
                                    <div class="alert alert-info" id="edit_tracking_info" style="color:black;">
                                        <small>
                                            <strong>Créé le:</strong> <span id="created_at"></span><br>
                                            <strong>Modifié le:</strong> <span id="updated_at"></span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.modal-xl {
    max-width: 1140px;
}

.-container {
    width: 100% !important;
}

#editTimesheetForm .is-invalid {
    border-color: #dc3545;
}

#editTimesheetForm .invalid-feedback {
    display: block;
}
</style>