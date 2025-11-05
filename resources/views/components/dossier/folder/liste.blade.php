
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

                        <div class="toolbar">
                            <div class="view-options">
                                <div class="view-option active" data-view="grid">
                                    <i class="fas fa-th"></i> Grille
                                </div>
                                <div class="view-option" data-view="list">
                                    <i class="fas fa-list"></i> Liste
                                </div>
                            </div>
                            <div class="sort-options">
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

<script>
    $(document).ready(function() {
        const dossierId = {{ $dossier->id }};
        let allFiles = [];
        let currentView = 'grid';
        let currentSort = 'name';
        let filesToUpload = [];

        // Load files function
        function loadFiles() {
            $('#filesContainer').html(`
                <div class="loading-state">
                    <div class="spinner"></div>
                    <p>chargement des fichiers...</p>
                </div>
            `);

            $.ajax({
                url: `/dossiers/${dossierId}/files`,
                type: 'GET',
                dataType: 'json',
                success: function(files) {
                    allFiles = files;
                    sortFiles(currentSort);
                    renderFiles(files);
                },
                error: function(xhr, status, error) {
                    $('#filesContainer').html(`
                        <div class="empty-state">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h3>Erreur de chargement</h3>
                            <p>Il y a eu un problème lors du chargement des fichiers. Veuillez réessayer.</p>
                            <button class="btn btn-primary mt-2" onclick="loadFiles()">
                                <i class="fas fa-redo"></i> Réessayer
                            </button>
                        </div>
                    `);
                }
            });
        }

        // Sort files
        function sortFiles(sortBy) {
            currentSort = sortBy;
            allFiles.sort((a, b) => {
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
                const fileUrl = file.url;
                
                // Determine file category for styling
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

                // Get appropriate icon
                const icon = getFileIcon(fileType, fileExtension);

                html += `
                    <div class="file-card ${fileCategory}" data-file-type="${fileType}">
                        <div class="file-actions">
                            ${fileType === 'file' ? 
                                `<a href="{{url('dossier/download/${fileUrl}')}}" class="action-btn" download>
                                    <i class="fas fa-download"></i>
                                </a>` : 
                                ''
                            }
                          
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
                const fileUrl = file.url;
                
                const icon = getFileIcon(fileType, fileExtension);

                html += `
                    <div class="list-item">
                        <div class="list-item-content">
                            <div class="list-file-icon ${fileType === 'folder' ? 'text-warning' : (fileExtension === 'docx' ? 'text-success' : (fileExtension === 'xlsx' ? 'text-danger' : 'text-info'))}">${icon}</div>
                            <div>
                                <div class="file-name">${fileName}</div>
                                <div class="file-meta">${lastModified}</div>
                            </div>
                        </div>     <div>${fileSize}</div>
                        <div>${fileType === 'folder' ? 'Dossier' : fileExtension.toUpperCase()}</div>
                        <div class="list-file-actions">
                            ${fileType === 'file' ? 
                                `<a href="{{url('dossier/download/${dossierId}/${fileName}')}}" class="action-btn">
                                    <i class="fas fa-download"></i>
                                </a>` : 
                                ''
                            }
                           
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
            loadFiles();
            setTimeout(() => {
                $icon.removeClass('fa-spin');
            }, 1000);
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

        // Upload files
        $('#uploadBtn').on('click', function() {
            if (filesToUpload.length === 0) return;

            const formData = new FormData();
            filesToUpload.forEach(file => {
                formData.append('files[]', file);
            });

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
                    loadFiles();
                    // Notification AdminLTE
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
                    // Notification AdminLTE
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
                loadFiles();
            }
        });

        // Auto-load files if files tab is active on page load
        if ($('#files').hasClass('show')) {
            loadFiles();
        }
    });
</script>