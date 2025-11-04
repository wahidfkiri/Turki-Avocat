<!-- Onglet Dossiers -->
                                            <div class="tab-pane fade" id="dossiers" role="tabpanel" aria-labelledby="dossiers-tab">
                                                <div class="p-3">
                                                    <h5 class="text-primary mb-3"><i class="fas fa-handshake"></i> Gestion des dossiers</h5>
                                                    

                                                    <!-- Autres dossiers -->
                                                    <div class="row">
                                                        <div class="col-md-12">

                                                        
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <h5 class="text-primary mb-0"><i class="fas fa-users"></i> dossiers Liés</h5>
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#linkDossierModal">
                                                            <i class="fas fa-link"></i> Lier un dossier
                                                        </button>
                                                    </div>


                                                    <!-- Tableau des intervenants liés -->
                                                    <div class="card">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0"><i class="fas fa-table"></i> Liste des dossiers liés</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped" id="linkedDossiersTable">
                                                                    <thead class="thead-dark">
                                                                        <tr>
                                                                            <th width="30%">Dossier Lié</th>
                                                                            <th width="30%">Relation</th>
                                                                            <th width="10%">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="linked-dossier-container">
                                                                        @if(old('linked_dossiers'))
                                                                            @foreach(old('linked_dossiers') as $index => $linkedDossier)
                                                                            <tr class="linked-dossier-item">
                                                                                <td>
                                                                                    <strong>{{ $linkedDossier['numero_dossier'] ?? 'Dossier' }}</strong>
                                                                                    <input type="hidden" name="linked_dossiers[{{ $index }}][dossier_id]" 
                                                                                           value="{{ $linkedDossier['dossier_id'] }}">
                                                                                    <input type="hidden" name="linked_dossiers[{{ $index }}][numero_dossier]" 
                                                                                           value="{{ $linkedDossier['numero_dossier'] }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control" 
                                                                                           name="linked_dossiers[{{ $index }}][relation]" 
                                                                                           value="{{ $linkedDossier['relation'] ?? '' }}"
                                                                                           placeholder=""
                                                                                           required>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <button type="button" class="btn btn-danger btn-sm remove-linked-dossier">
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
                                                            <div id="no-linked-dossiers" class="text-center py-4" 
                                                                 style="{{ old('linked_dossiers') ? 'display: none;' : '' }}">
                                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                                <p class="text-muted">Aucun dossier lié pour le moment</p>
                                                            </div>
                                                        </div>
                                                    </div>




                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal pour lier un intervenant -->
<div class="modal fade" id="linkDossierModal" tabindex="-1" role="dialog" aria-labelledby="linkDossierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="linkDossierModalLabel">
                    <i class="fas fa-users"></i> Sélectionner un dossier à lier
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Filtre de recherche -->
                <div class="form-group d-none">
                    <label for="dossierFilter">Filtrer les dossiers</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="dossierFilter" 
                               placeholder="Tapez pour filtrer par nom, email ou catégorie...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="clearFilter">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <small class="form-text text-muted">
                        Tapez pour filtrer la liste des intervenants. {{ $dossiers->count() }} intervenant(s) disponible(s).
                    </small>
                </div>

                <!-- Liste des intervenants disponibles -->
                <div class="form-group w-100" style="display:grid;">
                    <label for="dossierList">Choisir un dossier</label>
                    <select class="form-control search_test1" id="dossierList" >
                        <option value="">-- Sélectionnez un dossier --</option>
                        @foreach($dossiers as $dossier)
                            <option value="{{ $dossier->id }}" 
                                    data-name="{{ $dossier->numero_dossier }}"
                                    data-email="{{ $dossier->nom_dossier ?? 'N/A' }}"
                                    data-phone="{{ $dossier->objet ?? 'N/A' }}"
                                    data-category="{{ $dossier->date_entree ?? 'N/A' }}"
                                    class="dossier-option">
                                {{ $dossier->numero_dossier }} 
                            </option>
                        @endforeach
                    </select>
                    <div id="noResults" class="alert alert-warning mt-2" style="display: none;">
                        <i class="fas fa-search"></i> Aucun dossier ne correspond à votre recherche.
                    </div>
                </div>

                <!-- Aperçu du dossier sélectionné -->
                <div id="dossierPreview" class="card mt-3" style="display: none;">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-eye"></i> Aperçu du dossier</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="30%"><strong>Numéro Dossier :</strong></td>
                                        <td id="previewName"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nom Dossier :</strong></td>
                                        <td id="previewEmail"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Objet :</strong></td>
                                        <td id="previewPhone"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date Entrée :</strong></td>
                                        <td id="previewCategory"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4 text-right">
                                <button type="button" class="btn btn-success" id="confirmLinkDossier">
                                    <i class="fas fa-link"></i> Lier cet dossier
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message si aucun intervenant disponible -->
                @if($dossiers->isEmpty())
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Aucun dossier disponible pour le moment.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{asset('assets/custom/dossier-form.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.2/jquery.sumoselect.min.js"></script>
<script>
    $('.search_test1').SumoSelect({search: true, searchText: 'Sélectionner un dossier...'});
       
// Gestion des intervenants liés
let linkedDossiersCount = {{ old('linked_dossiers') ? count(old('linked_dossiers')) : 0 }};

// Filtrage des intervenants
$('#dossierFilter').on('input', function() {
    const filterText = $(this).val().toLowerCase();
    const options = $('.dossier-option');
    let visibleCount = 0;
    
    options.each(function() {
        const optionText = $(this).text().toLowerCase();
        if (optionText.includes(filterText)) {
            $(this).show();
            visibleCount++;
        } else {
            $(this).hide();
        }
    });
    
    // Afficher/masquer le message "aucun résultat"
    if (visibleCount === 0 && filterText !== '') {
        $('#noResults').show();
    } else {
        $('#noResults').hide();
    }
    
    // Réinitialiser la sélection si l'option sélectionnée est masquée
    const selectedOption = $('#dossierList option:selected');
    if (selectedOption.length > 0 && selectedOption.is(':hidden')) {
        $('#dossierList').val('');
        $('#dossierPreview').hide();
    }
});

// Effacer le filtre
$('#clearFilter').click(function() {
    $('#dossierFilter').val('');
    $('.dossier-option').show();
    $('#noResults').hide();
});

// Sélection d'un intervenant dans la liste
$('#dossierList').change(function() {
    const selectedOption = $(this).find('option:selected');
    const intervenantId = selectedOption.val();
    
    if (!intervenantId) {
        $('#dossierPreview').hide();
        return;
    }

    // Afficher l'aperçu
    $('#previewName').text(selectedOption.data('name'));
    $('#previewEmail').text(selectedOption.data('email'));
    $('#previewPhone').text(selectedOption.data('phone'));
    $('#previewCategory').text(selectedOption.data('category'));
    
    $('#dossierPreview').show();
});

// Confirmation du lien
$('#confirmLinkDossier').click(function() {
    const selectedOption = $('#dossierList option:selected');
    const intervenantId = selectedOption.val();
    const intervenantName = selectedOption.data('name');

    if (!intervenantId) {
        alert('Veuillez sélectionner un dossier.');
        return;
    }

    // Vérifier si l'intervenant n'est pas déjà lié
    const existingLink = $(`input[value="${intervenantId}"]`).closest('.linked-dossier-item');
    if (existingLink.length > 0) {
        alert('Cet dossier est déjà lié.');
        return;
    }

    addlinkedDossier(intervenantId, intervenantName);
    
    // Reset la modal
    $('#dossierList').val('');
    $('#dossierFilter').val('');
    $('.dossier-option').show();
    $('#noResults').hide();
    $('#dossierPreview').hide();
    $('#linkDossierModal').modal('hide');
});

function addlinkedDossier(dossierId, dossierNumero) {
    const newIndex = linkedDossiersCount++;
    
    const linkedItem = `
        <tr class="linked-dossier-item">
            <td>
                <strong>${dossierNumero}</strong>
                <input type="hidden" name="linked_dossiers[${newIndex}][dossier_id]" value="${dossierId}">
                <input type="hidden" name="linked_dossiers[${newIndex}][numero_dossier]" value="${dossierNumero}">
            </td>
            <td>
                <input type="text" class="form-control" 
                       name="linked_dossiers[${newIndex}][relation]" 
                       placeholder=""
                       required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-linked-dossier">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

    $('#linked-dossier-container').append(linkedItem);
    $('#no-linked-dossiers').hide();

    // Ajouter l'événement de suppression
    $('.remove-linked-dossier').off('click').on('click', function() {
        $(this).closest('.linked-dossier-item').remove();
        linkedDossiersCount--;
        
        // Réindexer les éléments restants
        reindexlinkedDossiers();
        
        // Afficher le message si plus d'intervenants liés
        if ($('#linked-dossier-container').children().length === 0) {
            $('#no-linked-dossiers').show();
        }
    });
}

function reindexlinkedDossiers() {
    $('#linked-dossier-container .linked-dossier-item').each(function(index) {
        $(this).find('input').each(function() {
            const name = $(this).attr('name').replace(/\[\d+\]/, `[${index}]`);
            $(this).attr('name', name);
        });
    });
}

// Initialiser les boutons de suppression pour les intervenants existants
$(document).ready(function() {
    $('.remove-linked-dossier').click(function() {
        $(this).closest('.linked-dossier-item').remove();
        linkedDossiersCount--;
        
        reindexlinkedDossiers();
        
        if ($('#linked-dossier-container').children().length === 0) {
            $('#no-linked-dossiers').show();
        }
    });

    // Reset de la modal quand elle se ferme
    $('#linkDossierModal').on('hidden.bs.modal', function() {
        $('#dossierList').val('');
        $('#dossierFilter').val('');
        $('.dossier-option').show();
        $('#noResults').hide();
        $('#dossierPreview').hide();
    });

    // Focus sur le champ de filtre quand la modal s'ouvre
    $('#linkDossierModal').on('shown.bs.modal', function() {
        $('#dossierFilter').focus();
    });
});
    </script>



