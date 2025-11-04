
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        body {
            background-color: #f5f7fb;
            color: var(--dark-color);
            line-height: 1.6;
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

        .view-options {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .view-option {
            padding: 0.5rem 1rem;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
        }

        .view-option.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .files-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1.5rem;
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

        .file-path {
            margin-bottom: 1.5rem;
            padding: 0.8rem 1.2rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            font-size: 0.9rem;
            color: var(--gray-color);
        }

        .file-path .path-segment {
            color: var(--primary-color);
            font-weight: 600;
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
        }
    </style>
    <!-- Onglet Dossier -->
    <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
        <div class="file-browser-container">
            <div class="header">
                <h1>Dossier Files</h1>
                <!-- <div class="header-actions">
                    <button class="btn btn-outline">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                    <button class="btn btn-primary" id="refreshFiles">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div> -->
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

    <script>
        $(document).ready(function() {
            const intervenantId = {{ $intervenant->id }};
            let allFiles = [];

            // Load files function
            function loadFiles() {
                $('#filesContainer').html(`
                    <div class="loading-state">
                        <div class="spinner"></div>
                        <p>chargement des fichiers...</p>
                    </div>
                `);

                $.ajax({
                    url: `/intervenants/${intervenantId}/files`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(files) {
                        allFiles = files;
                        renderFiles(files);
                    },
                    error: function(xhr, status, error) {
                        $('#filesContainer').html(`
                            <div class="empty-state">
                                <i class="fas fa-exclamation-triangle"></i>
                                <h3>Error Loading Files</h3>
                                <p>There was a problem loading the files. Please try again.</p>
                                <button class="btn btn-primary mt-2" onclick="loadFiles()">
                                    <i class="fas fa-redo"></i> Retry
                                </button>
                            </div>
                        `);
                    }
                });
            }

            // Render files in the grid
            function renderFiles(files) {
                if (files.length === 0) {
                    $('#filesContainer').html(`
                        <div class="empty-state">
                            <i class="fas fa-folder-open"></i>
                            <h3>No Files Found</h3>
                            <p>This folder is empty. Upload some files to get started.</p>
                        </div>
                    `);
                    return;
                }

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
                                ${ fileType === 'file' ? `
                                    <a href="{{url('intervenant/download')}}/${file.id}" class="action-btn" download>
                                        <i class="fas fa-download"></i>
                                    </a>
                                ` : '' }
                                ${ `
                                    <a href="{{url('intervenant/display')}}/${file.id}" class="action-btn">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                ` }
                            </div>
                            <div class="file-icon mt-5">
                                ${icon}
                            </div>
                            <div class="file-name">${fileName}</div>
                            <div class="file-meta">
                                <span>${fileType === 'folder' ? 'Folder' : fileExtension.toUpperCase()}</span>
                                ${fileType === 'file' ? `<span>${fileSize}</span>` : ''}
                                <span>${lastModified}</span>
                            </div>
                        </div>
                    `;
                });
                
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
                
                // In a real implementation, you would change the layout here
                // For this example, we'll keep the grid view
            });

            // Refresh button
            $('#refreshFiles').on('click', function() {
                $(this).find('i').addClass('fa-spin');
                loadFiles();
                setTimeout(() => {
                    $(this).find('i').removeClass('fa-spin');
                }, 1000);
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