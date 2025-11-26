<!-- Modal de création -->
<div class="modal fade" id="createTimesheetModal" tabindex="-1" role="dialog" aria-labelledby="createTimesheetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="createTimesheetModalLabel">
                    <i class="fas fa-plus"></i> Nouvelle Feuille de Temps
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('time-sheets.store') }}" method="POST" id="timesheetForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Date -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date_timesheet">Date *</label>
                                <input type="date" class="form-control @error('date_timesheet') is-invalid @enderror" 
                                       id="date_timesheet" name="date_timesheet" 
                                       value="{{ old('date_timesheet', date('Y-m-d')) }}" required>
                                @error('date_timesheet')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Utilisateur -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="utilisateur_id">Utilisateur *</label>
                                <select class="form-control @error('utilisateur_id') is-invalid @enderror" 
                                        id="utilisateur_id" name="utilisateur_id" required>
                                    <option value="">Sélectionnez un utilisateur</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('utilisateur_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->fonction }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('utilisateur_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Dossier -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="dossier_id">Dossier</label>
                                <select class="form-control @error('dossier_id') is-invalid @enderror" 
                                        id="dossier_id" name="dossier_id">
                                    <option value="">Sélectionnez un dossier</option>
                                    @foreach($dossiers as $dossier)
                                        <option value="{{ $dossier->id }}" {{ old('dossier_id') == $dossier->id ? 'selected' : '' }}>
                                            {{ $dossier->numero_dossier }} - {{ $dossier->nom_dossier ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dossier_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Catégorie -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categorie">Catégorie</label>
                                <select class="form-control @error('categorie') is-invalid @enderror" 
                                        id="categorie" name="categorie">
                                    <option value="">Chargement des catégories...</option>
                                </select>
                                @error('categorie')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Type -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select class="form-control @error('type') is-invalid @enderror" 
                                        id="type" name="type" disabled>
                                    <option value="">Sélectionnez d'abord une catégorie</option>
                                </select>
                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Quantité -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="quantite">Quantité *</label>
                                <input type="number" class="form-control @error('quantite') is-invalid @enderror" 
                                       id="quantite" name="quantite" value="{{ old('quantite', 1) }}" 
                                       min="0" step="0.01" placeholder="0.00" required>
                                @error('quantite')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Prix -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="prix">Prix (DT) *</label>
                                <input type="number" class="form-control @error('prix') is-invalid @enderror" 
                                       id="prix" name="prix" value="{{ old('prix', 0) }}" 
                                       min="0" step="0.01" placeholder="0.00" required>
                                @error('prix')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Total (calculé automatiquement) -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_calcule">Total calculé</label>
                                <input type="text" class="form-control" id="total_calcule" 
                                       value="0.00 DT" readonly style="background-color: #f8f9fa; font-weight: bold;">
                                <small class="form-text text-muted">
                                    Calcul automatique : Quantité × Prix
                                </small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="piece_jointe">Pièce Jointe</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('file') is-invalid @enderror" 
                                           id="piece_jointe" name="file" accept=".pdf,.doc,.docx,.jpg,.png">
                                    <label class="custom-file-label" for="piece_jointe" id="piece_jointe_label">
                                        Choisir un fichier (PDF, Word, images) - Max 2MB
                                    </label>
                                    @error('file')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">
                                    Formats acceptés: PDF, DOC, DOCX, JPG, PNG - Taille max: 2MB
                                </small>
                                
                                <!-- Aperçu du fichier -->
                                <div id="file_preview" class="mt-2" style="display: none;">
                                    <div class="alert alert-info">
                                        <i class="fas fa-file"></i>
                                        <span id="file_name"></span>
                                        <button type="button" class="close" onclick="clearFileInput()">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Décrivez l'activité réalisée..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Créer la feuille de temps
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Div pour les messages AJAX -->
<div id="ajaxMessage" style="display: none;"></div>
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
// Fonction pour effacer l'input file
function clearFileInput() {
    $('#piece_jointe').val('');
    $('#piece_jointe_label').text('Choisir un fichier (PDF, Word, images) - Max 2MB');
    $('#file_preview').hide();
}

// Fonction pour afficher les messages
function showMessage(message, type = 'success') {
    const messageDiv = $('#ajaxMessage');
    messageDiv.removeClass('alert-success alert-danger alert-warning')
              .addClass(`alert-${type === 'error' ? 'danger' : type}`)
              .html(message)
              .show()
              .delay(5000)
              .fadeOut();
}

// Fonction pour réinitialiser le formulaire
function resetForm() {
    $('#timesheetForm')[0].reset();
    $('#total_calcule').val('0.00 DT');
    clearFileInput();
    
    // Réinitialiser les sélecteurs de catégorie et type
    $('#categorie').html('<option value="">Chargement des catégories...</option>');
    $('#type').html('<option value="">Sélectionnez d\'abord une catégorie</option>').prop('disabled', true);
    
    // Recharger les catégories
    loadCategories();
    
    // Réinitialiser la validation
    $('#timesheetForm').validate().resetForm();
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
}

$(document).ready(function() {
    // Auto-sélection de l'utilisateur connecté si c'est un avocat/secrétaire
    @if(auth()->user()->fonction === 'avocat' || auth()->user()->fonction === 'secrétaire')
        $('#utilisateur_id').val('{{ auth()->id() }}').trigger('change');
    @endif

    // Calcul automatique du total
    function calculateTotal() {
        const quantite = parseFloat($('#quantite').val()) || 0;
        const prix = parseFloat($('#prix').val()) || 0;
        const total = quantite * prix;
        
        $('#total_calcule').val(total.toFixed(2) + ' DT');
    }

    // Écouter les changements sur quantité et prix
    $('#quantite, #prix').on('input', function() {
        calculateTotal();
    });

    // Calcul initial
    calculateTotal();

    // Gestion de l'input file
    $('#piece_jointe').on('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var fileName = file.name;
            var fileSize = (file.size / 1024 / 1024).toFixed(2); // Taille en MB
            
            // Mettre à jour le label
            $('#piece_jointe_label').text(fileName);
            
            // Afficher l'aperçu
            $('#file_name').text(fileName + ' (' + fileSize + ' MB)');
            $('#file_preview').show();
            
            // Vérifier la taille du fichier
            if (file.size > 2 * 1024 * 1024) { // 2MB en bytes
                showMessage('Le fichier est trop volumineux. Taille maximum: 2MB', 'error');
                clearFileInput();
            }
        }
    });

    // Validation pour le fichier
    $.validator.addMethod('fileSize', function(value, element, param) {
        return this.optional(element) || (element.files[0].size <= param);
    }, 'La taille du fichier doit être inférieure à {0}');

    $.validator.addMethod('fileType', function(value, element, param) {
        return this.optional(element) || (element.files[0].type.match(param) || element.files[0].name.match(param));
    }, 'Type de fichier non supporté');

    // Dynamic category-type functionality
    const categorieSelect = document.getElementById('categorie');
    const typeSelect = document.getElementById('type');

    // Load categories from the server
    function loadCategories() {
        fetch('/get/categories')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors du chargement des catégories');
                }
                return response.json();
            })
            .then(data => {
                categorieSelect.innerHTML = '<option value="">Sélectionnez une catégorie</option>';
                data.forEach(categorie => {
                    const option = document.createElement('option');
                    option.value = categorie.id;
                    option.textContent = categorie.nom;
                    categorieSelect.appendChild(option);
                });
                
                // Set old value if exists
                @if(old('categorie'))
                    categorieSelect.value = '{{ old('categorie') }}';
                    if (categorieSelect.value) {
                        loadTypes(categorieSelect.value);
                    }
                @endif
            })
            .catch(error => {
                console.error('Erreur:', error);
                categorieSelect.innerHTML = '<option value="">Erreur de chargement</option>';
            });
    }

    // Load types based on selected category
    function loadTypes(categorieId) {
        if (!categorieId) {
            typeSelect.innerHTML = '<option value="">Sélectionnez d\'abord une catégorie</option>';
            typeSelect.disabled = true;
            return;
        }

        typeSelect.disabled = true;
        typeSelect.innerHTML = '<option value="">Chargement des types...</option>';

        fetch(`/get/types?categorie_id=${categorieId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors du chargement des types');
                }
                return response.json();
            })
            .then(data => {
                typeSelect.innerHTML = '<option value="">Sélectionnez un type</option>';
                data.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type.id;
                    option.textContent = type.nom;
                    typeSelect.appendChild(option);
                });
                typeSelect.disabled = false;
                
                // Set old value if exists
                @if(old('type'))
                    typeSelect.value = '{{ old('type') }}';
                @endif
            })
            .catch(error => {
                console.error('Erreur:', error);
                typeSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                typeSelect.disabled = false;
            });
    }

    // Event listener for category change
    categorieSelect.addEventListener('change', function() {
        loadTypes(this.value);
    });

    // Initialize by loading categories when modal is shown
    $('#createTimesheetModal').on('show.bs.modal', function() {
        loadCategories();
    });

    // Reset form when modal is hidden
    $('#createTimesheetModal').on('hidden.bs.modal', function() {
        resetForm();
    });

    // Validation côté client
    $('#timesheetForm').validate({
        rules: {
            date_timesheet: {
                required: true
            },
            utilisateur_id: {
                required: true
            },
            description: {
                required: true,
                minlength: 10
            },
            quantite: {
                required: true,
                min: 0
            },
            prix: {
                required: true,
                min: 0
            },
            file: {
                fileSize: 2 * 1024 * 1024, // 2MB
                fileType: /\.(pdf|doc|docx|jpg|png)$/i
            }
        },
        messages: {
            date_timesheet: {
                required: "La date est obligatoire"
            },
            utilisateur_id: {
                required: "L'utilisateur est obligatoire"
            },
            description: {
                required: "La description est obligatoire",
                minlength: "La description doit contenir au moins 10 caractères"
            },
            quantite: {
                required: "La quantité est obligatoire",
                min: "La quantité doit être positive"
            },
            prix: {
                required: "Le prix est obligatoire",
                min: "Le prix doit être positif"
            },
            file: {
                fileSize: "Le fichier ne doit pas dépasser 2MB",
                fileType: "Formats acceptés: PDF, DOC, DOCX, JPG, PNG"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            // Vérification finale du calcul du total
            const quantite = parseFloat($('#quantite').val()) || 0;
            const prix = parseFloat($('#prix').val()) || 0;
            const total = quantite * prix;
            
            if (total <= 0) {
                showMessage('Erreur : Le total calculé doit être supérieur à 0. Vérifiez la quantité et le prix.', 'error');
                return false;
            }
            
            // Soumission AJAX
            submitFormAjax(form);
            return false; // Empêcher la soumission normale
        }
    });

    // Fonction de soumission AJAX
    function submitFormAjax(form) {
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        
        // Désactiver le bouton et afficher le loader
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Création...');
        
        // Créer FormData pour gérer les fichiers
        const formData = new FormData(form);
        
        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showMessage(response.message, 'success');
                    
                    // Fermer le modal
                    $('#createTimesheetModal').modal('hide');
                    
                    // Recharger la page ou actualiser les données après un délai
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                    
                } else {
                    showMessage(response.message, 'error');
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Une erreur est survenue lors de la création.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 422) {
                    // Gestion des erreurs de validation
                    const errors = xhr.responseJSON.errors;
                    errorMessage = 'Veuillez corriger les erreurs suivantes:<br>';
                    for (const field in errors) {
                        errorMessage += `- ${errors[field][0]}<br>`;
                    }
                } else if (xhr.status === 403) {
                    errorMessage = 'Vous n\'avez pas la permission de créer des feuilles de temps.';
                }
                
                showMessage(errorMessage, 'error');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    }

    // Formater les montants à la sortie des champs
    $('#quantite, #prix').on('blur', function() {
        var value = parseFloat($(this).val()) || 0;
        $(this).val(value.toFixed(2));
        calculateTotal();
    });
});
</script>

<style>
.select2-container .select2-selection--single {
    height: 38px;
}
.select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
}
.select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow {
    height: 36px;
}
#total_calcule {
    color: #28a745;
    font-size: 1.1em;
}
.custom-file-label::after {
    content: "Parcourir";
}

#file_preview .alert {
    padding: 8px 15px;
    margin-bottom: 0;
}

#file_preview .close {
    float: right;
    font-size: 1.2rem;
    font-weight: bold;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    opacity: .5;
    background: transparent;
    border: 0;
}

#file_preview .close:hover {
    opacity: .75;
}

/* Style pour les messages AJAX */
#ajaxMessage {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
}

/* Style pour le modal */
.modal-header.bg-primary {
    background-color: #007bff !important;
    color: white;
}

.modal-lg {
    max-width: 800px;
}

/* Ajustement pour les petits écrans */
@media (max-width: 768px) {
    .modal-lg {
        max-width: 95%;
    }
}
</style>