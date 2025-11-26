<!-- Modal de création -->
<div class="modal fade" id="createFactureModal" tabindex="-1" role="dialog" aria-labelledby="createFactureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="createFactureModalLabel">
                    <i class="fas fa-plus"></i> Nouvelle Facture
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('factures.store') }}" method="POST" id="factureForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Type de pièce -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type_piece">Type de pièce *</label>
                                <select class="form-control @error('type_piece') is-invalid @enderror" 
                                        id="type_piece" name="type_piece" required>
                                    <option value="">Sélectionnez un type</option>
                                    <option value="facture" {{ old('type_piece') == 'facture' ? 'selected' : '' }}>Facture</option>
                                    <option value="note_frais" {{ old('type_piece') == 'note_frais' ? 'selected' : '' }}>Note de frais</option>
                                    <option value="note_provision" {{ old('type_piece') == 'note_provision' ? 'selected' : '' }}>Note de provision</option>
                                    <option value="avoir" {{ old('type_piece') == 'avoir' ? 'selected' : '' }}>Avoir</option>
                                </select>
                                @error('type_piece')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Numéro -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="numero">Numéro *</label>
                                <input type="text" class="form-control @error('numero') is-invalid @enderror" 
                                       id="numero" name="numero" value="{{ old('numero', $nextNumber) }}" 
                                       placeholder="Numéro de la facture" required>
                                @error('numero')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Date d'émission -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_emission">Date d'émission *</label>
                                <input type="date" class="form-control @error('date_emission') is-invalid @enderror" 
                                       id="date_emission" name="date_emission" 
                                       value="{{ old('date_emission', date('Y-m-d')) }}" required>
                                @error('date_emission')
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
                                        <option value="{{ $dossier->id }}" 
                                            {{ old('dossier_id') == $dossier->id ? 'selected' : '' }}
                                            data-client-id="{{ $dossier->intervenants->first()->id ?? '' }}">
                                            {{ $dossier->numero_dossier }}
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

                        <!-- Client -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="client_id">Client</label>
                                <select class="form-control @error('client_id') is-invalid @enderror" 
                                        id="client_id" name="client_id">
                                    <option value="">Sélectionnez un client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->identite_fr ?? $client->name }}
                                        </option>
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

                    <!-- Montants -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="montant_ht">Montant HT (DT) *</label>
                                <input type="number" class="form-control @error('montant_ht') is-invalid @enderror" 
                                       id="montant_ht" name="montant_ht" value="{{ old('montant_ht', 0) }}" 
                                       min="0" step="0.01" placeholder="0.00" required>
                                @error('montant_ht')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="montant_tva">Montant TVA (DT) *</label>
                                <input type="number" class="form-control @error('montant_tva') is-invalid @enderror" 
                                       id="montant_tva" name="montant_tva" value="{{ old('montant_tva', 0) }}" 
                                       min="0" step="0.01" placeholder="0.00" required>
                                @error('montant_tva')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="montant">Montant TTC (DT) *</label>
                                <input type="number" class="form-control @error('montant') is-invalid @enderror" 
                                       id="montant" name="montant" value="{{ old('montant', 0) }}" 
                                       min="0" step="0.01" placeholder="0.00" required readonly
                                       style="background-color: #f8f9fa; font-weight: bold;">
                                @error('montant')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">
                                    Calcul automatique : HT + TVA
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Vérification des montants -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info" id="montantAlert" style="display: none;">
                                <i class="fas fa-info-circle"></i>
                                <span id="montantAlertText"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Statut -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="statut">Statut *</label>
                                <select class="form-control @error('statut') is-invalid @enderror" 
                                        id="statut" name="statut" required>
                                    <option value="">Sélectionnez un statut</option>
                                    <option value="non_payé" {{ old('statut') == 'non_payé' ? 'selected' : '' }}>Non payé</option>
                                    <option value="payé" {{ old('statut') == 'payé' ? 'selected' : '' }}>Payé</option>
                                </select>
                                @error('statut')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Commentaires -->
                    <div class="form-group">
                        <label for="commentaires">Commentaires</label>
                        <textarea class="form-control @error('commentaires') is-invalid @enderror" 
                                  id="commentaires" name="commentaires" rows="3" 
                                  placeholder="Ajoutez des commentaires ou notes...">{{ old('commentaires') }}</textarea>
                        @error('commentaires')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Pièce jointe -->
                    <div class="form-group">
                        <label for="piece_jointe">Pièce jointe</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('piece_jointe') is-invalid @enderror" 
                                   id="piece_jointe" name="piece_jointe" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
                            <label class="custom-file-label" for="piece_jointe" id="piece_jointe_label">
                                Choisir un fichier (PDF, images, Word, Excel) - Max 10MB
                            </label>
                            @error('piece_jointe')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <small class="form-text text-muted">
                            Formats acceptés: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX - Taille max: 10MB
                        </small>
                        
                        <!-- Aperçu du fichier -->
                        <div id="file_preview" class="mt-2" style="display: none;">
                            <div class="alert alert-info" style="color:black;">
                                <i class="fas fa-file"></i>
                                <span id="file_name"></span>
                                <button type="button" class="close" onclick="clearFileInput()">
                                    <span>&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Créer la facture
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
    $('#piece_jointe_label').text('Choisir un fichier (PDF, images, Word, Excel) - Max 10MB');
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
    $('#factureForm')[0].reset();
    
    // Réinitialiser les valeurs spécifiques
    $('#montant_ht').val('0');
    $('#montant_tva').val('0');
    $('#montant').val('0');
    $('#montantAlert').hide();
    
    // Réinitialiser le numéro automatique
    @if(isset($nextNumber))
    $('#numero').val('{{ $nextNumber }}');
    @endif
    
    clearFileInput();
    
    // Réinitialiser la validation
    if ($('#factureForm').validate()) {
        $('#factureForm').validate().resetForm();
    }
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
}

$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

    // Auto-sélection du client basé sur le dossier
    $('#dossier_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        var clientId = selectedOption.data('client-id');
        
        if (clientId) {
            $('#client_id').val(clientId).trigger('change');
        }
    });

    // Gestion de l'input file
    $('#piece_jointe').on('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var fileName = file.name;
            var fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            $('#piece_jointe_label').text(fileName);
            $('#file_name').text(fileName + ' (' + fileSize + ' MB)');
            $('#file_preview').show();
            
            if (file.size > 10 * 1024 * 1024) {
                showMessage('Le fichier est trop volumineux. Taille maximum: 10MB', 'error');
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

    // Calcul automatique du montant TTC
    function calculateMontantTTC() {
        var montantHT = parseFloat($('#montant_ht').val()) || 0;
        var montantTVA = parseFloat($('#montant_tva').val()) || 0;
        var montantTTC = montantHT + montantTVA;
        
        $('#montant').val(montantTTC.toFixed(2));
        
        var tolerance = 0.01;
        var difference = Math.abs(montantTTC - parseFloat($('#montant').val()));
        
        if (difference > tolerance) {
            $('#montantAlert').show();
            $('#montantAlertText').text('Attention : Le montant TTC calculé (' + montantTTC.toFixed(2) + ' DT) est différent du montant saisi. Vérifiez les montants HT et TVA.');
        } else {
            $('#montantAlert').hide();
        }
    }

    // Écouter les changements sur HT et TVA
    $('#montant_ht, #montant_tva').on('input', function() {
        calculateMontantTTC();
    });

    // Calcul initial
    calculateMontantTTC();

    // Reset form when modal is shown
    $('#createFactureModal').on('show.bs.modal', function() {
        // S'assurer que les calculs sont à jour
        calculateMontantTTC();
    });

    // Reset form when modal is hidden
    $('#createFactureModal').on('hidden.bs.modal', function() {
        resetForm();
    });

    // Validation côté client
    $('#factureForm').validate({
        rules: {
            type_piece: {
                required: true
            },
            numero: {
                required: true,
                minlength: 3
            },
            date_emission: {
                required: true
            },
            montant_ht: {
                required: true,
                min: 0
            },
            montant_tva: {
                required: true,
                min: 0
            },
            montant: {
                required: true,
                min: 0
            },
            statut: {
                required: true
            },
            piece_jointe: {
                fileSize: 10 * 1024 * 1024,
                fileType: /\.(pdf|jpg|jpeg|png|doc|docx|xls|xlsx)$/i
            }
        },
        messages: {
            type_piece: {
                required: "Le type de pièce est obligatoire"
            },
            numero: {
                required: "Le numéro est obligatoire",
                minlength: "Le numéro doit contenir au moins 3 caractères"
            },
            date_emission: {
                required: "La date d'émission est obligatoire"
            },
            montant_ht: {
                required: "Le montant HT est obligatoire",
                min: "Le montant HT doit être positif"
            },
            montant_tva: {
                required: "Le montant TVA est obligatoire",
                min: "Le montant TVA doit être positif"
            },
            montant: {
                required: "Le montant TTC est obligatoire",
                min: "Le montant TTC doit être positif"
            },
            statut: {
                required: "Le statut est obligatoire"
            },
            piece_jointe: {
                fileSize: "Le fichier ne doit pas dépasser 10MB",
                fileType: "Formats acceptés: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX"
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
            // Vérification finale de la cohérence des montants
            var montantHT = parseFloat($('#montant_ht').val()) || 0;
            var montantTVA = parseFloat($('#montant_tva').val()) || 0;
            var montantTTC = parseFloat($('#montant').val()) || 0;
            var calculatedTTC = montantHT + montantTVA;
            
            if (Math.abs(calculatedTTC - montantTTC) > 0.01) {
                showMessage('Erreur : Le montant TTC doit être égal à HT + TVA.\nHT: ' + montantHT.toFixed(2) + ' DT\nTVA: ' + montantTVA.toFixed(2) + ' DT\nTTC calculé: ' + calculatedTTC.toFixed(2) + ' DT\nTTC saisi: ' + montantTTC.toFixed(2) + ' DT', 'error');
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
                    $('#createFactureModal').modal('hide');
                    
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
                    errorMessage = 'Vous n\'avez pas la permission de créer des factures.';
                }
                
                showMessage(errorMessage, 'error');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    }

    // Formater les montants à la sortie des champs
    $('#montant_ht, #montant_tva').on('blur', function() {
        var value = parseFloat($(this).val()) || 0;
        $(this).val(value.toFixed(2));
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
.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}
.alert-info {
    background-color: #e8f4fd;
    border-color: #b6e0fe;
}
#montant {
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

.modal-xl {
    max-width: 1140px;
}

/* Ajustement pour les petits écrans */
@media (max-width: 768px) {
    .modal-xl {
        max-width: 95%;
    }
    
    .modal-body .row > [class*="col-"] {
        margin-bottom: 1rem;
    }
}

/* Amélioration de l'apparence dans le modal */
.modal-body .form-group {
    margin-bottom: 1rem;
}

.modal-body .alert {
    margin-bottom: 1rem;
}
</style>