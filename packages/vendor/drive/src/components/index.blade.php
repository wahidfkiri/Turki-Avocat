
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- AdminLTE 3 -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gray-color: #6c757d;
            --border-radius: 12px;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        .file-browser-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--dark-color);
        }

        .header-actions {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.7rem 1.2rem;
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--gray-color);
            color: var(--dark-color);
        }

        .btn-outline:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .search-box input {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 3rem;
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius);
            font-size: 1rem;
            background-color: white;
            transition: var(--transition);
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-color);
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .view-options {
            display: flex;
            gap: 0.5rem;
        }

        .view-option {
            padding: 0.5rem 1rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .view-option.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .sort-options {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .sort-select {
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius);
            background: white;
            cursor: pointer;
        }

        .files-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1.5rem;
        }

        .files-list {
            display: block;
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .list-header {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            padding: 1rem 1.5rem;
            background: #f8f9fa;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
            color: var(--gray-color);
        }

        .list-item {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f1f3f4;
            transition: var(--transition);
            cursor: pointer;
            align-items: center;
        }

        .list-item:hover {
            background: #f8f9fa;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-item-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .list-file-icon {
            font-size: 1.5rem;
            width: 40px;
            text-align: center;
        }

        .list-file-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .file-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .file-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .file-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #f0f4ff;
        }

        .folder .file-icon {
            color: #ffb347;
            background: #fff5eb;
        }

        .pdf .file-icon {
            color: #e74c3c;
            background: #fdedec;
        }

        .image .file-icon {
            color: #3498db;
            background: #ebf5fb;
        }

        .document .file-icon {
            color: #2ecc71;
            background: #eafaf1;
        }

        .spreadsheet .file-icon {
            color: #9b59b6;
            background: #f4ecf7;
        }

        .archive .file-icon {
            color: #f39c12;
            background: #fef5e7;
        }

        .file-name {
            font-weight: 600;
            margin-bottom: 0.5rem;
            word-break: break-word;
            width: 100%;
        }

        .file-meta {
            font-size: 0.85rem;
            color: var(--gray-color);
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
            width: 100%;
        }

        .file-actions {
            position: absolute;
            top: 0.8rem;
            right: 0.8rem;
            opacity: 0;
            transition: var(--transition);
            display: flex;
            gap: 0.3rem;
        }

        .file-card:hover .file-actions {
            opacity: 1;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            color: var(--gray-color);
            font-size: 0.8rem;
            transition: var(--transition);
        }

        .action-btn:hover {
            background: var(--primary-color);
            color: white;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem;
            color: var(--gray-color);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .loading-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 2rem;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(67, 97, 238, 0.2);
            border-left: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Styles pour la zone d'upload */
        .upload-area {
            border: 2px dashed #e2e8f0;
            border-radius: var(--border-radius);
            padding: 2rem;
            text-align: center;
            margin-bottom: 1.5rem;
            transition: var(--transition);
            cursor: pointer;
        }

        .upload-area:hover {
            border-color: var(--primary-color);
            background: #f8f9fa;
        }

        .upload-area i {
            font-size: 3rem;
            color: var(--gray-color);
            margin-bottom: 1rem;
        }

        .file-input {
            display: none;
        }

        .file-list {
            max-height: 200px;
            overflow-y: auto;
            margin: 1rem 0;
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius);
            padding: 1rem;
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem;
            border-bottom: 1px solid #f1f3f4;
        }

        .file-item:last-child {
            border-bottom: none;
        }

        .file-item-name {
            flex: 1;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }

        .file-item-size {
            color: var(--gray-color);
            font-size: 0.9rem;
            margin-left: 1rem;
        }

        /* .upload-btn {
            width: 100%;
            margin-top: 1rem;
        } */

        .upload-btn:disabled {
            background-color: var(--gray-color);
            cursor: not-allowed;
            transform: none;
        }

        .upload-btn:disabled:hover {
            background-color: var(--gray-color);
            transform: none;
        }

        @media (max-width: 768px) {
            .files-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 1rem;
            }
            
            .file-browser-container {
                padding: 1rem;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .header-actions {
                width: 100%;
                justify-content: space-between;
            }

            .toolbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .list-header, .list-item {
                grid-template-columns: 1fr 1fr;
                gap: 0.5rem;
            }

            .list-header div:nth-child(3),
            .list-item div:nth-child(3),
            .list-header div:nth-child(4),
            .list-item div:nth-child(4) {
                display: none;
            }
        }
    /* Couleurs des boutons d'action */
    .action-btn.download-btn {
        color: #28a745 !important; /* Vert */
    }
    .action-btn.download-btn:hover {
        color: #218838 !important;
    }

    .action-btn.rename-btn {
        color: #007bff !important; /* Bleu */
    }
    .action-btn.rename-btn:hover {
        color: #0056b3 !important;
    }

    .action-btn.move-btn {
        color: #17a2b8 !important; /* Cyan */
    }
    .action-btn.move-btn:hover {
        color: #138496 !important;
    }

    .action-btn.delete-btn {
        color: #dc3545 !important; /* Rouge */
    }
    .action-btn.delete-btn:hover {
        color: #c82333 !important;
    }

    /* Styles pour le drag & drop */
    .file-card.dragging {
        opacity: 0.5;
        border: 2px dashed #007bff !important;
    }

    .file-card.drop-target,
    .list-item.drop-target {
        background-color: #e3f2fd !important;
        border: 2px dashed #2196F3 !important;
    }

    /* Arborescence des dossiers */
    .folder-tree {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .folder-item {
        padding: 8px 12px;
        margin: 2px 0;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .folder-item:hover {
        background-color: #f8f9fa;
    }

    .folder-item.selected {
        background-color: #007bff;
        color: white;
    }

    .folder-item.selected .fas {
        color: white !important;
    }
    </style>
                <!-- Onglet Dossier -->
                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                    <div class="file-browser-container"  data-dossier-id="<?php echo $dossier->id; ?>">
                        <div class="header">
                            <h1>Mes fichiers</h1>
                            <div class="header-actions">
                                <button class="btn btn-outline" data-toggle="modal" data-target="#uploadModal">
                                    <i class="fas fa-upload"></i> Importer
                                </button>
                                <button class="btn btn-primary" id="refreshFiles">
                                    <i class="fas fa-sync-alt"></i> Actualiser
                                </button>
                            </div>
                        </div>
                        <!-- Add this near your file controls -->
<div class="file-navigation mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex">
            <button id="backButton" class="btn btn-secondary btn-sm me-2" title="Retour">
                <i class="fas fa-arrow-left"></i>
            </button>
            <nav aria-label="breadcrumb">
                <ol id="breadcrumb" class="breadcrumb mb-0"></ol>
            </nav>
        </div>
        <div class="d-flex align-items-center">

                        <div class="toolbar">
                            <div class="view-options" style="margin-right:40px;">
                                <div class="view-option" data-view="grid">
                                    <i class="fas fa-th"></i> Grille
                                </div>
                                <div class="view-option active" data-view="list">
                                    <i class="fas fa-list"></i> Liste
                                </div>
                            </div>
                            <div class="sort-options" style="margin-right:40px;">
                                <span>Trier par:</span>
                                <select class="sort-select" id="sortSelect">
                                    <option value="name">Nom</option>
                                    <option value="date">Date</option>
                                    <option value="size">Taille</option>
                                    <option value="type">Type</option>
                                </select>
                            </div>
                        </div>
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchInput" placeholder="Chercher des fichiers et dossiers...">
                        </div>
        </div>
    </div>
</div>


                        <div class="files-grid" id="filesContainer">
                            <div class="loading-state">
                                <div class="spinner"></div>
                                <p>chargement des fichiers...</p>
                            </div>
                        </div>
                    </div>
                </div>

<!-- Modal d'importation AdminLTE -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">
                    <i class="fas fa-upload mr-2"></i>Importer des fichiers
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="upload-area" id="dropZone">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Glissez-déposez vos fichiers ici ou</p>
                    <button class="btn btn-outline" id="browseFiles">
                        <i class="fas fa-upload"></i> Parcourir
                    </button>
                    <input type="file" id="fileInput" class="file-input" multiple>
                </div>
                <div class="file-list" id="fileList">
                    <div class="empty-state" style="padding: 1rem;">
                        <i class="fas fa-file" style="font-size: 1.5rem;"></i>
                        <p style="font-size: 0.9rem;">Aucun fichier sélectionné</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary upload-btn" id="uploadBtn" disabled>
                    <i class="fas fa-upload"></i> Importer les fichiers
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirmation de suppression</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                    <p id="deleteConfirmationText">Êtes-vous sûr de vouloir supprimer cet élément ?</p>
                    <p class="text-muted"><small>Cette action est irréversible.</small></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de renommage -->
<div class="modal fade" id="renameModal" tabindex="-1" role="dialog" aria-labelledby="renameModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="renameModalLabel">Renommer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="renameForm">
                    <div class="form-group">
                        <label for="newName">Nouveau nom :</label>
                        <input type="text" class="form-control" id="newName" required>
                        <small class="form-text text-muted" id="renameHelpText">
                            Entrez le nouveau nom pour cet élément.
                        </small>
                        <div class="invalid-feedback" id="renameError">
                            Veuillez entrer un nom valide.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirmRenameBtn">
                    <i class="fas fa-save"></i> Renommer
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal de déplacement -->
<div class="modal fade" id="moveModal" tabindex="-1" role="dialog" aria-labelledby="moveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moveModalLabel">Déplacer l'élément</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Élément à déplacer :</h6>
                        <div id="moveItemInfo" class="p-3 border rounded mb-3">
                            <i class="fas fa-folder text-warning mr-2" id="moveItemIcon"></i>
                            <span id="moveItemName" class="font-weight-bold"></span>
                            <small class="text-muted d-block mt-1" id="moveItemType"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Dossier de destination :</h6>
                        <div id="moveFolderTree" class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            <div class="loading-state">
                                <div class="spinner spinner-sm"></div>
                                <p>Chargement des dossiers...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirmMoveBtn">
                    <i class="fas fa-arrows-alt"></i> Déplacer
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        const dossierId = {{ $dossier->id }};
        let allFiles = [];
        let currentView = 'list';
        let currentSort = 'name';
        let filesToUpload = [];
        let currentPath = '';
        let pathHistory = [];
        
        // Variables pour stocker les données de suppression
        let pendingDelete = {
            filePath: null,
            fileName: null,
            fileType: null
        };

        // Variables pour stocker les données de renommage
        let pendingRename = {
            filePath: null,
            fileName: null,
            fileType: null,
            currentName: null
        };

        // Variables pour stocker les données de déplacement
        let pendingMove = {
            filePath: null,
            fileName: null,
            fileType: null,
            targetPath: null
        };

        // Load files function with path support
        function loadFiles(path = '') {
            $('#filesContainer').html(`
                <div class="loading-state">
                    <div class="spinner"></div>
                    <p>chargement des fichiers...</p>
                </div>
            `);

            const url = path ? 
                `/dossiers/${dossierId}/files?path=${encodeURIComponent(path)}` :
                `/dossiers/${dossierId}/files`;

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(files) {
                    allFiles = files;
                    sortFiles(currentSort);
                    renderFiles(files);
                    updateBreadcrumb(path);
                    currentPath = path;
                    
                    // Initialiser le drag & drop après le rendu
                    initDragAndDrop();
                },
                error: function(xhr, status, error) {
                    $('#filesContainer').html(`
                        <div class="empty-state">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h3>Erreur de chargement</h3>
                            <p>Il y a eu un problème lors du chargement des fichiers. Veuillez réessayer.</p>
                            <button class="btn btn-primary mt-2" onclick="loadFiles('${currentPath}')">
                                <i class="fas fa-redo"></i> Réessayer
                            </button>
                        </div>
                    `);
                }
            });
        }

        // Download file using POST method
        function downloadFile(filePath, fileName) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/dossier/download';
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            const dossierInput = document.createElement('input');
            dossierInput.type = 'hidden';
            dossierInput.name = 'dossier_id';
            dossierInput.value = dossierId;
            form.appendChild(dossierInput);
            
            const filePathInput = document.createElement('input');
            filePathInput.type = 'hidden';
            filePathInput.name = 'file_path';
            filePathInput.value = filePath;
            form.appendChild(filePathInput);
            
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        // Show delete confirmation modal
        function showDeleteConfirmation(filePath, fileName, fileType, event) {
            if (event) {
                event.stopPropagation();
            }
            
            const itemType = fileType === 'folder' ? 'dossier' : 'fichier';
            const itemName = fileName;
            
            pendingDelete.filePath = filePath;
            pendingDelete.fileName = fileName;
            pendingDelete.fileType = fileType;
            
            $('#deleteConfirmationText').html(`
                Êtes-vous sûr de vouloir supprimer le ${itemType} <strong>"${itemName}"</strong> ?
            `);
            
            $('#deleteConfirmationModal').modal('show');
        }

        // Execute delete after confirmation
        function executeDelete() {
            if (!pendingDelete.filePath) return;
            
            const { filePath, fileName, fileType } = pendingDelete;
            const itemType = fileType === 'folder' ? 'dossier' : 'fichier';
            
            $.ajax({
                url: `/dossiers/${dossierId}/delete`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    file_path: filePath,
                    file_type: fileType
                },
                beforeSend: function() {
                    $('#confirmDeleteBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Suppression...');
                },
                success: function(response) {
                    $('#deleteConfirmationModal').modal('hide');
                    
                    if (response.success) {
                        loadFiles(currentPath);
                        $(document).Toasts('create', {
                            class: 'bg-success',
                            title: 'Succès',
                            body: `${itemType} supprimé avec succès!`,
                            autohide: true,
                            delay: 3000
                        });
                    } else {
                        $(document).Toasts('create', {
                            class: 'bg-danger',
                            title: 'Erreur',
                            body: response.message || 'Erreur lors de la suppression',
                            autohide: true,
                            delay: 3000
                        });
                    }
                },
                error: function(xhr, status, error) {
                    $('#deleteConfirmationModal').modal('hide');
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Erreur',
                        body: 'Erreur lors de la suppression',
                        autohide: true,
                        delay: 3000
                    });
                },
                complete: function() {
                    $('#confirmDeleteBtn').prop('disabled', false).html('<i class="fas fa-trash"></i> Supprimer');
                    pendingDelete = {
                        filePath: null,
                        fileName: null,
                        fileType: null
                    };
                }
            });
        }

        // Show rename modal
        function showRenameModal(filePath, fileName, fileType, event) {
            if (event) {
                event.stopPropagation();
            }
            
            const itemType = fileType === 'folder' ? 'dossier' : 'fichier';
            
            pendingRename.filePath = filePath;
            pendingRename.fileName = fileName;
            pendingRename.fileType = fileType;
            pendingRename.currentName = fileName;
            
            $('#renameModalLabel').text(`Renommer le ${itemType}`);
            $('#renameHelpText').text(`Entrez le nouveau nom pour le ${itemType}.`);
            
            let currentNameWithoutExt = fileName;
            if (fileType === 'file') {
                const lastDotIndex = fileName.lastIndexOf('.');
                if (lastDotIndex > 0) {
                    currentNameWithoutExt = fileName.substring(0, lastDotIndex);
                }
            }
            
            $('#newName').val(currentNameWithoutExt);
            $('#newName').removeClass('is-invalid');
            $('#renameError').hide();
            
            $('#renameModal').modal('show');
            setTimeout(() => {
                $('#newName').focus();
                $('#newName').select();
            }, 500);
        }

        // Execute rename after confirmation
        function executeRename() {
            if (!pendingRename.filePath) return;
            
            const { filePath, fileName, fileType, currentName } = pendingRename;
            const newName = $('#newName').val().trim();
            const itemType = fileType === 'folder' ? 'dossier' : 'fichier';
            
            if (!newName) {
                $('#newName').addClass('is-invalid');
                $('#renameError').text('Veuillez entrer un nom valide.').show();
                return;
            }
            
            let finalNewName = newName;
            if (fileType === 'file') {
                const extension = fileName.split('.').pop();
                finalNewName = `${newName}.${extension}`;
            }
            
            if (finalNewName === currentName) {
                $('#renameModal').modal('hide');
                return;
            }
            
            $.ajax({
                url: `/dossiers/${dossierId}/rename`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    file_path: filePath,
                    file_type: fileType,
                    new_name: finalNewName
                },
                beforeSend: function() {
                    $('#confirmRenameBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Renommage...');
                },
                success: function(response) {
                    $('#renameModal').modal('hide');
                    
                    if (response.success) {
                        loadFiles(currentPath);
                        $(document).Toasts('create', {
                            class: 'bg-success',
                            title: 'Succès',
                            body: `${itemType} renommé avec succès!`,
                            autohide: true,
                            delay: 3000
                        });
                    } else {
                        $(document).Toasts('create', {
                            class: 'bg-danger',
                            title: 'Erreur',
                            body: response.message || 'Erreur lors du renommage',
                            autohide: true,
                            delay: 3000
                        });
                    }
                },
                error: function(xhr, status, error) {
                    $('#renameModal').modal('hide');
                    let errorMessage = 'Erreur lors du renommage';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).join(', ');
                    }
                    
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Erreur',
                        body: errorMessage,
                        autohide: true,
                        delay: 5000
                    });
                },
                complete: function() {
                    $('#confirmRenameBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Renommer');
                    pendingRename = {
                        filePath: null,
                        fileName: null,
                        fileType: null,
                        currentName: null
                    };
                }
            });
        }

        // Show move modal
       // Show move modal
function showMoveModal(filePath, fileName, fileType, event) {
    if (event) {
        event.stopPropagation();
    }
    
    const itemType = fileType === 'folder' ? 'dossier' : 'fichier';
    
    pendingMove.filePath = filePath;
    pendingMove.fileName = fileName;
    pendingMove.fileType = fileType;
    pendingMove.targetPath = ''; // Racine par défaut
    
    // Mettre à jour les informations de l'élément
    $('#moveItemName').text(fileName);
    $('#moveItemType').text(itemType === 'dossier' ? 'Dossier' : 'Fichier');
    $('#moveItemIcon').attr('class', fileType === 'folder' ? 'fas fa-folder text-warning mr-2' : 'fas fa-file text-primary mr-2');
    
    // Afficher le chemin actuel
    const currentLocation = filePath.includes('/') ? `Dans : ${filePath.split('/').slice(0, -1).join('/') || 'Racine'}` : 'Dans : Racine';
    $('#moveItemInfo').append(`<small class="text-muted d-block mt-1">${currentLocation}</small>`);
    
    // Charger l'arborescence des dossiers
    loadFolderTree();
    
    $('#moveModal').modal('show');
}

        // Load folder tree for move modal
        // Load folder tree for move modal
function loadFolderTree() {
    $('#moveFolderTree').html(`
        <div class="loading-state">
            <div class="spinner spinner-sm"></div>
            <p>Chargement des dossiers...</p>
        </div>
    `);

    // Exclure le dossier source et ses sous-dossiers
    const excludePath = pendingMove.fileType === 'folder' ? pendingMove.filePath : '';

    $.ajax({
        url: `/dossiers/${dossierId}/folders`,
        type: 'GET',
        data: {
            exclude_path: excludePath
        },
        dataType: 'json',
        success: function(folders) {
            renderFolderTree(folders);
        },
        error: function() {
            $('#moveFolderTree').html(`
                <div class="text-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Erreur lors du chargement des dossiers
                </div>
            `);
        }
    });
}

        // Render folder tree
        // Render folder tree (version simplifiée)
        // Render folder tree
function renderFolderTree(folders) {
    let html = `
        <div class="folder-tree">
            <div class="folder-item root-folder" data-path="">
                <i class="fas fa-home text-primary mr-2"></i>
                <span class="font-weight-bold">Racine du dossier (déplacer ici)</span>
            </div>
    `;

    if (folders.length === 0) {
        html += `
            <div class="text-muted p-3 text-center">
                <i class="fas fa-folder-open fa-2x mb-2"></i>
                <p>Aucun autre dossier disponible</p>
            </div>
        `;
    } else {
        folders.forEach(folder => {
            const indent = '&nbsp;'.repeat(folder.level * 3);
            const displayName = folder.level > 0 ? `${indent}↳ ${folder.name}` : folder.name;
            
            html += `
                <div class="folder-item" data-path="${folder.path}">
                    <i class="fas fa-folder text-warning mr-2"></i>
                    <span>${displayName}</span>
                </div>
            `;
        });
    }

    html += '</div>';

    $('#moveFolderTree').html(html);

    // Sélectionner la racine par défaut
    $('.root-folder').addClass('selected');
    pendingMove.targetPath = '';

    // Gérer les clics sur les dossiers
    $('.folder-item').on('click', function() {
        $('.folder-item').removeClass('selected');
        $(this).addClass('selected');
        pendingMove.targetPath = $(this).data('path');
    });
}

        // Execute move after confirmation
        // Execute move after confirmation
        // Execute move after confirmation - VERSION AVEC DEBUG
function executeMove() {
    if (pendingMove.targetPath === undefined) {
        $(document).Toasts('create', {
            class: 'bg-warning',
            title: 'Attention',
            body: 'Veuillez sélectionner un dossier de destination',
            autohide: true,
            delay: 3000
        });
        return;
    }
    
    const { filePath, fileName, fileType, targetPath } = pendingMove;
    const itemType = fileType === 'folder' ? 'dossier' : 'fichier';
    
    // DEBUG: Afficher les valeurs
    console.log('=== DEBUG DÉPLACEMENT ===');
    console.log('filePath:', filePath);
    console.log('fileName:', fileName);
    console.log('fileType:', fileType);
    console.log('targetPath:', targetPath);
    console.log('targetPath type:', typeof targetPath);
    console.log('targetPath === "":', targetPath === '');
    console.log('targetPath == null:', targetPath == null);
    console.log('========================');
    
    // Vérifier qu'on ne déplace pas un dossier dans lui-même
    if (fileType === 'folder' && targetPath && targetPath.startsWith(filePath)) {
        $(document).Toasts('create', {
            class: 'bg-warning',
            title: 'Action impossible',
            body: 'Vous ne pouvez pas déplacer un dossier dans lui-même ou dans un de ses sous-dossiers',
            autohide: true,
            delay: 4000
        });
        return;
    }
    
    // Préparer les données - CORRECTION EXPLICITE POUR LA RACINE
    const moveData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        file_path: filePath,
        file_type: fileType,
        target_path: targetPath === '' ? '' : (targetPath || '') // Force une chaîne vide pour la racine
    };
    
    console.log('Données envoyées:', moveData);
    
    $.ajax({
        url: `/dossiers/${dossierId}/move`,
        type: 'POST',
        data: moveData,
        beforeSend: function() {
            $('#confirmMoveBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Déplacement...');
        },
        success: function(response) {
            $('#moveModal').modal('hide');
            
            if (response.success) {
                loadFiles(currentPath);
                const destination = targetPath ? `vers ${targetPath}` : 'vers la racine';
                $(document).Toasts('create', {
                    class: 'bg-success',
                    title: 'Succès',
                    body: `${itemType} déplacé avec succès ${destination}!`,
                    autohide: true,
                    delay: 3000
                });
            } else {
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Erreur',
                    body: response.message || 'Erreur lors du déplacement',
                    autohide: true,
                    delay: 3000
                });
            }
        },
        error: function(xhr, status, error) {
            $('#moveModal').modal('hide');
            let errorMessage = 'Erreur lors du déplacement';
            
            console.log('Erreur AJAX:', xhr.responseJSON);
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).join(', ');
            }
            
            $(document).Toasts('create', {
                class: 'bg-danger',
                title: 'Erreur',
                body: errorMessage,
                autohide: true,
                delay: 5000
            });
        },
        complete: function() {
            $('#confirmMoveBtn').prop('disabled', false).html('<i class="fas fa-arrows-alt"></i> Déplacer');
            pendingMove = {
                filePath: null,
                fileName: null,
                fileType: null,
                targetPath: null
            };
        }
    });
}


        // Initialize drag and drop
        function initDragAndDrop() {
            $('.file-card, .list-item').each(function() {
                const element = $(this);
                const fileType = element.data('file-type');
                
                if (fileType === 'file' || fileType === 'folder') {
                    element.attr('draggable', 'true');
                    
                    element.on('dragstart', function(e) {
                        e.originalEvent.dataTransfer.setData('text/plain', JSON.stringify({
                            filePath: element.find('.download-btn, .rename-btn, .delete-btn, .move-btn').first().attr('onclick').match(/'([^']+)'/)[1],
                            fileName: element.find('.file-name').text(),
                            fileType: fileType
                        }));
                        element.addClass('dragging');
                    });
                    
                    element.on('dragend', function() {
                        element.removeClass('dragging');
                    });
                }
            });

            // Gérer le drop sur les dossiers
            $('.file-card[data-file-type="folder"], .list-item[onclick*="handleFolderClick"]').on('dragover', function(e) {
                e.preventDefault();
                e.originalEvent.dataTransfer.dropEffect = 'move';
                $(this).addClass('drop-target');
            });
            
            $('.file-card[data-file-type="folder"], .list-item[onclick*="handleFolderClick"]').on('dragleave', function() {
                $(this).removeClass('drop-target');
            });
            
            $('.file-card[data-file-type="folder"], .list-item[onclick*="handleFolderClick"]').on('drop', function(e) {
                e.preventDefault();
                $(this).removeClass('drop-target');
                
                try {
                    const dragData = JSON.parse(e.originalEvent.dataTransfer.getData('text/plain'));
                    const targetFolderPath = $(this).find('.download-btn, .rename-btn, .delete-btn, .move-btn').first().attr('onclick').match(/'([^']+)'/)[1];
                    
                    if (dragData.filePath !== targetFolderPath) {
                        pendingMove.filePath = dragData.filePath;
                        pendingMove.fileName = dragData.fileName;
                        pendingMove.fileType = dragData.fileType;
                        pendingMove.targetPath = targetFolderPath;
                        
                        // Confirmation rapide pour le drag & drop
                        if (confirm(`Déplacer "${dragData.fileName}" vers ce dossier ?`)) {
                            executeMove();
                        }
                    }
                } catch (error) {
                    console.error('Erreur lors du drop:', error);
                }
            });
        }

        // Update breadcrumb navigation
        function updateBreadcrumb(path) {
            const breadcrumb = $('#breadcrumb');
            breadcrumb.empty();

            breadcrumb.append(`
                <li class="breadcrumb-item">
                    <a href="javascript:void(0)" class="breadcrumb-link" data-path="">
                        <i class="fas fa-home"></i> Racine
                    </a>
                </li>
            `);

            if (path) {
                const pathParts = path.split('/');
                let currentPath = '';

                pathParts.forEach((part, index) => {
                    currentPath += (currentPath ? '/' : '') + part;
                    const isLast = index === pathParts.length - 1;

                    breadcrumb.append(`
                        <li class="breadcrumb-item ${isLast ? 'active' : ''}">
                            ${isLast ? 
                                part : 
                                `<a href="javascript:void(0)" class="breadcrumb-link" data-path="${currentPath}">${part}</a>`
                            }
                        </li>
                    `);
                });
            }

            $('.breadcrumb-link').on('click', function() {
                const path = $(this).data('path');
                loadFiles(path);
            });
        }

        // Handle folder click
        function handleFolderClick(folderPath, folderName) {
            pathHistory.push(currentPath);
            loadFiles(folderPath);
        }

        // Go back to previous folder
        function goBack() {
            if (pathHistory.length > 0) {
                const previousPath = pathHistory.pop();
                loadFiles(previousPath);
            } else {
                loadFiles('');
            }
        }

        // Sort files
        function sortFiles(sortBy) {
            currentSort = sortBy;
            allFiles.sort((a, b) => {
                if (a.type === 'folder' && b.type !== 'folder') return -1;
                if (a.type !== 'folder' && b.type === 'folder') return 1;

                switch(sortBy) {
                    case 'name':
                        return a.name.localeCompare(b.name);
                    case 'date':
                        return b.last_modified - a.last_modified;
                    case 'size':
                        return b.size - a.size;
                    case 'type':
                        if (a.type === b.type) {
                            return a.name.localeCompare(b.name);
                        }
                        return a.type.localeCompare(b.type);
                    default:
                        return 0;
                }
            });
        }

        // Render files based on current view
        function renderFiles(files) {
            if (files.length === 0) {
                $('#filesContainer').html(`
                    <div class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <h3>Aucun fichier trouvé</h3>
                        <p>Ce dossier est vide. Merci de charger des fichiers</p>
                    </div>
                `);
                return;
            }

            if (currentView === 'grid') {
                renderGridView(files);
                $('#filesContainer').addClass('files-grid').removeClass('files-list');
            } else {
                renderListView(files);
                $('#filesContainer').addClass('files-list').removeClass('files-grid');
            }
        }

        // Render grid view
        function renderGridView(files) {
            let html = '';
            files.forEach(function(file) {
                const fileName = file.name;
                const fileType = file.type;
                const fileSize = formatFileSize(file.size);
                const lastModified = new Date(file.last_modified * 1000).toLocaleDateString();
                const fileExtension = file.extension.toLowerCase();
                const filePath = file.path;
                
                let fileCategory = 'file';
                if (fileType === 'folder') {
                    fileCategory = 'folder';
                } else if (['pdf'].includes(fileExtension)) {
                    fileCategory = 'pdf';
                } else if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'].includes(fileExtension)) {
                    fileCategory = 'image';
                } else if (['doc', 'docx', 'txt', 'rtf'].includes(fileExtension)) {
                    fileCategory = 'document';
                } else if (['xls', 'xlsx', 'csv'].includes(fileExtension)) {
                    fileCategory = 'spreadsheet';
                } else if (['zip', 'rar', '7z', 'tar'].includes(fileExtension)) {
                    fileCategory = 'archive';
                }

                const icon = getFileIcon(fileType, fileExtension);

                const safeFilePath = filePath.replace(/'/g, "\\'");
                const safeFileName = fileName.replace(/'/g, "\\'");

                html += `
                    <div class="file-card ${fileCategory}" data-file-type="${fileType}" 
                         ${fileType === 'folder' ? `onclick="handleFolderClick('${safeFilePath}', '${safeFileName}')" style="cursor: pointer;"` : ''}>
                        <div class="file-actions">
                            ${fileType === 'file' ? 
                                `<button type="button" class="action-btn download-btn text-success" onclick="downloadFile('${safeFilePath}', '${safeFileName}')" title="Télécharger">
                                    <i class="fas fa-download"></i>
                                </button>` : 
                                ''
                            }
                            <button type="button" class="action-btn rename-btn text-primary" onclick="showRenameModal('${safeFilePath}', '${safeFileName}', '${fileType}', event)" title="Renommer">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="action-btn move-btn text-info" onclick="showMoveModal('${safeFilePath}', '${safeFileName}', '${fileType}', event)" title="Déplacer">
                                <i class="fas fa-arrows-alt"></i>
                            </button>
                            <button type="button" class="action-btn delete-btn text-danger" onclick="showDeleteConfirmation('${safeFilePath}', '${safeFileName}', '${fileType}', event)" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="file-icon">
                            ${icon}
                        </div>
                        <div class="file-name">${fileName}</div>
                        <div class="file-meta">
                            <span>${fileType === 'folder' ? 'Dossier' : fileExtension.toUpperCase()}</span>
                            ${fileType === 'file' ? `<span>${fileSize}</span>` : ''}
                            <span>${lastModified}</span>
                        </div>
                    </div>
                `;
            });
            
            $('#filesContainer').html(html);
        }

        // Render list view
        function renderListView(files) {
            let html = `
                <div class="files-list">
                    <div class="list-header">
                        <div>Nom</div>
                        <div>Taille</div>
                        <div>Type</div>
                        <div>Actions</div>
                    </div>
            `;

            files.forEach(function(file) {
                const fileName = file.name;
                const fileType = file.type;
                const fileSize = file.type === 'folder' ? '-' : formatFileSize(file.size);
                const lastModified = new Date(file.last_modified * 1000).toLocaleDateString();
                const fileExtension = file.extension.toLowerCase();
                const filePath = file.path;
                
                const icon = getFileIcon(fileType, fileExtension);

                const safeFilePath = filePath.replace(/'/g, "\\'");
                const safeFileName = fileName.replace(/'/g, "\\'");

                const folderClick = fileType === 'folder' ? `onclick="handleFolderClick('${safeFilePath}', '${safeFileName}')" style="cursor: pointer;"` : '';

                html += `
                    <div class="list-item" ${folderClick}>
                        <div class="list-item-content">
                            <div class="list-file-icon ${fileType === 'folder' ? 'text-warning' : (fileExtension === 'docx' ? 'text-success' : (fileExtension === 'xlsx' ? 'text-danger' : 'text-info'))}">${icon}</div>
                            <div>
                                <div class="file-name">${fileName}</div>
                                <div class="file-meta">${lastModified}</div>
                            </div>
                        </div>
                        <div>${fileSize}</div>
                        <div>${fileType === 'folder' ? 'Dossier' : fileExtension.toUpperCase()}</div>
                        <div class="list-file-actions">
                            ${fileType === 'file' ? 
                                `<button type="button" class="action-btn download-btn text-success" onclick="downloadFile('${safeFilePath}', '${safeFileName}')" title="Télécharger">
                                    <i class="fas fa-download"></i>
                                </button>` : 
                                ''
                            }
                            <button type="button" class="action-btn rename-btn text-primary" onclick="showRenameModal('${safeFilePath}', '${safeFileName}', '${fileType}', event)" title="Renommer">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="action-btn move-btn text-info" onclick="showMoveModal('${safeFilePath}', '${safeFileName}', '${fileType}', event)" title="Déplacer">
                                <i class="fas fa-arrows-alt"></i>
                            </button>
                            <button type="button" class="action-btn delete-btn text-danger" onclick="showDeleteConfirmation('${safeFilePath}', '${safeFileName}', '${fileType}', event)" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            $('#filesContainer').html(html);
        }

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Get file icon based on type and extension
        function getFileIcon(type, extension) {
            if (type === 'folder') {
                return '<i class="fas fa-folder"></i>';
            }
            
            const iconMap = {
                'pdf': 'fa-file-pdf',
                'doc': 'fa-file-word',
                'docx': 'fa-file-word',
                'xls': 'fa-file-excel',
                'xlsx': 'fa-file-excel',
                'jpg': 'fa-file-image',
                'jpeg': 'fa-file-image',
                'png': 'fa-file-image',
                'gif': 'fa-file-image',
                'zip': 'fa-file-archive',
                'rar': 'fa-file-archive',
                'txt': 'fa-file-alt',
                'mp4': 'fa-file-video',
                'mp3': 'fa-file-audio'
            };
            
            const iconClass = iconMap[extension] || 'fa-file';
            return `<i class="fas ${iconClass}"></i>`;
        }

        // Event handlers for modal buttons
        $('#confirmDeleteBtn').on('click', function() {
            executeDelete();
        });

        $('#confirmRenameBtn').on('click', function() {
            executeRename();
        });

        $('#confirmMoveBtn').on('click', function() {
            executeMove();
        });

        // Enter key support for rename modal
        $('#newName').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                executeRename();
            }
        });

        // Reset pending actions when modals are closed
        $('#deleteConfirmationModal').on('hidden.bs.modal', function () {
            pendingDelete = {
                filePath: null,
                fileName: null,
                fileType: null
            };
            $('#confirmDeleteBtn').prop('disabled', false).html('<i class="fas fa-trash"></i> Supprimer');
        });

        $('#renameModal').on('hidden.bs.modal', function () {
            pendingRename = {
                filePath: null,
                fileName: null,
                fileType: null,
                currentName: null
            };
            $('#confirmRenameBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Renommer');
            $('#newName').removeClass('is-invalid');
            $('#renameError').hide();
        });

        $('#moveModal').on('hidden.bs.modal', function () {
            pendingMove = {
                filePath: null,
                fileName: null,
                fileType: null,
                targetPath: null
            };
            $('#confirmMoveBtn').prop('disabled', false).html('<i class="fas fa-arrows-alt"></i> Déplacer');
        });

        // Search functionality
        $('#searchInput').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            if (searchTerm === '') {
                renderFiles(allFiles);
            } else {
                const filteredFiles = allFiles.filter(file => 
                    file.name.toLowerCase().includes(searchTerm)
                );
                renderFiles(filteredFiles);
            }
        });

        // View options
        $('.view-option').on('click', function() {
            $('.view-option').removeClass('active');
            $(this).addClass('active');
            currentView = $(this).data('view');
            renderFiles(allFiles);
        });

        // Sort functionality
        $('#sortSelect').on('change', function() {
            sortFiles($(this).val());
            renderFiles(allFiles);
        });

        // Refresh button
        $('#refreshFiles').on('click', function() {
            const $icon = $(this).find('i');
            $icon.addClass('fa-spin');
            loadFiles(currentPath);
            setTimeout(() => {
                $icon.removeClass('fa-spin');
            }, 1000);
        });

        // Back button
        $('#backButton').on('click', function() {
            goBack();
        });

        // Modal functionality avec AdminLTE
        $('#browseFiles').on('click', function() {
            $('#fileInput').click();
        });

        // File input change
        $('#fileInput').on('change', function(e) {
            handleFiles(e.target.files);
        });

        // Drag and drop functionality
        $('#dropZone').on('dragover', function(e) {
            e.preventDefault();
            $(this).css('border-color', '#4361ee');
            $(this).css('background-color', '#f0f4ff');
        });

        $('#dropZone').on('dragleave', function(e) {
            e.preventDefault();
            $(this).css('border-color', '#e2e8f0');
            $(this).css('background-color', 'transparent');
        });

        $('#dropZone').on('drop', function(e) {
            e.preventDefault();
            $(this).css('border-color', '#e2e8f0');
            $(this).css('background-color', 'transparent');
            handleFiles(e.originalEvent.dataTransfer.files);
        });

        // Handle selected files
        function handleFiles(fileList) {
            filesToUpload = Array.from(fileList);
            updateFileList();
            $('#uploadBtn').prop('disabled', false);
        }

        // Update file list in modal
        function updateFileList() {
            const fileList = $('#fileList');
            fileList.empty();

            if (filesToUpload.length === 0) {
                fileList.html(`
                    <div class="empty-state" style="padding: 1rem;">
                        <i class="fas fa-file" style="font-size: 1.5rem;"></i>
                        <p style="font-size: 0.9rem;">Aucun fichier sélectionné</p>
                    </div>
                `);
                return;
            }

            filesToUpload.forEach((file, index) => {
                const fileItem = `
                    <div class="file-item">
                        <div class="file-item-name">${file.name}</div>
                        <div class="file-item-size">${formatFileSize(file.size)}</div>
                    </div>
                `;
                fileList.append(fileItem);
            });
        }

        // Reset upload form
        function resetUploadForm() {
            filesToUpload = [];
            $('#fileList').empty();
            $('#fileInput').val('');
            $('#uploadBtn').prop('disabled', true);
            updateFileList();
        }

        // Upload files with current path
        $('#uploadBtn').on('click', function() {
            if (filesToUpload.length === 0) return;

            const formData = new FormData();
            filesToUpload.forEach(file => {
                formData.append('files[]', file);
            });

            if (currentPath) {
                formData.append('path', currentPath);
            }

            $('#uploadBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Importation...');

            $.ajax({
                url: `/dossiers/${dossierId}/upload`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#uploadModal').modal('hide');
                    resetUploadForm();
                    loadFiles(currentPath);
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Succès',
                        body: 'Fichiers importés avec succès!',
                        autohide: true,
                        delay: 3000
                    });
                },
                error: function(xhr, status, error) {
                    $('#uploadBtn').prop('disabled', false).html('<i class="fas fa-upload"></i> Importer les fichiers');
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Erreur',
                        body: 'Erreur lors de l\'importation des fichiers',
                        autohide: true,
                        delay: 3000
                    });
                }
            });
        });

        // Reset form when modal is closed
        $('#uploadModal').on('hidden.bs.modal', function () {
            resetUploadForm();
        });

        // Load files when files tab is shown
        $('#files-tab').on('click', function() {
            if ($('#filesContainer').is(':empty') || allFiles.length === 0) {
                loadFiles(currentPath);
            }
        });

        // Auto-load files if files tab is active on page load
        if ($('#files').hasClass('show')) {
            loadFiles();
        }

        // Make functions globally available
        window.handleFolderClick = handleFolderClick;
        window.goBack = goBack;
        window.downloadFile = downloadFile;
        window.showDeleteConfirmation = showDeleteConfirmation;
        window.showRenameModal = showRenameModal;
        window.showMoveModal = showMoveModal;
    });
</script>