<!-- Modal d'édition de tâche -->
<div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editTaskModalLabel">
                    <i class="fas fa-edit"></i> Modifier la Tâche
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="taskEditForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_task_id" name="task_id">
                    
                    <div class="row">
                        <!-- Titre -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_titre">Titre *</label>
                                <input type="text" class="form-control" id="edit_titre" name="titre" 
                                       placeholder="Entrez le titre de la tâche" required>
                                <div class="invalid-feedback" id="edit_titre-error"></div>
                            </div>
                        </div>

                        <!-- Priorité -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_priorite">Priorité *</label>
                                <select class="form-control" id="edit_priorite" name="priorite" required>
                                    <option value="">Sélectionnez une priorité</option>
                                    <option value="basse">Basse</option>
                                    <option value="normale">Normale</option>
                                    <option value="haute">Haute</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                                <div class="invalid-feedback" id="edit_priorite-error"></div>
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_statut">Statut *</label>
                                <select class="form-control" id="edit_statut" name="statut" required>
                                    <option value="">Sélectionnez un statut</option>
                                    <option value="a_faire">À faire</option>
                                    <option value="en_cours">En cours</option>
                                    <option value="terminee">Terminée</option>
                                    <option value="en_retard">En retard</option>
                                </select>
                                <div class="invalid-feedback" id="edit_statut-error"></div>
                            </div>
                        </div>

                        <!-- Utilisateur assigné -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_utilisateur_id">Assigné à *</label>
                                <select class="form-control" id="edit_utilisateur_id" name="utilisateur_id" required>
                                    <option value="">Sélectionnez un utilisateur</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name }} ({{ $user->fonction }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="edit_utilisateur_id-error"></div>
                            </div>
                        </div>

                        <!-- Date de début -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_date_debut">Date de début</label>
                                <input type="date" class="form-control" id="edit_date_debut" name="date_debut">
                                <div class="invalid-feedback" id="edit_date_debut-error"></div>
                            </div>
                        </div>

                        <!-- Date de fin -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_date_fin">Date de fin</label>
                                <input type="date" class="form-control" id="edit_date_fin" name="date_fin">
                                <div class="invalid-feedback" id="edit_date_fin-error"></div>
                            </div>
                        </div>

                        <!-- Dossier -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_dossier_id">Dossier</label>
                                <select class="form-control" id="edit_dossier_id" name="dossier_id">
                                    <option value="">Sélectionnez un dossier</option>
                                    @foreach($dossiers as $dossier)
                                        <option value="{{ $dossier->id }}">
                                            {{ $dossier->numero_dossier }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="edit_dossier_id-error"></div>
                            </div>
                        </div>

                        <!-- Intervenant -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_intervenant_id">Intervenant</label>
                                <select class="form-control" id="edit_intervenant_id" name="intervenant_id">
                                    <option value="">Sélectionnez un intervenant</option>
                                    @foreach($intervenants as $intervenant)
                                        <option value="{{ $intervenant->id }}">
                                            {{ $intervenant->identite_fr }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="edit_intervenant_id-error"></div>
                            </div>
                        </div>

                        <!-- Fichier -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit_file">Fichier joint</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="edit_file" name="file" 
                                           accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.xlsx,.xls">
                                    <label class="custom-file-label" for="edit_file" id="edit_file-label">
                                        Choisir un fichier...
                                    </label>
                                </div>
                                <div class="invalid-feedback" id="edit_file-error"></div>
                                <small class="form-text text-muted">
                                    Formats acceptés: PDF, Word, Excel, TXT, JPG, PNG (Max: 10MB)
                                </small>
                                
                                <!-- Info fichier existant -->
                                <div id="current-file-info" class="mt-2" style="display: none;">
                                    <div class="alert alert-info py-2">
                                        <small>
                                            <i class="fas fa-file"></i> 
                                            <span id="current-file-name"></span>
                                            <button type="button" class="btn btn-sm btn-outline-danger ml-2" id="remove-file-btn">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                            <input type="hidden" name="remove_file" id="remove_file" value="0">
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" 
                                  placeholder="Décrivez la tâche en détail..."></textarea>
                        <div class="invalid-feedback" id="edit_description-error"></div>
                    </div>

                    <!-- Note -->
                    <div class="form-group d-none">
                        <label for="edit_note">Notes supplémentaires</label>
                        <textarea class="form-control" id="edit_note" name="note" rows="2" 
                                  placeholder="Ajoutez des notes ou commentaires..."></textarea>
                        <div class="invalid-feedback" id="edit_note-error"></div>
                        <small class="form-text text-muted">
                            Ces notes sont internes et ne seront pas visibles par le client.
                        </small>
                    </div>

                    <!-- Informations de suivi -->
                    <div class="row d-none">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Informations de suivi</label>
                                <div class="alert alert-info text-black" id="task-info" style="color:black;">
                                    <!-- Les infos seront chargées dynamiquement -->
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="button" class="btn btn-danger" id="deleteTaskBtn" style="display: none;">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
                <button type="button" class="btn btn-primary" id="submitEditTask">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
            </div>
        </div>
    </div>
</div>


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
.alert-info {
    background-color: #e8f4fd;
    border-color: #b6e0fe;
}
</style>