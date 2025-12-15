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
                                        <option value="{{ $user->id }}" @if(auth()->user()->id == $user->id) selected @endif>
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
                    <div class="form-group d-none">
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