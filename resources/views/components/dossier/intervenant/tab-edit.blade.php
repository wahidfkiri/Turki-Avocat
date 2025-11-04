<!-- Onglet Intervenants -->
<div class="tab-pane fade" id="intervenants" role="tabpanel" aria-labelledby="intervenants-tab">
    <div class="p-3">
        <h5 class="text-primary mb-3"><i class="fas fa-handshake"></i> Gestion des intervenants</h5>
        
        <!-- Client principal -->
        <div class="row d-none">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="client_id">Client principal</label>
                    <select class="form-control @error('client_id') is-invalid @enderror" 
                            id="client_id" name="client_id">
                        <option value="">Sélectionnez le client</option>
                        @foreach($intervenants as $intervenant)
                            @if($intervenant->categorie == 'client')
                                <option value="{{ $intervenant->id }}" {{ old('client_id', $dossier->intervenants()->wherePivot('role', 'client')->first()?->id) == $intervenant->id ? 'selected' : '' }}>
                                    {{ $intervenant->identite_fr }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('client_id')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Autres intervenants -->
        <div class="row">
            <div class="col-md-12">
                
                                                    <div class="d-flex justify-content-end align-items-center mb-3">
                                                        <h5 class="text-primary mb-0 d-none"><i class="fas fa-users"></i> Intervenants Liés</h5>
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#linkIntervenantModal" style="float:right;">
                                                            <i class="fas fa-link"></i> Lier un intervenant
                                                        </button>
                                                    </div>
                                                     <!-- Tableau des intervenants liés -->
                                                    <div class="card">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0"><i class="fas fa-table"></i> Liste des intervenants liés</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped" id="linkedIntervenantsTable">
                                                                    <thead class="thead-dark">
                                                                        <tr>
                                                                            <th width="30%">Intervenant Lié</th>
                                                                            <th width="30%">Role (de cet intervenant)</th>
                                                                            <th width="10%">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="linked-intervenants-container">
                                                                        @php
    $existingLinkedIntervenants = [];
    if ($dossier->intervenants->count() > 0) {
        foreach ($dossier->intervenants as $index => $linkedIntervenant) {
            $pivot = $linkedIntervenant->pivot;
            $existingLinkedIntervenants[] = [
                'intervenant_id' => $linkedIntervenant->id,
                'intervenant_name' => $linkedIntervenant->identite_fr,
                'role' => $pivot->role,
            ];
        }
    }
@endphp

                                                                        @if(old('linked_intervenants'))
                                                                            <!-- Afficher les intervenants depuis la validation -->
                                                                            @foreach(old('linked_intervenants') as $index => $linkedIntervenant)
                                                                            <tr class="linked-intervenant-item">
                                                                                <td>
                                                                                    <strong>{{ $linkedIntervenant['intervenant_name'] ?? 'Intervenant' }}</strong>
                                                                                    <input type="hidden" name="linked_intervenants[{{ $index }}][intervenant_id]" 
                                                                                           value="{{ $linkedIntervenant['intervenant_id'] }}">
                                                                                    <input type="hidden" name="linked_intervenants[{{ $index }}][intervenant_name]" 
                                                                                           value="{{ $linkedIntervenant['intervenant_name'] }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control" 
                                                                                           name="linked_intervenants[{{ $index }}][role]" 
                                                                                           value="{{ $linkedIntervenant['intervenant_id'] ?? '' }}"
                                                                                           placeholder="Ex: Client, Partenaire, Associé..."
                                                                                           required>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <button type="button" class="btn btn-danger btn-sm remove-linked-intervenant">
                                                                                        <i class="fas fa-trash"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        @elseif(count($existingLinkedIntervenants) > 0)
                                                                            <!-- Afficher les intervenants existants depuis la base de données -->
                                                                            @foreach($existingLinkedIntervenants as $index => $linkedIntervenant)
                                                                            <tr class="linked-intervenant-item">
                                                                                <td>
                                                                                    <strong>{{ $linkedIntervenant['intervenant_name'] }}</strong>
                                                                                    <input type="hidden" name="linked_intervenants[{{ $index }}][intervenant_id]" 
                                                                                           value="{{ $linkedIntervenant['intervenant_id'] }}">
                                                                                    <input type="hidden" name="linked_intervenants[{{ $index }}][intervenant_name]" 
                                                                                           value="{{ $linkedIntervenant['intervenant_name'] }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control" 
                                                                                           name="linked_intervenants[{{ $index }}][role]" 
                                                                                           value="{{ $linkedIntervenant['role'] }}"
                                                                                           placeholder="Ex: Client, Partenaire, Associé..."
                                                                                           required>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <button type="button" class="btn btn-danger btn-sm remove-linked-intervenant">
                                                                                        <i class="fas fa-trash"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            <!-- Message quand aucun intervenant n'est lié -->
                                                            <div id="no-linked-intervenants" class="text-center py-4" 
                                                                 style="{{ (old('linked_intervenants') || count($existingLinkedIntervenants) > 0) ? 'display: none;' : '' }}">
                                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                                <p class="text-muted">Aucun intervenant lié pour le moment</p>
                                                            </div>
                                                        </div>
                                                    </div>

                <div class="form-group d-none">
                    <label>Autres intervenants</label>
                    <select class="form-control" id="autres_intervenants" name="autres_intervenants[]" multiple>
                        @foreach($intervenants as $intervenant)
                          
                                @php
                                    $isSelected = $dossier->intervenants()
                                        ->where('intervenants.id', $intervenant->id)
                                        ->exists();
                                @endphp
                                <option value="{{ $intervenant->id }}" {{ $isSelected ? 'selected' : '' }}>
                                    {{ $intervenant->identite_fr }} ({{ $intervenant->categorie }})
                                </option>
                         
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Rôles des intervenants -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <h6><i class="icon fas fa-info"></i> Information</h6>
                    <p class="mb-0">
                        Sélectionnez le client principal et éventuellement d'autres intervenants (avocats adverses, experts, etc.).
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal pour lier un intervenant -->
<div class="modal fade" id="linkIntervenantModal" tabindex="-1" role="dialog" aria-labelledby="linkIntervenantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="linkIntervenantModalLabel">
                    <i class="fas fa-users"></i> Sélectionner un intervenant à lier
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Filtre de recherche -->
                <div class="form-group d-none">
                    <label for="intervenantFilter">Filtrer les intervenants</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="intervenantFilter" 
                               placeholder="Tapez pour filtrer par nom, email ou catégorie...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="clearFilter">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <small class="form-text text-muted">
                        Tapez pour filtrer la liste des intervenants. {{ $intervenants->count() }} intervenant(s) disponible(s).
                    </small>
                </div>

                <!-- Liste des intervenants disponibles -->
                <div class="form-group w-100" style="display:grid;">
                    <label for="intervenantList">Choisir un intervenant</label>
                    <select class="form-control search_test2" id="intervenantList">
                        <option value="">-- Sélectionnez un intervenant --</option>
                        @foreach($intervenants as $intervenantItem)
                            <option value="{{ $intervenantItem->id }}" 
                                    data-name="{{ $intervenantItem->identite_fr }}"
                                    data-email="{{ $intervenantItem->mail1 ?? 'N/A' }}"
                                    data-phone="{{ $intervenantItem->portable1 ?? 'N/A' }}"
                                    data-category="{{ $intervenantItem->categorie ?? 'N/A' }}"
                                    class="intervenant-option">
                                {{ $intervenantItem->identite_fr }} 
                                @if($intervenantItem->mail1)
                                    - {{ $intervenantItem->mail1 }}
                                @endif
                                @if($intervenantItem->categorie)
                                    ({{ $intervenantItem->categorie }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <div id="noResults" class="alert alert-warning mt-2" style="display: none;">
                        <i class="fas fa-search"></i> Aucun intervenant ne correspond à votre recherche.
                    </div>
                </div>

                <!-- Aperçu de l'intervenant sélectionné -->
                <div id="intervenantPreview" class="card mt-3" style="display: none;">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-eye"></i> Aperçu de l'intervenant</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="30%"><strong>Nom :</strong></td>
                                        <td id="previewName"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email :</strong></td>
                                        <td id="previewEmail"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Téléphone :</strong></td>
                                        <td id="previewPhone"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Catégorie :</strong></td>
                                        <td id="previewCategory"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4 text-right">
                                <button type="button" class="btn btn-success" id="confirmLinkIntervenant">
                                    <i class="fas fa-link"></i> Lier cet intervenant
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message si aucun intervenant disponible -->
                @if($intervenants->count() <= 1)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Aucun autre intervenant disponible pour le moment.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<!-- Add this JavaScript code -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const clientPrincipalSelect = document.getElementById('client_id');
    const autresIntervenantsSelect = document.getElementById('autres_intervenants');
    
    // Function to hide selected client principal from autres intervenants
    function updateAutresIntervenants() {
        const selectedClientId = clientPrincipalSelect.value;
        
        // Reset all options to be visible
        for (let option of autresIntervenantsSelect.options) {
            option.classList.remove('hidden-item');
        }
        
        // Hide the selected client principal
        if (selectedClientId) {
            for (let option of autresIntervenantsSelect.options) {
                if (option.value === selectedClientId) {
                    option.classList.add('hidden-item');
                    
                    // Also deselect it if it was selected
                    option.selected = false;
                }
            }
        }
    }
    
    // Add event listener to client principal select
    if (clientPrincipalSelect) {
        clientPrincipalSelect.addEventListener('change', updateAutresIntervenants);
        
        // Initialize on page load
        updateAutresIntervenants();
    }
});
</script>