<!-- Onglet Emails -->
<div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
    <div class="p-3">
        <h5 class="text-primary mb-3"><i class="fas fa-envelope"></i> Emails attachés</h5>
        
        <!-- Statistiques des emails -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-envelope"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total des emails</span>
                        <span class="info-box-number">{{ $dossier->attachedEmails->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-paperclip"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Dossiers sources</span>
                        <span class="info-box-number">{{ $dossier->attachedEmails->groupBy('folder_name')->count() }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-calendar"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Plus ancien</span>
                        <span class="info-box-number">
                            @if($dossier->attachedEmails->count() > 0)
                                {{ $dossier->attachedEmails->min('email_date')?->format('d/m/Y') ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-primary">
                    <span class="info-box-icon"><i class="fas fa-sync"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Dernier ajout</span>
                        <span class="info-box-number">
                            @if($dossier->attachedEmails->count() > 0)
                                {{ $dossier->attachedEmails->max('created_at')?->format('d/m/Y') ?? 'N/A' }}
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des emails -->
        <div class="row">
            <div class="col-md-12">
                @if($dossier->attachedEmails && $dossier->attachedEmails->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-list mr-2"></i>Liste des emails attachés
                            </h6>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover w-100" id="email-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="50px">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox" id="checkAllEmails">
                                                    <label for="checkAllEmails"></label>
                                                </div>
                                            </th>
                                            <th>Sujet</th>
                                            <th>Expéditeur</th>
                                            <th>Dossier source</th>
                                            <th>Date de l'email</th>
                                            <th>Date d'attachement</th>
                                            <th>Attaché par</th>
                                            <th width="100px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dossier->attachedEmails as $attachedEmail)
                                        <tr>
                                            <td>
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox" class="email-checkbox" value="{{ $attachedEmail->email_uid }}" 
                                                           data-folder="{{ $attachedEmail->folder_name }}" 
                                                           id="checkEmail{{ $attachedEmail->id }}">
                                                    <label for="checkEmail{{ $attachedEmail->id }}"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="email-subject">
                                                    <strong>{{ $attachedEmail->subject ?? 'Sans objet' }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $attachedEmail->from ?? 'Expéditeur inconnu' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $attachedEmail->folder_name }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $attachedEmail->email_date ? $attachedEmail->email_date->format('d/m/Y H:i') : 'Date inconnue' }}
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $attachedEmail->created_at->format('d/m/Y H:i') }}
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $attachedEmail->user?->name ?? 'Utilisateur inconnu' }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('email.show', ['folder' => $attachedEmail->folder_name, 'uid' => $attachedEmail->email_uid]) }}" 
                                                       class="btn btn-info" title="Voir l'email" target="_blank">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger detach-single-email" 
                                                            title="Détacher cet email"
                                                            data-email-uid="{{ $attachedEmail->email_uid }}"
                                                            data-folder-name="{{ $attachedEmail->folder_name }}"
                                                            data-email-subject="{{ $attachedEmail->subject ?? 'Sans objet' }}">
                                                        <i class="fas fa-unlink"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <!-- Bouton de détachement groupé -->
                            <div id="detachButtonContainer" style="display: none;">
                                <button type="button" class="btn btn-danger" id="detachSelectedEmails">
                                    <i class="fas fa-unlink mr-1"></i> Détacher les emails sélectionnés
                                </button>
                                <small class="text-muted ml-2">
                                    <span id="selectedEmailsCount">0</span> email(s) sélectionné(s)
                                </small>
                            </div>
                            
                            <!-- Lien pour attacher plus d'emails -->
                            <div class="mt-2">
                                <a href="{{ route('email.index') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus mr-1"></i> Attacher d'autres emails
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Aucun email attaché</h4>
                        <p class="text-muted">Ce dossier ne contient aucun email pour le moment.</p>
                        <a href="{{ route('email.index') }}" 
                           class="btn btn-primary mt-2">
                            <i class="fas fa-link mr-1"></i> Attacher des emails
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation pour le détachement groupé -->
<div class="modal fade" id="confirmDetachModal" tabindex="-1" role="dialog" aria-labelledby="confirmDetachModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDetachModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning mr-2"></i>Confirmation de détachement
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir détacher <strong><span id="detachCount">0</span> email(s)</strong> de ce dossier ?</p>
                <p class="text-muted small">
                    <i class="fas fa-info-circle mr-1"></i> 
                    Cette action ne supprime pas les emails de votre boîte mail, mais seulement leur lien avec ce dossier.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDetach">
                    <i class="fas fa-unlink mr-1"></i> Détacher
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation pour le détachement individuel -->
<div class="modal fade" id="confirmSingleDetachModal" tabindex="-1" role="dialog" aria-labelledby="confirmSingleDetachModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmSingleDetachModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning mr-2"></i>Confirmation de détachement
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir détacher l'email suivant de ce dossier ?</p>
                <div class="alert alert-light border">
                    <strong id="singleEmailSubject">Sujet de l'email</strong><br>
                    <small class="text-muted" id="singleEmailInfo">Informations supplémentaires</small>
                </div>
                <p class="text-muted small">
                    <i class="fas fa-info-circle mr-1"></i> 
                    Cette action ne supprime pas l'email de votre boîte mail, mais seulement son lien avec ce dossier.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmSingleDetach">
                    <i class="fas fa-unlink mr-1"></i> Détacher
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Script pour la gestion des emails -->
<script>
$(document).ready(function() {
    // Initialisation de DataTables
    if ($('#email-table').length) {
        $('#email-table').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json"
            },
            "responsive": true,
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
            "pageLength": 10,
            "order": [[5, 'desc']], // Trier par date d'attachement décroissante
            "columnDefs": [
                {
                    "targets": 0, // Colonne checkbox
                    "orderable": false,
                    "searchable": false
                },
                {
                    "targets": 6, // Colonne Actions
                    "orderable": false,
                    "searchable": false
                }
            ],
            "drawCallback": function(settings) {
                // Réinitialiser les checkboxes après le redessinage de la table
                updateDetachButton();
                // Réattacher les événements après le redessinage
                attachSingleDetachEvents();
            }
        });
    }

    // Gestion des checkboxes
    const checkAllEmails = $('#checkAllEmails');
    const emailCheckboxes = $('.email-checkbox');
    const detachButtonContainer = $('#detachButtonContainer');
    const selectedEmailsCount = $('#selectedEmailsCount');

    // Fonction pour mettre à jour le bouton de détachement
    function updateDetachButton() {
        const checkedCount = $('.email-checkbox:checked').length;
        if (checkedCount > 0) {
            detachButtonContainer.show();
            selectedEmailsCount.text(checkedCount);
        } else {
            detachButtonContainer.hide();
        }
        
        // Mettre à jour l'état de "Tout cocher"
        const allChecked = checkedCount === emailCheckboxes.length;
        const someChecked = checkedCount > 0 && checkedCount < emailCheckboxes.length;
        
        checkAllEmails.prop('checked', allChecked);
        checkAllEmails.prop('indeterminate', someChecked);
    }

    // Événement pour "Tout cocher"
    checkAllEmails.on('change', function() {
        emailCheckboxes.prop('checked', this.checked);
        updateDetachButton();
    });

    // Événements pour les cases individuelles
    emailCheckboxes.on('change', updateDetachButton);

    // Détachement groupé
    $('#detachSelectedEmails').on('click', function() {
        const selectedEmails = [];
        const selectedFolders = [];
        
        $('.email-checkbox:checked').each(function() {
            selectedEmails.push($(this).val());
            selectedFolders.push($(this).data('folder'));
        });

        $('#detachCount').text(selectedEmails.length);
        $('#confirmDetachModal').modal('show');
        
        // Stocker les données pour la confirmation
        $('#confirmDetach').data('emails', selectedEmails);
    });

    // Confirmation du détachement groupé
    $('#confirmDetach').on('click', function() {
        const selectedEmails = $(this).data('emails');
        const button = $(this);
        const originalText = button.html();
        
        button.html('<i class="fas fa-spinner fa-spin mr-1"></i> Détachement...');
        button.prop('disabled', true);

        // Envoi AJAX
        $.ajax({
            url: '{{ route("email.detach-from-dossier") }}',
            method: 'POST',
            data: {
                dossier_id: {{ $dossier->id }},
                email_uids: selectedEmails,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    // Recharger la page pour voir les changements
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showNotification('error', 'Une erreur est survenue lors du détachement');
            },
            complete: function() {
                $('#confirmDetachModal').modal('hide');
                button.html(originalText);
                button.prop('disabled', false);
            }
        });
    });

    // Fonction pour attacher les événements de détachement individuel
    function attachSingleDetachEvents() {
        $('.detach-single-email').off('click').on('click', function() {
            const emailUid = $(this).data('email-uid');
            const folderName = $(this).data('folder-name');
            const emailSubject = $(this).data('email-subject');
            const fromEmail = $(this).closest('tr').find('td:nth-child(3)').text().trim();
            
            // Mettre à jour le modal avec les informations de l'email
            $('#singleEmailSubject').text(emailSubject);
            $('#singleEmailInfo').html(`
                <strong>UID:</strong> ${emailUid}<br>
                <strong>Dossier source:</strong> ${folderName}<br>
                <strong>Expéditeur:</strong> ${fromEmail}
            `);
            
            // Stocker les données pour la confirmation
            $('#confirmSingleDetach').data('email-uid', emailUid);
            $('#confirmSingleDetach').data('button', $(this));
            
            // Afficher le modal
            $('#confirmSingleDetachModal').modal('show');
        });
    }

    // Initialiser les événements de détachement individuel
    attachSingleDetachEvents();

    // Confirmation du détachement individuel
    $('#confirmSingleDetach').on('click', function() {
        const emailUid = $(this).data('email-uid');
        const button = $(this).data('button');
        const confirmButton = $(this);
        const originalText = confirmButton.html();
        
        confirmButton.html('<i class="fas fa-spinner fa-spin mr-1"></i> Détachement...');
        confirmButton.prop('disabled', true);

        // Désactiver le bouton original pendant le traitement
        if (button) {
            button.prop('disabled', true);
            button.html('<i class="fas fa-spinner fa-spin"></i>');
        }

        $.ajax({
            url: '{{ route("email.detach-from-dossier") }}',
            method: 'POST',
            data: {
                dossier_id: {{ $dossier->id }},
                email_uids: [emailUid],
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    
                    // Fermer le modal
                    $('#confirmSingleDetachModal').modal('hide');
                    
                    // Supprimer la ligne du tableau
                    if (button) {
                        button.closest('tr').fadeOut(400, function() {
                            $(this).remove();
                            // Si plus d'emails, recharger la page
                            if ($('#email-table tbody tr').length === 0) {
                                setTimeout(() => {
                                    location.reload();
                                }, 500);
                            }
                        });
                    }
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showNotification('error', 'Une erreur est survenue lors du détachement');
            },
            complete: function() {
                confirmButton.html(originalText);
                confirmButton.prop('disabled', false);
                
                // Réactiver le bouton original en cas d'erreur
                if (button && !button.closest('tr').is(':visible')) {
                    button.prop('disabled', false);
                    button.html('<i class="fas fa-unlink"></i>');
                }
            }
        });
    });

    // Réinitialiser le modal individuel quand il se ferme
    $('#confirmSingleDetachModal').on('hidden.bs.modal', function () {
        const confirmButton = $('#confirmSingleDetach');
        confirmButton.html('<i class="fas fa-unlink mr-1"></i> Détacher');
        confirmButton.prop('disabled', false);
        confirmButton.removeData('email-uid');
        confirmButton.removeData('button');
    });

    // Fonction pour afficher les notifications
    function showNotification(type, message) {
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            alert(message);
        }
    }
});
</script>

<!-- Styles supplémentaires pour les emails -->
<style>
#email-table thead th {
    background-color: #f8f9fa;
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
}

.hover-row {
    background-color: #f8f9fa !important;
    transition: background-color 0.3s ease;
}

.email-subject {
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.info-box {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    border-radius: 0.25rem;
    background: #fff;
    display: -ms-flexbox;
    display: flex;
    margin-bottom: 1rem;
    min-height: 80px;
    padding: 0.5rem;
    position: relative;
}

.info-box .info-box-icon {
    border-radius: 0.25rem;
    -ms-flex-align: center;
    align-items: center;
    display: -ms-flexbox;
    display: flex;
    font-size: 1.875rem;
    -ms-flex-pack: center;
    justify-content: center;
    text-align: center;
    width: 70px;
}

.info-box .info-box-content {
    -ms-flex: 1;
    flex: 1;
    padding: 0.5rem 0.5rem 0.5rem 1rem;
}

.info-box .info-box-number {
    display: block;
    font-weight: 700;
    font-size: 1.5rem;
}

.badge {
    font-size: 0.75em;
    padding: 0.3em 0.6em;
}

.table-responsive {
    border-radius: 0.375rem;
    overflow: hidden;
}

/* Style pour DataTables */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 1rem;
}

.dataTables_wrapper .dataTables_filter input {
    margin-left: 0.5em;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.375rem 0.75rem;
}

/* Style pour les checkboxes */
.icheck-primary.d-inline {
    display: inline-block;
    margin-right: 0;
}

/* Style pour le modal d'information */
.alert-light {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}
</style>