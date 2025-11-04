<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Folder in Explorer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>📁 Open Folder in Windows Explorer</h4>
                    </div>
                    <div class="card-body">
                        <form id="openFolderForm">
                            @csrf
                            <div class="mb-3">
                                <label for="path" class="form-label">Folder Path:</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="path" 
                                       name="path" 
                                       placeholder="C:\projects\laravel or projects/laravel"
                                       required>
                                <div class="form-text">
                                    Enter absolute path (C:\path\to\folder) or relative path from Laravel root.
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" id="openBtn">
                                Open in Explorer
                            </button>
                            
                            <div class="mt-3">
                                <h6>Quick Examples:</h6>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setExample('C:\\')">
                                        C:\ Drive
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setExample('C:\\Users')">
                                        Users Folder
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setExample('storage')">
                                        Storage Folder
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setExample('public')">
                                        Public Folder
                                    </button>
                                </div>
                            </div>
                        </form>
                        
                        <div id="result" class="mt-3"></div>
                    </div>
                </div>
                
                <!-- Recent Paths Section -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6>Common Paths</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Laravel Directories:</strong>
                                <ul class="list-unstyled mt-2">
                                    <li><a href="#" onclick="setExample('app')">app/</a></li>
                                    <li><a href="#" onclick="setExample('config')">config/</a></li>
                                    <li><a href="#" onclick="setExample('database')">database/</a></li>
                                    <li><a href="#" onclick="setExample('public')">public/</a></li>
                                    <li><a href="#" onclick="setExample('storage')">storage/</a></li>
                                    <li><a href="#" onclick="setExample('resources')">resources/</a></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <strong>System Directories:</strong>
                                <ul class="list-unstyled mt-2">
                                    <li><a href="#" onclick="setExample('C:\\')">C:\ Drive</a></li>
                                    <li><a href="#" onclick="setExample('C:\\Users')">Users Folder</a></li>
                                    <li><a href="#" onclick="setExample('C:\\Windows\\Temp')">Windows Temp</a></li>
                                    <li><a href="#" onclick="setExample('C:\\Program Files')">Program Files</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function setExample(path) {
            document.getElementById('path').value = path;
        }
        
        document.getElementById('openFolderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const openBtn = document.getElementById('openBtn');
            const originalText = openBtn.innerHTML;
            const path = document.getElementById('path').value;
            
            // Show loading state
            openBtn.innerHTML = '⏳ Opening...';
            openBtn.disabled = true;
            
            fetch('{{ route("folder.open") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ path: path })
            })
            .then(response => response.json())
            .then(data => {
                const resultDiv = document.getElementById('result');
                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="alert alert-success">
                            ✅ ${data.message}<br>
                            <small>Path: ${data.path}</small>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="alert alert-danger">
                            ❌ ${data.message}<br>
                            <small>Path: ${data.path}</small>
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('result').innerHTML = `
                    <div class="alert alert-danger">
                        ❌ Network error: ${error}
                    </div>
                `;
            })
            .finally(() => {
                // Reset button
                openBtn.innerHTML = originalText;
                openBtn.disabled = false;
            });
        });
    </script>
</body>
</html>