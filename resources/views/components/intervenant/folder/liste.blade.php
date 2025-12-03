
    <style>
/* Dropdown */
#fileTypeDropdown {
    position: absolute;
    list-style: none;
    padding: 0;
    margin: 0;
    border: 1px solid #ccc;
    background: #fff;
    z-index: 10;
    min-width: 150px;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s ease, visibility 0.2s ease;
}

#fileTypeDropdown.show {
    opacity: 1 !important;
    visibility: visible !important;
    display: block !important;
}

/* Dropdown items */
.dropdown-item {
    display: block;
    padding: 5px 10px;
    text-decoration: none;
    color: #000;
}

.dropdown-item:hover {
    background-color: #f0f0f0;
}

/* Modal */
#createFileModal {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    opacity: 0;
    visibility: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: opacity 0.2s ease, visibility 0.2s ease;
    z-index: 100;
}

#createFileModal.show {
    opacity: 1;
    visibility: visible;
}

/* Modal content */
#createFileModal .modal-content {
    background: #fff;
    max-width: 400px;
    width: 90%;
    padding: 20px;
    border-radius: 5px;
}
</style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- AdminLTE 3 -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/drive.css') }}">
  
                <!-- Onglet Dossier -->
                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                    <div class="file-browser-container"  data-intervenant-id="<?php echo $intervenant->id; ?>">
                        <div class="header">
                            <h1>Mes fichiers</h1>
                            <div class="header-actions">
                                <button class="btn btn-outline" data-toggle="modal" data-target="#uploadModal">
                                    <i class="fas fa-upload"></i> Importer
                                </button>
                                
                <input type="file" id="folderInput" webkitdirectory multiple style="display: none;">

<!-- Mettez à jour votre bouton ou ajoutez un nouveau bouton -->
<button type="button" class="btn btn-info d-none" id="uploadFolderBtn">
    <i class="fas fa-folder-upload"></i> Uploader un dossier
</button>
<div class="dropdown" style="position: relative; display: inline-block;">
    <button id="createFileBtn" class="btn btn-primary">Créer un fichier ▾</button>
    <ul id="fileTypeDropdown" class="dropdown-menu">
        <li><a href="#" class="dropdown-item" data-file-type="docx"><i class="fas fa-file-word text-primary"></i> Word (.docx)</a></li>
        <li><a href="#" class="dropdown-item" data-file-type="xlsx"><i class="fas fa-file-excel text-success"></i> Excel (.xlsx)</a></li>
        <li><a href="#" class="dropdown-item" data-file-type="pptx"><i class="fas fa-file-powerpoint text-warning"></i> PowerPoint (.pptx)</a></li>
    </ul>
</div>


                                <!-- Ajoutez ce bouton près de vos autres boutons de contrôle -->
<button type="button" class="btn btn-primary" id="createFolderBtn">
    <i class="fas fa-folder-plus"></i> Nouveau dossier
</button>
                                <button class="btn btn-success" id="refreshFiles">
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
<!-- Modal de création de dossier -->
<div class="modal fade" id="createFolderModal" tabindex="-1" role="dialog" aria-labelledby="createFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createFolderModalLabel">Créer un nouveau dossier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createFolderForm">
                    <div class="form-group">
                        <label for="folderName">Nom du dossier :</label>
                        <input type="text" class="form-control" id="folderName" placeholder="Entrez le nom du dossier" required>
                        <small class="form-text text-muted">
                            Le dossier sera créé dans : <span id="currentFolderLocation">Racine</span>
                        </small>
                        <div class="invalid-feedback" id="folderNameError">
                            Veuillez entrer un nom de dossier valide.
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" id="confirmCreateFolderBtn">
                    <i class="fas fa-folder-plus"></i> Créer le dossier
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Custom Modal -->
<div id="createFileModal">
    <div class="modal-content">
        <h5>Créer un fichier</h5>
        <input type="hidden" id="file_type">
        <input type="hidden" id="intervenant_id" value="{{ $intervenant->id }}">
        <div class="mb-3">
            <label for="file_name">Nom du fichier</label>
            <input type="text" id="file_name" class="form-control" placeholder="Ex: Nouveau fichier">
        </div>
        <div style="text-align:right;">
            <button id="cancelBtn" class="btn btn-secondary">Annuler</button>
            <button id="submitBtn" class="btn btn-primary">Créer</button>
        </div>
    </div>
</div>
<!-- Modal pour l'upload de dossier -->
<div class="modal fade" id="uploadFolderModal" tabindex="-1" role="dialog" aria-labelledby="uploadFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadFolderModalLabel">
                    <i class="fas fa-folder-upload"></i> Uploader un dossier
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Zone de dépôt -->
                <div id="folderDropZone" class="drop-zone folder-drop-zone">
                    <div class="drop-zone-content">
                        <i class="fas fa-folder-plus fa-3x text-primary mb-3"></i>
                        <h4>Glissez-déposez un dossier ici</h4>
                        <p class="text-muted">ou</p>
                        <button type="button" class="btn btn-primary" id="browseFolderBtn">
                            <i class="fas fa-folder-open"></i> Parcourir les dossiers
                        </button>
                        <p class="text-muted mt-2">
                            <small>Le dossier et toute son arborescence seront importés</small>
                        </p>
                    </div>
                </div>

                <!-- Informations du dossier sélectionné -->
                <div id="folderInfo" class="folder-info" style="display: none;">
                    <div class="card mt-3">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-folder text-warning"></i>
                                Dossier sélectionné
                            </h6>
                            <div id="folderDetails"></div>
                        </div>
                    </div>
                </div>

                <!-- Animation de chargement -->
                <div id="folderUploadProgress" class="upload-progress" style="display: none;">
                    <div class="progress-container">
                        <div class="progress-bar-container">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%"></div>
                            </div>
                            <div class="progress-text">
                                <span id="progressPercentage">0%</span>
                            </div>
                        </div>
                        
                        <div class="upload-details mt-3">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <div class="stat-item">
                                        <i class="fas fa-folder text-warning"></i>
                                        <div class="stat-number" id="foldersCount">0</div>
                                        <div class="stat-label">Dossiers</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-item">
                                        <i class="fas fa-file text-info"></i>
                                        <div class="stat-number" id="filesCount">0</div>
                                        <div class="stat-label">Fichiers</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="stat-item">
                                        <i class="fas fa-hdd text-success"></i>
                                        <div class="stat-number" id="totalSize">0 MB</div>
                                        <div class="stat-label">Taille totale</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="current-file mt-3">
                            <small class="text-muted">
                                <i class="fas fa-sync fa-spin"></i>
                                Traitement en cours: <span id="currentFileName">-</span>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Input caché pour les dossiers -->
                <input type="file" id="folderInput" webkitdirectory multiple style="display: none;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" id="confirmFolderUploadBtn" style="display: none;">
                    <i class="fas fa-upload"></i> Uploader le dossier
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        const intervenantId = {{ $intervenant->id }};
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

        // Function to handle folder upload
        function handleFolderUpload(files) {
            const folder = files[0];
            
            if (!folder) return;

            // Vérifier si c'est un dossier (webkitRelativePath existe pour les dossiers)
            if (!folder.webkitRelativePath) {
                alert('Veuillez sélectionner un dossier, pas des fichiers individuels.');
                return;
            }

            // Afficher un indicateur de chargement
            $('#uploadBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Traitement du dossier...');

            // Construire la structure du dossier
            buildFolderStructure(files).then(folderStructure => {
                // Envoyer la structure au serveur
                uploadFolderStructure(folderStructure);
            }).catch(error => {
                console.error('Erreur construction dossier:', error);
                $('#uploadBtn').prop('disabled', false).html('<i class="fas fa-upload"></i> Importer les fichiers');
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    title: 'Erreur',
                    body: 'Erreur lors du traitement du dossier',
                    autohide: true,
                    delay: 3000
                });
            });
        }

        // Build folder structure from FileList
        function buildFolderStructure(files) {
            return new Promise(async (resolve, reject) => {
                try {
                    const folderStructure = {
                        name: getFolderName(files),
                        files: [],
                        folders: []
                    };

                    // Organiser les fichiers par chemin
                    const pathMap = {};

                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        const relativePath = file.webkitRelativePath;
                        const pathParts = relativePath.split('/');
                        
                        // Le premier élément est le nom du dossier racine
                        const fileName = pathParts.pop();
                        const folderPath = pathParts.join('/');

                        if (!pathMap[folderPath]) {
                            pathMap[folderPath] = [];
                        }
                        
                        pathMap[folderPath].push({
                            file: file,
                            name: fileName,
                            path: folderPath
                        });
                    }

                    // Traiter le dossier racine
                    await processFolder('', pathMap, folderStructure);

                    resolve(folderStructure);
                } catch (error) {
                    reject(error);
                }
            });
        }

        // Process folder recursively
        async function processFolder(currentPath, pathMap, parentFolder) {
            const currentFiles = pathMap[currentPath] || [];

            // Traiter les fichiers du dossier courant
            for (const item of currentFiles) {
                const fileData = await readFileAsBase64(item.file);
                parentFolder.files.push({
                    name: item.name,
                    type: item.file.type,
                    size: item.file.size,
                    content: fileData
                });
            }

            // Trouver les sous-dossiers
            const subFolders = {};
            Object.keys(pathMap).forEach(path => {
                if (path.startsWith(currentPath) && path !== currentPath) {
                    const remainingPath = path.substring(currentPath ? currentPath.length + 1 : 0);
                    const nextSegment = remainingPath.split('/')[0];
                    
                    if (nextSegment && !subFolders[nextSegment]) {
                        subFolders[nextSegment] = path;
                    }
                }
            });

            // Traiter les sous-dossiers
            for (const [folderName, folderPath] of Object.entries(subFolders)) {
                const subFolder = {
                    name: folderName,
                    files: [],
                    folders: []
                };
                
                parentFolder.folders.push(subFolder);
                await processFolder(folderPath, pathMap, subFolder);
            }
        }

        // Read file as base64
        function readFileAsBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(file);
            });
        }

        // Get folder name from FileList
        function getFolderName(files) {
            if (files.length > 0 && files[0].webkitRelativePath) {
                return files[0].webkitRelativePath.split('/')[0];
            }
            return 'Nouveau_Dossier_' + Date.now();
        }

        // Upload folder structure to server
        function uploadFolderStructure(folderStructure) {
            $.ajax({
                url: `/intervenants/${intervenantId}/upload-folder`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    folder: folderStructure,
                    path: currentPath
                },
                beforeSend: function() {
                    $('#uploadBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Upload du dossier...');
                },
                success: function(response) {
                    $('#uploadModal').modal('hide');
                    resetUploadForm();
                    
                    if (response.success) {
                        loadFiles(currentPath);
                        $(document).Toasts('create', {
                            class: 'bg-success',
                            title: 'Succès',
                            body: `Dossier "${response.folder_name}" uploadé avec ${response.count} éléments!`,
                            autohide: true,
                            delay: 4000
                        });
                    } else {
                        $(document).Toasts('create', {
                            class: 'bg-danger',
                            title: 'Erreur',
                            body: response.message || 'Erreur lors de l\'upload du dossier',
                            autohide: true,
                            delay: 3000
                        });
                    }
                },
                error: function(xhr, status, error) {
                    $('#uploadBtn').prop('disabled', false).html('<i class="fas fa-upload"></i> Importer les fichiers');
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Erreur',
                        body: 'Erreur lors de l\'upload du dossier',
                        autohide: true,
                        delay: 3000
                    });
                }
            });
        }

        // Load files function with path support
        function loadFiles(path = '') {
            $('#filesContainer').html(`
                <div class="loading-state">
                    <div class="spinner"></div>
                    <p>chargement des fichiers...</p>
                </div>
            `);

            const url = path ? 
                `/intervenants/${intervenantId}/files?path=${encodeURIComponent(path)}` :
                `/intervenants/${intervenantId}/files`;

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

        // Show create folder modal
        function showCreateFolderModal() {
            // Mettre à jour l'emplacement actuel
            const locationText = currentPath ? `Dossier : ${currentPath}` : 'Racine du dossier';
            $('#currentFolderLocation').text(locationText);
            
            // Réinitialiser le formulaire
            $('#folderName').val('');
            $('#folderName').removeClass('is-invalid');
            $('#folderNameError').hide();
            
            // Afficher la modal et focus sur le champ
            $('#createFolderModal').modal('show');
            setTimeout(() => {
                $('#folderName').focus();
            }, 500);
        }

        // Create new folder
        function createFolder() {
            const folderName = $('#folderName').val().trim();
            
            // Validation
            if (!folderName) {
                $('#folderName').addClass('is-invalid');
                $('#folderNameError').text('Veuillez entrer un nom de dossier.').show();
                return;
            }
            
            // Validation des caractères interdits
            if (/[<>:"\/\\|?*]/.test(folderName)) {
                $('#folderName').addClass('is-invalid');
                $('#folderNameError').text('Le nom contient des caractères non autorisés: < > : " / \\ | ? *').show();
                return;
            }
            
            // Vérifier la longueur
            if (folderName.length > 255) {
                $('#folderName').addClass('is-invalid');
                $('#folderNameError').text('Le nom du dossier est trop long (max 255 caractères).').show();
                return;
            }

            $.ajax({
                url: `/intervenants/${intervenantId}/create-folder`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    folder_name: folderName,
                    path: currentPath // Le chemin actuel où créer le dossier
                },
                beforeSend: function() {
                    $('#confirmCreateFolderBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Création...');
                },
                success: function(response) {
                    $('#createFolderModal').modal('hide');
                    
                    if (response.success) {
                        loadFiles(currentPath);
                        $(document).Toasts('create', {
                            class: 'bg-success',
                            title: 'Succès',
                            body: 'Dossier créé avec succès!',
                            autohide: true,
                            delay: 3000
                        });
                    } else {
                        $(document).Toasts('create', {
                            class: 'bg-danger',
                            title: 'Erreur',
                            body: response.message || 'Erreur lors de la création du dossier',
                            autohide: true,
                            delay: 3000
                        });
                    }
                },
                error: function(xhr, status, error) {
                    $('#createFolderModal').modal('hide');
                    let errorMessage = 'Erreur lors de la création du dossier';
                    
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
                    $('#confirmCreateFolderBtn').prop('disabled', false).html('<i class="fas fa-folder-plus"></i> Créer le dossier');
                }
            });
        }

        // View File using post method and new tab target
        function previewFile(filePath, fileName) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/intervenant/view';  
            form.target = '_blank'; // Ouvrir dans un nouvel onglet

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            const filePathInput = document.createElement('input');
            filePathInput.type = 'hidden';
            filePathInput.name = 'file_path';
            filePathInput.value = filePath;
            form.appendChild(filePathInput);

            const fileNameInput = document.createElement('input');
            fileNameInput.type = 'hidden';
            fileNameInput.name = 'file_name';
            fileNameInput.value = fileName;
            form.appendChild(fileNameInput);
            const intervenantInput = document.createElement('input');
            intervenantInput.type = 'hidden';
            intervenantInput.name = 'intervenant_id';
            intervenantInput.value = intervenantId;
            form.appendChild(intervenantInput);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        
        // View File using post method and new tab target
        function previewFileChrome(filePath, fileName) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/intervenant/view/chrome';  
            form.target = '_blank'; // Ouvrir dans un nouvel onglet

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            const filePathInput = document.createElement('input');
            filePathInput.type = 'hidden';
            filePathInput.name = 'file_path';
            filePathInput.value = filePath;
            form.appendChild(filePathInput);

            const fileNameInput = document.createElement('input');
            fileNameInput.type = 'hidden';
            fileNameInput.name = 'file_name';
            fileNameInput.value = fileName;
            form.appendChild(fileNameInput);
            const intervenantInput = document.createElement('input');
            intervenantInput.type = 'hidden';
            intervenantInput.name = 'intervenant_id';
            intervenantInput.value = intervenantId;
            form.appendChild(intervenantInput);
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        // Download file using POST method
        function downloadFile(filePath, fileName) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/intervenant/download';
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            const intervenantInput = document.createElement('input');
            intervenantInput.type = 'hidden';
            intervenantInput.name = 'intervenant_id';
            intervenantInput.value = intervenantId;
            form.appendChild(intervenantInput);
            
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
                url: `/intervenants/${intervenantId}/delete`,
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
                url: `/intervenants/${intervenantId}/rename`,
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
        function showMoveModal(filePath, fileName, fileType, event) {
            if (event) {
                event.stopPropagation();
            }
            
            const itemType = fileType === 'folder' ? 'dossier' : 'fichier';
            
            pendingMove.filePath = filePath;
            pendingMove.fileName = fileName;
            pendingMove.fileType = fileType;
            pendingMove.targetPath = '';
            
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
                url: `/intervenants/${intervenantId}/folders`,
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
            
            // Préparer les données
            const moveData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                file_path: filePath,
                file_type: fileType,
                target_path: targetPath === '' ? '' : (targetPath || '')
            };
            
            $.ajax({
                url: `/intervenants/${intervenantId}/move`,
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
      // Render grid view
function renderGridView(files) {
    let html = '';

    files.forEach(function(file) {
        const fileName = file.name;
        const fileType = file.type;
        const originalPath = file.original_path;
        const fileSize = file.type === 'folder' ? '-' : formatFileSize(file.size);
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

        // Logique de lien pour les fichiers (identique à renderListView)
        let fileNameDisplay = fileName;
        if (fileType === 'file') {
            let fileUrl = '';
            
            if (fileExtension === 'docx' || fileExtension === 'doc') {
                fileUrl = `ms-word:ofe|u|file:///P:/${originalPath}/${fileName}`;
                fileNameDisplay = `<a href="" onclick="window.location = this.dataset.url" data-url="${fileUrl}" title="Ouvrir dans Word">${fileName}</a>`;
            } else if (fileExtension === 'xlsx' || fileExtension === 'xls') {
                fileUrl = `ms-excel:ofe|u|file:///P:/${originalPath}/${fileName}`;
                fileNameDisplay = `<a href="" onclick="window.location = this.dataset.url" data-url="${fileUrl}" title="Ouvrir dans Excel">${fileName}</a>`;
            } else if (fileExtension === 'pptx') {
                fileUrl = `ms-powerpoint:ofe|u|file:///P:/${originalPath}/${fileName}`;
                fileNameDisplay = `<a href="" onclick="window.location = this.dataset.url" data-url="${fileUrl}" title="Ouvrir dans PowerPoint">${fileName}</a>`;
            } else {
                // Pour les autres fichiers, garder le lien d'aperçu
                fileNameDisplay = `<a href="#" onclick="previewFileChrome('${safeFilePath}', '${safeFileName}')">${fileName}</a>`;
            }
            
            // Garder le lien d'aperçu Chrome en backup (identique à renderListView)
            fileNameDisplay += `<a href="#" class="d-none" onclick="previewFileChrome('${safeFilePath}', '${safeFileName}')">${fileName}</a>`;
        }

        // Garder l'ancien style d'événement onclick sur la carte pour les dossiers
        const folderClick = fileType === 'folder' ? `onclick="handleFolderClick('${safeFilePath}', '${safeFileName}')" style="cursor: pointer;"` : '';

        html += `
            <div class="file-card ${fileCategory}" data-file-type="${fileType}" 
                 ${folderClick}>
                <div class="file-actions">
                    ${fileExtension === 'pdf' ? 
                        `<button type="button" class="action-btn preview-btn text-danger" onclick="previewFile('${safeFilePath}', '${safeFileName}')" title="Aperçu">
                            <i class="fas fa-eye"></i>
                        </button>` : ''
                    }
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
                <div class="file-name">${fileNameDisplay}</div>
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
                const originalPath = file.original_path;
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
                            <div class="list-file-icon ${fileType === 'folder' ? 'text-warning' : (fileExtension === 'docx' || fileExtension === 'doc' ? 'text-primary' : (fileExtension === 'xlsx' || fileExtension === 'xls' ? 'text-success' : (fileExtension === 'pptx' ? 'text-warning' : 'text-secondary')))}">${icon}</div>
                            <div>
                                <div class="file-name">
                                ${fileType === 'file' ? `${fileExtension === 'docx' || fileExtension === 'doc' ? 
                                `<a href="" onclick="window.location = this.dataset.url" data-url="ms-word:ofe|u|file:///I:/${originalPath}/${fileName}" title="Ouvrir dans Word">
                                    ${fileName}  
                                </a>` :
                                (fileExtension === 'xlsx' || fileExtension === 'xls' ?
                                    `<a href="" onclick="window.location = this.dataset.url" data-url="ms-excel:ofe|u|file:///I:/${originalPath}/${fileName}" title="Ouvrir dans Excel">
                                        ${fileName}
                                    </a>` :
                                    (fileExtension === 'pptx' ?
                                        `<a href="" onclick="window.location = this.dataset.url" data-url="ms-powerpoint:ofe|u|file:///I:/${originalPath}/${fileName}" title="Ouvrir dans PowerPoint">
                                            ${fileName}
                                        </a>` :
                                    (fileType === 'folder' ?
                                        `` :  `<a href="#" onclick="previewFileChrome('${safeFilePath}', '${safeFileName}')">${fileName}</a>`
                                    )
                                    )
                                )
                            }
                                <a href="#" class="d-none" onclick="previewFileChrome('${safeFilePath}', '${safeFileName}')">${fileName}</a>
                                ` : fileName}</div>
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
                'mp3': 'fa-file-audio',
                'pptx': 'fa-file-powerpoint'
            };
            
            const iconClass = iconMap[extension] || 'fa-file';
            return `<i class="fas ${iconClass}"></i>`;
        }

        // Event handlers for modal buttons
        // Upload folder button
        $('#uploadFolderBtn').on('click', function() {
            $('#folderInput').click();
        });

        // Folder input change
        $('#folderInput').on('change', function(e) {
            if (e.target.files.length > 0) {
                handleFolderUpload(e.target.files);
            }
        });
        
        $('#confirmDeleteBtn').on('click', function() {
            executeDelete();
        });

        $('#confirmRenameBtn').on('click', function() {
            executeRename();
        });

        $('#confirmMoveBtn').on('click', function() {
            executeMove();
        });

        $('#confirmCreateFolderBtn').on('click', function() {
            createFolder();
        });

        // Enter key support for modals
        $('#newName').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                executeRename();
            }
        });

        $('#folderName').on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                createFolder();
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

        $('#createFolderModal').on('hidden.bs.modal', function () {
            $('#confirmCreateFolderBtn').prop('disabled', false).html('<i class="fas fa-folder-plus"></i> Créer le dossier');
            $('#folderName').removeClass('is-invalid');
            $('#folderNameError').hide();
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

        // Create folder button
        $('#createFolderBtn').on('click', function() {
            showCreateFolderModal();
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
                url: `/intervenants/${intervenantId}/upload`,
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

        const createFileBtn = document.getElementById('createFileBtn');
        const dropdown = document.getElementById('fileTypeDropdown');
        const modal = document.getElementById('createFileModal');
        const fileTypeInput = document.getElementById('file_type');

        // Toggle dropdown
        createFileBtn.addEventListener('click', function(e){
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(){
            dropdown.classList.remove('show');
        });

        // Dropdown item click -> open modal
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function(e){
                e.preventDefault();
                fileTypeInput.value = this.dataset.fileType;
                modal.classList.add('show'); // show modal
                dropdown.classList.remove('show'); // hide dropdown
            });
        });

        // Cancel button
        document.getElementById('cancelBtn').addEventListener('click', function(){
            modal.classList.remove('show');
            document.getElementById('file_name').value = '';
        });

        // Submit button
        document.getElementById('submitBtn').addEventListener('click', function(){
            const fileName = document.getElementById('file_name').value.trim();
            const fileType = fileTypeInput.value;
            const intervenantId = document.getElementById('intervenant_id').value;
            

            if(!fileName){ alert('Veuillez saisir un nom de fichier.'); return; }

            const formData = new FormData();
            formData.append('file_name', fileName + '.' + fileType);
            formData.append('intervenant_id', intervenantId);
            formData.append('path', currentPath);

            fetch('{{ route("intervenant.create.file.backend") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Succès',
                        body: `"${data.message}"`,
                        autohide: true,
                        delay: 4000
                    });
                    modal.classList.remove('show');
                    console.log(currentPath);
                    loadFiles(currentPath);
                } else {
                    alert(data.error || 'Erreur lors de la création du fichier.');
                }
            })
            .catch(err => { console.error(err); alert('Erreur serveur.'); });
        });

        // Close modal when clicking outside modal content
        modal.addEventListener('click', function(e){
            if(e.target === modal){
                modal.classList.remove('show');
            }
        });
        
        // Make functions globally available
        window.handleFolderClick = handleFolderClick;
        window.goBack = goBack;
        window.previewFile = previewFile;
        window.previewFileChrome = previewFileChrome;
        window.downloadFile = downloadFile;
        window.showDeleteConfirmation = showDeleteConfirmation;
        window.showRenameModal = showRenameModal;
        window.showMoveModal = showMoveModal;
        window.showCreateFolderModal = showCreateFolderModal;
    });
</script>