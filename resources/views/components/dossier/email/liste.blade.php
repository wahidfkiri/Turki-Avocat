<!-- Onglet Emails -->
<div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
    <div class="p-3">
        <h5 class="text-primary mb-3"><i class="fas fa-envelope"></i> Emails attachés</h5>
        
        <!-- Loader simple CSS -->
        <div id="emailLoader" class="email-loader" style="display: none;">
            <div class="loader-spinner"></div>
            <span class="loader-text">Chargement...</span>
        </div>
        <!-- Liste des emails -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-list mr-2"></i>Liste des emails
                            <small class="text-muted ml-2" id="emailListInfo">Chargement...</small>
                        </h6>
                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 200px;">
                                <input type="text" class="form-control" placeholder="Rechercher..." id="emailSearch">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover w-100" id="email-table">
                                <thead class="thead-light">
                                    <tr>
                                        <!-- <th width="50px">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="checkAllEmails">
                                                <label for="checkAllEmails"></label>
                                            </div>
                                        </th> -->
                                        <th>Sujet</th>
                                        <th>Expéditeur</th>
                                        <th width="120px">Date</th>
                                        <th width="100px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="emailTableBody">
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="loader-spinner small"></div>
                                            <p class="mt-2 text-muted">Chargement des emails...</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="emailPaginationInfo" class="text-muted">
                                    Affichage de 0 email(s)
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                <div id="attachButtonContainer" style="display: none;">
                                    <button type="button" class="btn btn-success" id="attachSelectedEmails">
                                        <i class="fas fa-link mr-1"></i> Attacher les emails sélectionnés
                                    </button>
                                    <small class="text-muted ml-2">
                                        <span id="selectedEmailsCount">0</span> email(s) sélectionné(s)
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation pour l'attachement groupé -->
<div class="modal fade" id="confirmAttachModal" tabindex="-1" role="dialog" aria-labelledby="confirmAttachModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmAttachModalLabel">
                    <i class="fas fa-link text-success mr-2"></i>Confirmation d'attachement
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir attacher <strong><span id="attachCount">0</span> email(s)</strong> à ce dossier ?</p>
                <div class="selected-emails-preview mt-3" id="selectedEmailsPreview"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" id="confirmAttach">
                    <i class="fas fa-link mr-1"></i> Attacher
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour visualiser un email -->
<div class="modal fade" id="emailViewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailViewModalTitle">Chargement...</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="emailViewModalBody">
                <div class="text-center py-4">
                    <div class="loader-spinner"></div>
                    <p class="mt-2 text-muted">Chargement de l'email...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Script pour la gestion des emails -->
<script>
$(document).ready(function() {
    let currentFolder = 'Dossiers/{{$dossier->numero_dossier}}-{{$dossier->nom_dossier}}-{{$dossier->id}}';
    let currentEmails = [];
    let selectedEmails = new Set();

    loadEmails(currentFolder);

   

    // Charger les emails d'un dossier
    function loadEmails(folder, searchTerm = '') {
        showLoader('Chargement des emails depuis ' + folder + '...');
        
        $.ajax({
            url: '/emails/list',
            method: 'GET',
            data: {
                folder: folder,
                limit: 100
            },
            success: function(response) {
                hideLoader();
                
                if (response.success) {
                    currentEmails = response.emails;
                    displayEmails(currentEmails, searchTerm);
                    updateStats(response.count, folder);
                    showNotification('success', `${response.count} email(s) chargé(s) depuis ${folder}`);
                } else {
                    $('#emailTableBody').html(`
                        <tr>
                            <td colspan="7" class="text-center py-4 text-danger">
                                <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                                <p>Erreur: ${response.error}</p>
                                <button class="btn btn-sm btn-primary mt-2" onclick="loadEmails('${folder}')">
                                    <i class="fas fa-redo"></i> Réessayer
                                </button>
                            </td>
                        </tr>
                    `);
                    showNotification('error', 'Erreur: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                hideLoader();
                $('#emailTableBody').html(`
                    <tr>
                        <td colspan="7" class="text-center py-4 text-danger">
                            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                            <p>Erreur de connexion: ${error}</p>
                            <button class="btn btn-sm btn-primary mt-2" onclick="loadEmails('${folder}')">
                                <i class="fas fa-redo"></i> Réessayer
                            </button>
                        </td>
                    </tr>
                `);
                showNotification('error', 'Erreur de connexion: ' + error);
            }
        });
    }

    // Afficher les emails dans le tableau
    function displayEmails(emails, searchTerm = '') {
        const tbody = $('#emailTableBody');
        
        if (emails.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aucun email trouvé dans ce dossier</p>
                        <button class="btn btn-sm btn-primary mt-2" onclick="loadEmails('${currentFolder}')">
                            <i class="fas fa-redo"></i> Actualiser
                        </button>
                    </td>
                </tr>
            `);
            return;
        }

        let html = '';
        let filteredEmails = emails;

        // Filtrer par terme de recherche
        if (searchTerm) {
            filteredEmails = emails.filter(email => 
                email.subject.toLowerCase().includes(searchTerm.toLowerCase()) ||
                email.from_email.toLowerCase().includes(searchTerm.toLowerCase()) ||
                email.from_name.toLowerCase().includes(searchTerm.toLowerCase())
            );
        }

        filteredEmails.forEach(email => {
            const isSelected = selectedEmails.has(email.uid);
            const seenClass = email.seen ? '' : 'font-weight-bold';
            const seenBadge = email.seen ? 
                '<span class="badge badge-secondary">Lu</span>' : 
                '<span class="badge badge-primary">Non lu</span>';
            
            html += `
                <tr>
                    <td>
                        <div class="email-subject" title="${escapeHtml(email.subject)}">
                            <strong>${escapeHtml(email.subject)}</strong>
                        </div>
                    </td>
                    <td>
                        <div class="email-from">
                            <div>${escapeHtml(email.from_name)}</div>
                            <small class="text-muted">${escapeHtml(email.from_email)}</small>
                        </div>
                    </td>
                    <td>
                        <small class="text-muted">${formatDate(email.date)}</small>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-info view-email" 
                                    title="Voir l'email"
                                    data-uid="${email.uid}"
                                    data-folder="${currentFolder}">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        tbody.html(html);
        updatePaginationInfo(filteredEmails.length, emails.length);
        attachEmailEvents();
        updateAttachButton();
    }

    // Mettre à jour les statistiques
    function updateStats(count, folder) {
        $('#totalEmailsCount').text(count);
        $('#currentFolder').text(folder);
        // $('#loadedEmailsCount').text(currentEmails.length);
        $('#lastRefresh').text(new Date().toLocaleTimeString());
        $('#emailListInfo').text(`${count} email(s) dans ${folder}`);
    }

    // Mettre à jour les informations de pagination
    function updatePaginationInfo(displayed, total) {
        $('#emailPaginationInfo').text(`Affichage de ${displayed} email(s) sur ${total}`);
    }

    // Gestion du loader
    function showLoader(message) {
        $('#emailLoader .loader-text').text(message);
        $('#emailLoader').show();
    }

    function hideLoader() {
        $('#emailLoader').hide();
    }

    // Événements
    $('#folderSelect').on('change', function() {
        currentFolder = $(this).val();
        selectedEmails.clear();
        loadEmails(currentFolder);
    });

    $('#refreshEmails').on('click', function() {
        const button = $(this);
        const originalHtml = button.html();
        
        button.html('<i class="fas fa-spinner fa-spin"></i>');
        button.prop('disabled', true);
        
        loadEmails(currentFolder);
        
        setTimeout(() => {
            button.html(originalHtml);
            button.prop('disabled', false);
        }, 1000);
    });

    $('#loadImapEmails').on('click', function() {
        loadEmails(currentFolder);
    });

    $('#testImapConnection').on('click', function() {
        const button = $(this);
        const originalHtml = button.html();
        
        button.html('<i class="fas fa-spinner fa-spin"></i>');
        button.prop('disabled', true);
        
        $.ajax({
            url: '/emails/test-connection',
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    showNotification('success', 
                        `Connexion réussie! ${response.messages_count} message(s) dans la boîte.`);
                } else {
                    showNotification('error', 'Erreur de connexion: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                showNotification('error', 'Erreur de test: ' + error);
            },
            complete: function() {
                button.html(originalHtml);
                button.prop('disabled', false);
            }
        });
    });

    // Recherche d'emails
    $('#emailSearch').on('input', function() {
        const searchTerm = $(this).val();
        displayEmails(currentEmails, searchTerm);
    });

    $('#clearSearch').on('click', function() {
        $('#emailSearch').val('');
        displayEmails(currentEmails, '');
    });

    // Gestion des checkboxes
    function attachEmailEvents() {
        // Checkbox "Tout cocher"
        $('#checkAllEmails').off('change').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('.email-checkbox').prop('checked', isChecked);
            
            if (isChecked) {
                currentEmails.forEach(email => selectedEmails.add(email.uid));
            } else {
                selectedEmails.clear();
            }
            
            updateAttachButton();
        });

        // Checkboxes individuelles
        $('.email-checkbox').off('change').on('change', function() {
            const uid = parseInt($(this).val());
            
            if ($(this).is(':checked')) {
                selectedEmails.add(uid);
            } else {
                selectedEmails.delete(uid);
            }
            
            updateAttachButton();
        });

        // Bouton d'attachement individuel
        $('.attach-single-email').off('click').on('click', function() {
            const uid = $(this).data('uid');
            const subject = $(this).data('subject');
            const from = $(this).data('from');
            
            attachEmails([uid], [{uid, subject, from}]);
        });

        // Bouton de visualisation
        $('.view-email').off('click').on('click', function() {
            const uid = $(this).data('uid');
            const folder = $(this).data('folder');
            
            viewEmail(uid, folder);
        });
    }

    // Mettre à jour le bouton d'attachement groupé
    function updateAttachButton() {
        const count = selectedEmails.size;
        
        if (count > 0) {
            $('#attachButtonContainer').show();
            $('#selectedEmailsCount').text(count);
        } else {
            $('#attachButtonContainer').hide();
        }
    }

    // Attachement groupé
    $('#attachSelectedEmails').on('click', function() {
        const selectedUids = Array.from(selectedEmails);
        const selectedEmailData = currentEmails.filter(email => 
            selectedEmails.has(email.uid)
        ).map(email => ({
            uid: email.uid,
            subject: email.subject,
            from: email.from_name + ' <' + email.from_email + '>'
        }));

        showAttachConfirmation(selectedUids, selectedEmailData);
    });

    // Afficher la confirmation d'attachement
    function showAttachConfirmation(uids, emailData) {
        $('#attachCount').text(uids.length);
        
        let previewHtml = '';
        emailData.slice(0, 5).forEach(email => {
            previewHtml += `
                <div class="border-bottom pb-2 mb-2">
                    <strong>${escapeHtml(email.subject)}</strong><br>
                    <small class="text-muted">${escapeHtml(email.from)}</small>
                </div>
            `;
        });
        
        if (uids.length > 5) {
            previewHtml += `<small class="text-muted">... et ${uids.length - 5} autre(s) email(s)</small>`;
        }
        
        $('#selectedEmailsPreview').html(previewHtml);
        $('#confirmAttachModal').modal('show');
        
        $('#confirmAttach').data('uids', uids);
    }

    // Confirmation d'attachement
    $('#confirmAttach').on('click', function() {
        const uids = $(this).data('uids');
        attachEmails(uids);
    });

    // Fonction d'attachement des emails
    function attachEmails(uids, emailData = null) {
        const button = $('#confirmAttach');
        const originalText = button.html();
        
        button.html('<i class="fas fa-spinner fa-spin mr-1"></i> Attachement...');
        button.prop('disabled', true);

        $.ajax({
            url: '{{ route("email.attach-to-dossier") }}',
            method: 'POST',
            data: {
                dossier_id: {{ $dossier->id }},
                email_uids: uids,
                folder_name: currentFolder,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    $('#confirmAttachModal').modal('hide');
                    
                    // Désélectionner les emails attachés
                    uids.forEach(uid => selectedEmails.delete(uid));
                    updateAttachButton();
                    
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function(xhr, status, error) {
                showNotification('error', 'Erreur lors de l\'attachement: ' + error);
            },
            complete: function() {
                button.html(originalText);
                button.prop('disabled', false);
            }
        });
    }

    // Visualiser un email
    function viewEmail(uid, folder) {
        $('#emailViewModalTitle').text('Chargement...');
        $('#emailViewModalBody').html(`
            <div class="text-center py-4">
                <div class="loader-spinner"></div>
                <p class="mt-2 text-muted">Chargement de l'email...</p>
            </div>
        `);
        
        $('#emailViewModal').modal('show');
        
        $.ajax({
            url: '/emails/email',
            method: 'GET',
            data: {
                folder: folder,
                uid: uid
            },
            success: function(response) {
                if (response.success) {
                    const email = response.email;
                    $('#emailViewModalTitle').text(escapeHtml(email.subject));
                    
                    const emailHtml = `
                        <div class="email-header border-bottom pb-3 mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>De:</strong> ${escapeHtml(email.from_name)}<br>
                                    <strong>Email:</strong> ${escapeHtml(email.from_email)}
                                </div>
                                <div class="col-md-6 text-right">
                                    <strong>Date:</strong> ${formatDate(email.date)}<br>
                                    <strong>UID:</strong> ${email.uid}
                                </div>
                            </div>
                        </div>
                        <div class="email-body">
                            <pre style="white-space: pre-wrap; font-family: inherit; background: #f8f9fa; padding: 15px; border-radius: 5px;">${escapeHtml(email.body)}</pre>
                        </div>
                    `;
                    
                    $('#emailViewModalBody').html(emailHtml);
                    
                    $('#emailViewModal .modal-footer').html(`
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    `);
                } else {
                    $('#emailViewModalBody').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Erreur: ${response.error}
                        </div>
                    `);
                }
            },
            error: function(xhr, status, error) {
                $('#emailViewModalBody').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Erreur de chargement: ${error}
                    `);
            }
        });
    }

    // Utilitaires
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function formatDate(dateString) {
        if (!dateString) return 'Date inconnue';
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR') + ' ' + date.toLocaleTimeString('fr-FR');
        } catch (e) {
            return dateString;
        }
    }

    function showNotification(type, message) {
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
                    ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;
            $('body').append(alertHtml);
            setTimeout(() => $('.alert').alert('close'), 1000);
        }
    }
});
</script>

<style>
/* Loader CSS simple */
.email-loader {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 8px;
    margin: 10px 0;
    border: 1px solid #e3e6f0;
}

.loader-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 10px;
}

.loader-spinner.small {
    width: 16px;
    height: 16px;
    border-width: 1.5px;
}

.loader-text {
    color: #6c757d;
    font-size: 14px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Styles existants */
.info-box {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    border-radius: 0.25rem;
    background: #fff;
    display: flex;
    margin-bottom: 1rem;
    min-height: 80px;
    padding: 0.5rem;
    position: relative;
}

.info-box .info-box-icon {
    border-radius: 0.25rem;
    align-items: center;
    display: flex;
    font-size: 1.875rem;
    justify-content: center;
    text-align: center;
    width: 70px;
}

.info-box .info-box-content {
    flex: 1;
    padding: 0.5rem 0.5rem 0.5rem 1rem;
}

.info-box .info-box-number {
    display: block;
    font-weight: 700;
    font-size: 1.5rem;
}

.email-subject {
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.email-from {
    max-width: 200px;
}

.table-responsive {
    border-radius: 0.375rem;
    overflow: hidden;
}

#emailTableBody tr {
    cursor: pointer;
}

#emailTableBody tr:hover {
    background-color: #f8f9fa !important;
}

.btn:disabled {
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .info-box .info-box-icon {
        width: 50px;
        font-size: 1.5rem;
    }
    
    .info-box .info-box-number {
        font-size: 1.2rem;
    }
    
    .btn-group .btn {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    
    .email-subject {
        max-width: 150px;
    }
    
    .email-from {
        max-width: 120px;
    }
}
</style>