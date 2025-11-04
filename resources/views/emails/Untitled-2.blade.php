@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold text-primary">
                        <i class="fas fa-envelope mr-2"></i>Gestion des Emails
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Accueil</a></li>
                        <li class="breadcrumb-item active">Emails - INBOX</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Alert Messages -->
            <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle mr-3 fa-lg"></i>
                    <div class="flex-grow-1">
                        <h6 class="alert-heading mb-1">Succès!</h6>
                        <p class="mb-0">3 nouveaux emails ont été récupérés avec succès.</p>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>

            <div class="row">
                <!-- Sidebar - Dossiers -->
                <div class="col-md-3">
                    <div class="card card-modern">
                        <div class="card-header-modern text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-folder mr-2"></i>
                                    Tous les Dossiers
                                </h3>
                                <span class="badge badge-light badge-modern">6</span>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                            <ul class="nav nav-pills flex-column">
                                <li class="nav-item">
                                    <a href="#" class="nav-link active">
                                        <i class="fas fa-inbox mr-2"></i>
                                        INBOX
                                        <span class="badge badge-primary float-right">12</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Envoyés
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-edit mr-2"></i>
                                        Brouillons
                                        <span class="badge badge-warning float-right">3</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-trash mr-2"></i>
                                        Corbeille
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        Spam
                                        <span class="badge badge-danger float-right">5</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-archive mr-2"></i>
                                        Archives
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                6 dossier(s) disponible(s)
                            </small>
                        </div>
                    </div>

                    <!-- Card Actions -->
                    <div class="card card-modern">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-edit mr-1"></i>
                                Nouvel Email
                            </h3>
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-primary btn-modern btn-block" data-toggle="modal" data-target="#composeModal">
                                <i class="fas fa-pen mr-1"></i>Composer
                            </button>
                            <a href="#" class="btn btn-outline-secondary btn-modern btn-block mt-2">
                                <i class="fas fa-sync-alt mr-1"></i>Rafraîchir
                            </a>
                        </div>
                    </div>

                    <!-- Card Info -->
                    <div class="card card-modern">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-1"></i>
                                Informations
                            </h3>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Compte:</strong></p>
                            <p class="text-sm text-muted">wahid.fkiri@peakmind-solutions.com</p>
                            
                            <p class="mb-1"><strong>Dossier actuel:</strong></p>
                            <p class="text-sm text-muted">INBOX</p>
                            
                            <p class="mb-1"><strong>Total dossiers:</strong></p>
                            <p class="text-sm text-muted">6</p>
                            
                            <p class="mb-1"><strong>Emails affichés:</strong></p>
                            <p class="text-sm text-muted">8</p>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-md-9">
                    <div class="card card-modern">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title">
                                    <i class="fas fa-inbox mr-1 text-primary"></i>
                                    INBOX
                                    <span class="badge badge-primary badge-modern ml-2">8 email(s)</span>
                                </h3>
                                <div class="card-tools">
                                    <div class="input-group input-group-modern" style="width: 250px;">
                                        <input type="text" name="table_search" class="form-control form-control-modern" placeholder="Rechercher..." id="emailSearch">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary btn-modern" id="searchButton">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <!-- Bouton Attacher à un dossier (caché par défaut) -->
                            <div id="attachButtonContainer" class="p-3 border-bottom" style="display: none;">
                                <button type="button" class="btn btn-primary btn-modern" data-toggle="modal" data-target="#attachToFolderModal">
                                    <i class="fas fa-folder-plus mr-1"></i> Attacher à un dossier
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-modern table-hover table-striped" id="emailsTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 40px">
                                                <div class="form-check">
                                                    <input type="checkbox" id="checkAll" class="form-check-input">
                                                    <label for="checkAll" class="form-check-label"></label>
                                                </div>
                                            </th>
                                            <th style="width: 40px"></th>
                                            <th>Expéditeur</th>
                                            <th>Sujet & Aperçu</th>
                                            <th style="width: 40px"></th>
                                            <th style="width: 150px">Date</th>
                                            <th style="width: 100px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="font-weight-bold bg-light">
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" value="1" id="check1" class="form-check-input email-checkbox">
                                                    <label for="check1" class="form-check-label"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#" class="text-warning mailbox-star">
                                                    <i class="fas fa-star"></i>
                                                </a>
                                            </td>
                                            <td class="mailbox-name">
                                                <small>John Doe</small>
                                            </td>
                                            <td class="mailbox-subject">
                                                <a href="#" class="text-dark text-decoration-none">
                                                    <div class="font-weight-bold">
                                                        Bienvenue sur notre plateforme
                                                    </div>
                                                    <div class="text-muted text-sm mt-1">
                                                        Merci de vous être inscrit sur notre plateforme. Nous sommes ravis de vous accueillir...
                                                    </div>
                                                </a>
                                            </td>
                                            <td class="mailbox-attachment text-center">
                                                <i class="fas fa-paperclip text-muted" title="1 pièce(s) jointe(s)"></i>
                                            </td>
                                            <td class="mailbox-date text-sm">
                                                <small>Aujourd'hui, 10:30</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="#" class="btn btn-info" title="Voir l'email">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" value="2" id="check2" class="form-check-input email-checkbox">
                                                    <label for="check2" class="form-check-label"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#" class="text-warning mailbox-star">
                                                    <i class="fas fa-star-o"></i>
                                                </a>
                                            </td>
                                            <td class="mailbox-name">
                                                <small>Support Technique</small>
                                            </td>
                                            <td class="mailbox-subject">
                                                <a href="#" class="text-dark text-decoration-none">
                                                    <div class="font-weight-bold">
                                                        Votre ticket a été résolu
                                                    </div>
                                                    <div class="text-muted text-sm mt-1">
                                                        Nous sommes heureux de vous informer que votre ticket #4587 a été résolu avec succès...
                                                    </div>
                                                </a>
                                            </td>
                                            <td class="mailbox-attachment text-center">
                                                <!-- No attachment -->
                                            </td>
                                            <td class="mailbox-date text-sm">
                                                <small>Hier, 14:22</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="#" class="btn btn-info" title="Voir l'email">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input type="checkbox" value="3" id="check3" class="form-check-input email-checkbox">
                                                    <label for="check3" class="form-check-label"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="#" class="text-warning mailbox-star">
                                                    <i class="fas fa-star"></i>
                                                </a>
                                            </td>
                                            <td class="mailbox-name">
                                                <small>Équipe Marketing</small>
                                            </td>
                                            <td class="mailbox-subject">
                                                <a href="#" class="text-dark text-decoration-none">
                                                    <div class="font-weight-bold">
                                                        Nouveautés de ce mois
                                                    </div>
                                                    <div class="text-muted text-sm mt-1">
                                                        Découvrez les nouvelles fonctionnalités que nous avons ajoutées cette semaine...
                                                    </div>
                                                </a>
                                            </td>
                                            <td class="mailbox-attachment text-center">
                                                <i class="fas fa-paperclip text-muted" title="2 pièce(s) jointe(s)"></i>
                                            </td>
                                            <td class="mailbox-date text-sm">
                                                <small>05/12/2024</small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="#" class="btn btn-info" title="Voir l'email">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer clearfix">
                            <div class="float-left">
                                <button type="button" class="btn btn-default btn-sm btn-modern" onclick="location.reload()">
                                    <i class="fas fa-sync-alt mr-1"></i> Actualiser
                                </button>
                            </div>
                            <div class="float-right">
                                <small class="text-muted">
                                    Affichage de 8 email(s)
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Compose Modal -->
<div class="modal fade" id="composeModal" tabindex="-1" role="dialog" aria-labelledby="composeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h4 class="modal-title" id="composeModalLabel">
                    <i class="fas fa-pen mr-2"></i>Nouveau Message
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="#" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="email" class="form-control form-control-modern" name="to" placeholder="À:" required>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-modern" name="subject" placeholder="Sujet:" required>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control form-control-modern" name="content" style="height: 300px" required placeholder="Votre message..."></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btn-modern" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary btn-modern">
                        <i class="fas fa-paper-plane mr-1"></i>Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour attacher aux dossiers -->
<div class="modal fade" id="attachToFolderModal" tabindex="-1" role="dialog" aria-labelledby="attachToFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="attachToFolderModalLabel">
                    <i class="fas fa-folder-plus mr-2"></i>Attacher aux dossiers
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="attachToFolderForm">
                    <div class="form-group">
                        <label for="folderSelect">Sélectionner un dossier :</label>
                        <select class="form-control form-control-modern" id="folderSelect" name="folder_id" required>
                            <option value="">-- Choisir un dossier --</option>
                            <option value="1">Dossier Projet Alpha</option>
                            <option value="2">Dossier Client Beta</option>
                            <option value="3">Dossier Documentation</option>
                            <option value="4">Dossier Réunions</option>
                        </select>
                    </div>
                    <div id="selectedEmailsInfo" class="alert alert-info">
                        <small><i class="fas fa-info-circle mr-1"></i> <span id="selectedCount">0</span> email(s) sélectionné(s)</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-modern" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary btn-modern" id="confirmAttach">
                    <i class="fas fa-link mr-1"></i> Attacher
                </button>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-color: #4361ee;
    --secondary-color: #6c757d;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --warning-color: #ffc107;
    --info-color: #17a2b8;
    --light-color: #f8f9fa;
    --dark-color: #343a40;
    --border-radius: 10px;
    --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --transition: all 0.3s ease;
}

/* Modern Cards */
.card-modern {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    transition: var(--transition);
    background: #ffffff;
}

.card-modern:hover {
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    transform: translateY(-2px);
}

.card-header-modern {
    background: linear-gradient(135deg, var(--primary-color) 0%, #3a56e4 100%);
    border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
    border: none;
    color: white;
}

/* Modern Buttons */
.btn-modern {
    border-radius: 8px;
    font-weight: 500;
    transition: var(--transition);
    border: none;
    padding: 0.5rem 1.5rem;
    font-size: 0.875rem;
}

.btn-modern:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #3a56e4 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #3a56e4 0%, #2f46c4 100%);
}

/* Modern Form Controls */
.form-control-modern {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    transition: var(--transition);
    padding: 0.75rem 1rem;
    font-size: 0.875rem;
}

.form-control-modern:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    transform: translateY(-1px);
}

.input-group-modern .form-control {
    border-right: none;
}

.input-group-modern .input-group-append .btn {
    border-left: none;
    border-color: #e2e8f0;
}

/* Table Styles */
.table-modern {
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--box-shadow);
    background: white;
    width: 100%;
    border-collapse: collapse;
}

.table-modern thead th {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: none;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    padding: 1rem 1.5rem;
    text-align: left;
}

.table-modern tbody td {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.table-modern tbody tr {
    transition: var(--transition);
    cursor: pointer;
}

.table-modern tbody tr:hover {
    background-color: #f8fafc;
    transform: translateY(-1px);
}

.table-modern tbody tr.bg-light {
    background-color: #f0f9ff !important;
    font-weight: 600;
}

.table-modern tbody tr.bg-light:hover {
    background-color: #e1f0ff !important;
}

/* Badges */
.badge-modern {
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
}

/* Alerts */
.alert-modern {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    border-left: 4px solid;
    padding: 1rem 1.5rem;
    margin-bottom: 1rem;
}

.alert-success {
    border-left-color: var(--success-color);
    background: linear-gradient(135deg, #f0fff4 0%, #e6fffa 100%);
    color: #065f46;
}

/* Mailbox Styles */
.mailbox-name {
    width: 180px;
}

.mailbox-subject {
    min-width: 300px;
}

.mailbox-date {
    width: 120px;
}

.table-responsive {
    min-height: 400px;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.card-body {
    scrollbar-width: thin;
}

/* Gradient Backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #3a56e4 100%) !important;
}

/* Modal Modern */
.modal-content {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.modal-header {
    border-radius: var(--border-radius) var(--border-radius) 0 0;
}

/* Custom Checkbox */
.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.form-check-label {
    cursor: pointer;
}

/* Star Animation */
.mailbox-star {
    transition: var(--transition);
}

.mailbox-star:hover {
    transform: scale(1.2);
}

/* Responsive */
@media (max-width: 768px) {
    .mailbox-name {
        width: 150px;
    }
    
    .mailbox-subject {
        min-width: 200px;
    }
    
    .table-modern {
        font-size: 0.875rem;
    }
    
    .input-group-modern {
        width: 200px !important;
    }
}
</style>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('checkAll');
    const emailCheckboxes = document.querySelectorAll('.email-checkbox');
    const attachButtonContainer = document.getElementById('attachButtonContainer');
    const selectedCount = document.getElementById('selectedCount');
    
    // Fonction pour mettre à jour l'affichage du bouton
    function updateAttachButton() {
        const checkedBoxes = document.querySelectorAll('.email-checkbox:checked');
        const hasChecked = checkedBoxes.length > 0;
        
        attachButtonContainer.style.display = hasChecked ? 'block' : 'none';
        selectedCount.textContent = checkedBoxes.length;
    }
    
    // Événement pour "Tout cocher"
    checkAll.addEventListener('change', function() {
        emailCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateAttachButton();
    });
    
    // Événements pour les cases individuelles
    emailCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Mettre à jour l'état de "Tout cocher"
            const allChecked = document.querySelectorAll('.email-checkbox:checked').length === emailCheckboxes.length;
            const someChecked = document.querySelectorAll('.email-checkbox:checked').length > 0;
            
            checkAll.checked = allChecked;
            checkAll.indeterminate = someChecked && !allChecked;
            
            updateAttachButton();
        });
    });
    
    // Gestion de la soumission du formulaire
    document.getElementById('confirmAttach').addEventListener('click', function() {
        const selectedFolder = document.getElementById('folderSelect').value;
        const selectedEmails = Array.from(document.querySelectorAll('.email-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        if (!selectedFolder) {
            alert('Veuillez sélectionner un dossier');
            return;
        }
        
        if (selectedEmails.length === 0) {
            alert('Aucun email sélectionné');
            return;
        }
        
        // Afficher un indicateur de chargement
        const button = this;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Attachement...';
        button.disabled = true;
        
        // Simuler l'envoi AJAX
        setTimeout(() => {
            alert(selectedEmails.length + ' email(s) attaché(s) au dossier avec succès!');
            
            // Fermer le modal
            $('#attachToFolderModal').modal('hide');
            
            // Décocher toutes les cases
            document.querySelectorAll('.email-checkbox:checked').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('checkAll').checked = false;
            
            // Masquer le bouton
            document.getElementById('attachButtonContainer').style.display = 'none';
            
            // Restaurer le bouton
            button.innerHTML = originalText;
            button.disabled = false;
        }, 1500);
    });
    
    // Star functionality
    document.querySelectorAll('.mailbox-star').forEach(star => {
        star.addEventListener('click', function(e) {
            e.preventDefault();
            const icon = this.querySelector('i');
            icon.classList.toggle('fa-star');
            icon.classList.toggle('fa-star-o');
        });
    });

    // Search functionality
    document.getElementById('emailSearch').addEventListener('keyup', function() {
        const value = this.value.toLowerCase();
        const rows = document.querySelectorAll('#emailsTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });

    document.getElementById('searchButton').addEventListener('click', function() {
        document.getElementById('emailSearch').dispatchEvent(new Event('keyup'));
    });
    
    // Réinitialiser le formulaire quand le modal se ferme
    $('#attachToFolderModal').on('hidden.bs.modal', function () {
        document.getElementById('folderSelect').value = '';
    });
});
</script>
@endsection