@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Modifier la Feuille de Temps</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('time-sheets.index') }}">Feuilles de Temps</a></li>
                        <li class="breadcrumb-item active">Modifier</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Modifier les informations de la feuille de temps</h3>
                        </div>
                        <!-- form start -->
                        <form action="{{ route('time-sheets.update', ['time_sheet' => $timesheet]) }}" method="POST" id="timesheetForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <!-- Date -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_timesheet">Date *</label>
                                            <input type="date" class="form-control @error('date_timesheet') is-invalid @enderror" 
                                                   id="date_timesheet" name="date_timesheet" 
                                                   value="{{ old('date_timesheet', $timesheet->date_timesheet ? $timesheet->date_timesheet->format('Y-m-d') : '') }}" required>
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
                                            <select class="form-control select2 @error('utilisateur_id') is-invalid @enderror" 
                                                    id="utilisateur_id" name="utilisateur_id" required>
                                                <option value="">Sélectionnez un utilisateur</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ old('utilisateur_id', $timesheet->utilisateur_id) == $user->id ? 'selected' : '' }}>
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
                                                    <option value="{{ $dossier->id }}" {{ old('dossier_id', $timesheet->dossier_id) == $dossier->id ? 'selected' : '' }}>
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
                                                   id="quantite" name="quantite" value="{{ old('quantite', $timesheet->quantite) }}" 
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
                                                   id="prix" name="prix" value="{{ old('prix', $timesheet->prix) }}" 
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
                                                   value="{{ number_format($timesheet->total, 2, ',', ' ') }} DT" readonly style="background-color: #f8f9fa; font-weight: bold;">
                                            <small class="form-text text-muted">
                                                Calcul automatique : Quantité × Prix
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="file">Pièce Jointe</label>
                                            <!-- Fichier existant -->
                                            @if($timesheet->file_path)
                                            <div class="alert alert-success mb-2">
                                                <i class="fas fa-file"></i>
                                                <a href="{{ Storage::disk('public')->url($timesheet->file_path) }}" target="_blank" class="ml-2">
                                                    {{ $timesheet->file_name }}
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger float-right" onclick="confirmDeleteFile()">
                                                    <i class="fas fa-trash"></i> Supprimer
                                                </button>
                                            </div>
                                            <input type="hidden" name="current_file" value="{{ $timesheet->file_path }}">
                                            @endif
                                            
                                            <!-- Nouveau fichier -->
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input @error('file') is-invalid @enderror" 
                                                       id="file" name="file" accept=".pdf,.doc,.docx,.jpg,.png">
                                                <label class="custom-file-label" for="file" id="file_label">
                                                    {{ $timesheet->file_path ? 'Remplacer le fichier' : 'Choisir un fichier' }} (PDF, Word, images) - Max 2MB
                                                </label>
                                                @error('file')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <small class="form-text text-muted">
                                                Formats acceptés: PDF, DOC, DOCX, JPG, PNG - Taille max: 2MB
                                                @if($timesheet->file_path)
                                                    <br>Laisser vide pour conserver le fichier actuel.
                                                @endif
                                            </small>
                                            
                                            <!-- Aperçu du nouveau fichier -->
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

                                <!-- Informations de suivi -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Informations de suivi</label>
                                            <div class="alert alert-info" style="color:black;">
                                                <small>
                                                    <strong>Créé le:</strong> {{ $timesheet->created_at->format('d/m/Y H:i') }}<br>
                                                    <strong>Modifié le:</strong> {{ $timesheet->updated_at->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label for="description">Description *</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="Décrivez l'activité réalisée..." required>{{ old('description', $timesheet->description) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="fas fa-save"></i> Mettre à jour
                                </button>
                                <a href="{{ url()->previous() }}" class="btn btn-default btn-lg">
                                    <i class="fas fa-arrow-left"></i> Retour à la liste
                                </a>

                                @can('delete_timesheets')
                                    <button type="button" class="btn btn-danger btn-lg float-right" 
                                            onclick="confirmDelete({{ $timesheet->id }})">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                @endcan
                            </div>
                        </form>

                        <!-- Formulaire de suppression -->
                        @can('delete_timesheets')
                            <form id="delete-form-{{ $timesheet->id }}" 
                                  action="{{ route('time-sheets.destroy', $timesheet) }}" 
                                  method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endcan
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

<!-- Modal de confirmation de suppression du fichier -->
<div class="modal fade" id="deleteFileModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation de suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer la pièce jointe ?</p>
                <p><strong>{{ $timesheet->file_name }}</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="btnConfirmDeleteFile">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<!-- Div pour les messages AJAX -->
<div id="ajaxMessage" style="display: none;"></div>
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script>
// Fonction pour effacer l'input file
function clearFileInput() {
    $('#file').val('');
    $('#file_label').text('{{ $timesheet->file_path ? "Remplacer le fichier" : "Choisir un fichier" }} (PDF, Word, images) - Max 2MB');
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

// Fonction pour mettre à jour l'affichage du fichier actuel
function updateCurrentFileDisplay() {
    $('.alert-success').hide();
}

$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });

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
    $('#file').on('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var fileName = file.name;
            var fileSize = (file.size / 1024 / 1024).toFixed(2); // Taille en MB
            
            // Mettre à jour le label
            $('#file_label').text(fileName);
            
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
    
    // Store the current timesheet values
    const currentCategorieId = '{{ old("categorie", $timesheet->categorie) }}';
    const currentTypeId = '{{ old("type", $timesheet->type) }}';

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
                
                // Set current category if exists
                if (currentCategorieId) {
                    categorieSelect.value = currentCategorieId;
                    if (categorieSelect.value) {
                        // Load types for the current category
                        loadTypes(categorieSelect.value, true);
                    }
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                categorieSelect.innerHTML = '<option value="">Erreur de chargement</option>';
            });
    }

    // Load types based on selected category
    function loadTypes(categorieId, setCurrentType = false) {
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
                
                // Set current type if exists and we're loading for the current category
                if (setCurrentType && currentTypeId) {
                    typeSelect.value = currentTypeId;
                }
                
                // Also set old value if form validation failed
                @if(old('type'))
                    if (!setCurrentType) {
                        typeSelect.value = '{{ old('type') }}';
                    }
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

    // Initialize by loading categories
    loadCategories();

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
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mise à jour...');
        
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
                    
                    // Redirection après un délai
                    setTimeout(function() {
                        window.location.href = response.redirect_url;
                    }, 1500);
                    
                } else {
                    showMessage(response.message, 'error');
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                let errorMessage = 'Une erreur est survenue lors de la mise à jour.';
                
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
                    errorMessage = 'Vous n\'avez pas la permission de modifier des feuilles de temps.';
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

// Fonction de confirmation de suppression
function confirmDelete(timesheetId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette feuille de temps ? Cette action est irréversible.')) {
        document.getElementById('delete-form-' + timesheetId).submit();
    }
}

// Fonction pour confirmer la suppression du fichier
function confirmDeleteFile() {
    $('#deleteFileModal').modal('show');
}

// Suppression du fichier
$('#btnConfirmDeleteFile').click(function() {
    // Créer un champ caché pour indiquer la suppression du fichier
    $('<input>').attr({
        type: 'hidden',
        name: 'delete_file',
        value: '1'
    }).appendTo('#timesheetForm');
    
    // Masquer l'affichage du fichier actuel
    updateCurrentFileDisplay();
    
    $('#deleteFileModal').modal('hide');
    showMessage('La pièce jointe sera supprimée lors de la sauvegarde.', 'warning');
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
.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}
.alert-info {
    background-color: #e8f4fd;
    border-color: #b6e0fe;
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
</style>
@endsection