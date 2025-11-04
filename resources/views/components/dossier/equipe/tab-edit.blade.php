<div class="tab-pane fade" id="equipe" role="tabpanel" aria-labelledby="equipe-tab">
                                                <div class="p-3">
                                                    <h5 class="text-primary mb-3"><i class="fas fa-users-cog"></i> Attribution de l'équipe</h5>
                                                    
                                                    <!-- Avocat responsable -->
                                                    <div class="row">
                                                        <div class="col-md-12 d-none">
                                                            <div class="form-group">
                                                                <label for="avocat_id">Avocat responsable</label>
                                                                <select class="form-control @error('avocat_id') is-invalid @enderror" 
                                                                        id="avocat_id" name="avocat_id">
                                                                    <option value="">Sélectionnez l'avocat responsable</option>
                                                                    @foreach($users as $user)
                                                                        @if($user->hasRole('avocat') || $user->hasRole('admin'))
                                                                            @php
                                                                                $isAvocat = $dossier->users()
                                                                                    ->where('users.id', $user->id)
                                                                                    ->wherePivot('role', 'avocat')
                                                                                    ->exists();
                                                                            @endphp
                                                                            <option value="{{ $user->id }}" {{ old('avocat_id', $isAvocat ? $user->id : '') == $user->id ? 'selected' : '' }}>
                                                                                {{ $user->name }} ({{ $user->fonction }})
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                                @error('avocat_id')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 d-none">
                                                            <div class="form-group">
                                                                <label for="ordre">Ordre de priorité</label>
                                                                <select class="form-control @error('ordre') is-invalid @enderror" 
                                                                        id="ordre" name="ordre">
                                                                    <option value="1" {{ old('ordre', $dossier->users()->wherePivot('role', 'avocat')->first()?->pivot->ordre ?? 1) == 1 ? 'selected' : '' }}>1 - Priorité haute</option>
                                                                    <option value="2" {{ old('ordre', $dossier->users()->wherePivot('role', 'avocat')->first()?->pivot->ordre ?? 1) == 2 ? 'selected' : '' }}>2 - Priorité moyenne</option>
                                                                    <option value="3" {{ old('ordre', $dossier->users()->wherePivot('role', 'avocat')->first()?->pivot->ordre ?? 1) == 3 ? 'selected' : '' }}>3 - Priorité basse</option>
                                                                </select>
                                                                @error('ordre')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>

                                                   <div class="p-3">
                                                    <div class="d-flex justify-content-end align-items-center mb-3">
                                                        <h5 class="text-primary mb-0 d-none"><i class="fas fa-users"></i> Utilisateurs Liés</h5>
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#linkUtilisateurModal">
                                                            <i class="fas fa-link"></i> Lier un utilisateur
                                                        </button>
                                                    </div>

                                                    <!-- Tableau des utilisateurs liés -->
                                                    <div class="card">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0"><i class="fas fa-table"></i> Liste des utilisateurs liés</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-striped" id="linkedUtilisateursTable">
                                                                    <thead class="thead-dark">
                                                                        <tr>
                                                                            <th width="50%">Utilisateur Lié</th>
                                                                            <th width="40%">Role</th>
                                                                            <th width="10%">Actions</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="linked-utilisateurs-container">
                                                                        @php
    $existingLinkedUtilisateurs = [];
    if ($dossier->users->count() > 0) {
        foreach ($dossier->users as $index => $linkedUtilisateur) {
            $pivot = $linkedUtilisateur->pivot;
            
            $existingLinkedUtilisateurs[] = [
                'user_id' => $linkedUtilisateur->id,
                'user_name' => $linkedUtilisateur->name,
                'role' => $pivot->role
            ];
        }
    }
@endphp

                                                                        @if(old('linked_utilisateurs'))
                                                                            <!-- Afficher les utilisateurs depuis la validation -->
                                                                            @foreach(old('linked_utilisateurs') as $index => $linkedUtilisateur)
                                                                            <tr class="linked-utilisateur-item">
                                                                                <td>
                                                                                    <strong>{{ $linkedUtilisateur['name'] ?? 'Utilisateur' }}</strong>
                                                                                    <input type="hidden" name="linked_utilisateurs[{{ $index }}][user_id]" 
                                                                                           value="{{ $linkedUtilisateur['user_id'] }}">
                                                                                    <input type="hidden" name="linked_utilisateurs[{{ $index }}][user_name]" 
                                                                                           value="{{ $linkedUtilisateur['user_name'] }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control" 
                                                                                           name="linked_utilisateurs[{{ $index }}][role]" 
                                                                                           value="{{ $linkedUtilisateur['role'] ?? '' }}"
                                                                                           placeholder="Ex: Avocat, Secrétaire..."
                                                                                           required>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <button type="button" class="btn btn-danger btn-sm remove-linked-utilisateur">
                                                                                        <i class="fas fa-trash"></i>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        @elseif(count($existingLinkedUtilisateurs) > 0)
                                                                            <!-- Afficher les utilisateurs existants depuis la base de données -->
                                                                            @foreach($existingLinkedUtilisateurs as $index => $linkedUtilisateur)
                                                                            <tr class="linked-utilisateur-item">
                                                                                <td>
                                                                                    <strong>{{ $linkedUtilisateur['user_name'] }}</strong>
                                                                                    <input type="hidden" name="linked_utilisateurs[{{ $index }}][user_id]" 
                                                                                           value="{{ $linkedUtilisateur['user_id'] }}">
                                                                                    <input type="hidden" name="linked_utilisateurs[{{ $index }}][user_name]" 
                                                                                           value="{{ $linkedUtilisateur['user_name'] }}">
                                                                                </td>
                                                                                <td>
                                                                                    <input type="text" class="form-control" 
                                                                                           name="linked_utilisateurs[{{ $index }}][role]" 
                                                                                           value="{{ $linkedUtilisateur['role'] }}"
                                                                                           placeholder="Ex: Admin, Avocat..."
                                                                                           required>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    <button type="button" class="btn btn-danger btn-sm remove-linked-utilisateur">
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
                                                            <div id="no-linked-utilisateurs" class="text-center py-4" 
                                                                 style="{{ (old('linked_utilisateurs') || count($existingLinkedUtilisateurs) > 0) ? 'display: none;' : '' }}">
                                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                                <p class="text-muted">Aucun utilisateur lié pour le moment</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>


                                            
<!-- Modal pour lier un intervenant -->
<div class="modal fade" id="linkUtilisateurModal" tabindex="-1" role="dialog" aria-labelledby="linkUtilisateurModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="linkUtilisateurModalLabel">
                    <i class="fas fa-users"></i> Sélectionner un utilisateur à lier
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Filtre de recherche -->
                <div class="form-group d-none">
                    <label for="utilisateurFilter">Filtrer les utilisateurs</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="utilisateurFilter" 
                               placeholder="Tapez pour filtrer par nom, email ...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="clearFilter">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <small class="form-text text-muted">
                        Tapez pour filtrer la liste des utilisateurs. {{ $users->count() }} utilisateur(s) disponible(s).
                    </small>
                </div>

                <!-- Liste des utilisateurs disponibles -->
                <div class="form-group w-100" style="display:grid;">
                    <label for="utilisateurList">Choisir un utilisateur</label>
                    <select class="form-control search_test1" id="utilisateurList">
                        <option value="">-- Sélectionnez un utilisateur --</option>
                        @foreach($users as $utilisateurItem)
                            <option value="{{ $utilisateurItem->id }}" 
                                    data-name="{{ $utilisateurItem->name }}"
                                    data-email="{{ $utilisateurItem->email ?? 'N/A' }}"
                                    data-category="{{$utilisateurItem->fonction ?? 'N/A' }}"
                                    class="utilisateur-option">
                                {{ $utilisateurItem->name }} 
                            </option>
                        @endforeach
                    </select>
                    <div id="noResults" class="alert alert-warning mt-2" style="display: none;">
                        <i class="fas fa-search"></i> Aucun utilisateur ne correspond à votre recherche.
                    </div>
                </div>

                <!-- Aperçu de l'intervenant sélectionné -->
                <div id="utilisateurPreview" class="card mt-3" style="display: none;">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-eye"></i> Aperçu de l'utilisateur</h6>
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
                                </table>
                            </div>
                            <div class="col-md-4 text-right">
                                <button type="button" class="btn btn-success" id="confirmLinkUtilisateur">
                                    <i class="fas fa-link"></i> Lier cet utilisateur
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message si aucun intervenant disponible -->
                @if($users->count() <= 1)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Aucun autre utilisateur disponible pour le moment.
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
<!-- Bootstrap 4 -->
<!-- <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> -->
<!-- jquery-validation -->
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sumoselect/3.0.2/jquery.sumoselect.min.js"></script>
<script>
    $('.search_test1').SumoSelect({search: true, searchText: 'Sélectionner un utilisateur...'});
// Fonction pour effacer l'input file
function clearFileInput() {
    $('#piece_jointe').val('');
    $('#piece_jointe_label').text('Choisir des fichiers (PDF, images, Word, Excel) - Max 10MB par fichier');
    $('#files_preview').hide();
    $('#files_list').empty();
}


// Gestion des utilisateurs liés
let linkedUtilisateursCount = {{ max(
    old('linked_utilisateurs') ? count(old('linked_utilisateurs')) : 0,
    count($existingLinkedUtilisateurs)
) }};

// Filtrage des utilisateurs 
$('#utilisateurFilter').on('input', function() {
    const filterText = $(this).val().toLowerCase();
    const options = $('.utilisateur-option');
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
    const selectedOption = $('#utilisateurList option:selected');
    if (selectedOption.length > 0 && selectedOption.is(':hidden')) {
        $('#utilisateurList').val('');
        $('#utilisateurPreview').hide();
    }
});

// Effacer le filtre
$('#clearFilter').click(function() {
    $('#utilisateurFilter').val('');
    $('.utilisateur-option').show();
    $('#noResults').hide();
});

// Sélection d'un intervenant dans la liste
$('#utilisateurList').change(function() {
    const selectedOption = $(this).find('option:selected');
    const userId = selectedOption.val();
    
    if (!userId) {
        $('#utilisateurPreview').hide();
        return;
    }

    // Afficher l'aperçu
    $('#previewName').text(selectedOption.data('name'));
    $('#previewEmail').text(selectedOption.data('email'));
    $('#previewPhone').text(selectedOption.data('phone'));
    $('#previewCategory').text(selectedOption.data('category'));
    
    $('#utilisateurPreview').show();
});

// Confirmation du lien
$('#confirmLinkUtilisateur').click(function() {
    const selectedOption = $('#utilisateurList option:selected');
    const userId = selectedOption.val();
    const userName = selectedOption.data('name');
    const userCategory = selectedOption.data('category');

    if (!userId) {
        alert('Veuillez sélectionner un utilisateur.');
        return;
    }

    // Vérifier si l'intervenant n'est pas déjà lié
    const existingLink = $(`input[value="${userId}"]`).closest('.linked-utilisateur-item');
    if (existingLink.length > 0) {
        alert('Cet utilisateur est déjà lié.');
        return;
    }

    addlinkedUtilisateur(userId, userName,userCategory);
    
    // Reset la modal
    $('#utilisateurList').val('');
    $('#utilisateurFilter').val('');
    $('.utilisateur-option').show();
    $('#noResults').hide();
    $('#utilisateurPreview').hide();
    $('#linkUtilisateurModal').modal('hide');
});

function addlinkedUtilisateur(userId, userName, userCategory) {
    const newIndex = linkedUtilisateursCount++;
    
    const linkedItem = `
        <tr class="linked-utilisateur-item">
            <td>
                <strong>${userName}</strong>
                <input type="hidden" name="linked_utilisateurs[${newIndex}][user_id]" value="${userId}">
                <input type="hidden" name="linked_utilisateurs[${newIndex}][user_name]" value="${userName}">
            </td>
            <td>
                <input type="text" class="form-control" 
                       name="linked_utilisateurs[${newIndex}][role]" 
                       value="${userCategory}"
                       required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-linked-utilisateur">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

    $('#linked-utilisateurs-container').append(linkedItem);
    $('#no-linked-utilisateurs').hide();

    // Ajouter l'événement de suppression
    $('.remove-linked-utilisateur').off('click').on('click', function() {
        $(this).closest('.linked-utilisateur-item').remove();
        linkedUtilisateursCount--;
        
        // Réindexer les éléments restants
        reindexlinkedUtilisateurs();
        
        // Afficher le message si plus d'intervenants liés
        if ($('#linked-utilisateurs-container').children().length === 0) {
            $('#no-linked-utilisateurs').show();
        }
    });
}

function reindexlinkedUtilisateurs() {
    $('#linked-utilisateurs-container .linked-utilisateur-item').each(function(index) {
        $(this).find('input').each(function() {
            const name = $(this).attr('name').replace(/\[\d+\]/, `[${index}]`);
            $(this).attr('name', name);
        });
    });
}

// Initialiser les boutons de suppression pour les utilisateurs existants
$(document).ready(function() {
    $('.remove-linked-utilisateur').click(function() {
        $(this).closest('.linked-utilisateur-item').remove();
        linkedUtilisateursCount--;
        
        reindexlinkedUtilisateurs();
        
        if ($('#linked-utilisateurs-container').children().length === 0) {
            $('#no-linked-utilisateurs').show();
        }
    });

    // Reset de la modal quand elle se ferme
    $('#linkUtilisateurModal').on('hidden.bs.modal', function() {
        $('#utilisateurList').val('');
        $('#utilisateurFilter').val('');
        $('.utilisateur-option').show();
        $('#noResults').hide();
        $('#utilisateurPreview').hide();
    });

    // Focus sur le champ de filtre quand la modal s'ouvre
    $('#linkUtilisateurModal').on('shown.bs.modal', function() {
        $('#utilisateurFilter').focus();
    });

});
</script>
