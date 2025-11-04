<!DOCTYPE html>
<html>
<head>
    <title>Laravel Desktop - MySQL</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div id="app">
        <h1>🚀 Laravel Desktop with MySQL</h1>
        
        <div class="status-panel">
            <h3>Database Status</h3>
            <p>Connection: <span id="db-status">Checking...</span></p>
            <p>Database: <span id="db-name">{{ config('database.connections.mysql.database') }}</span></p>
            <p>Host: <span id="db-host">{{ config('database.connections.mysql.host') }}</span></p>
            
            <button onclick="testDatabase()">Test Connection</button>
            <button onclick="showDatabaseStats()">Show Stats</button>
            <button onclick="backupDatabase()">Backup Database</button>
        </div>

        <div id="db-stats" style="display: none; margin-top: 20px; padding: 15px; background: #f5f5f5;">
            <h4>Database Statistics</h4>
            <div id="stats-content"></div>
        </div>
    </div>

    <script>
        async function testDatabase() {
            try {
                const response = await fetch('/api/desktop/test-db');
                const data = await response.json();
                
                const statusElement = document.getElementById('db-status');
                if (data.connected) {
                    statusElement.innerHTML = `<span style="color: green;">✅ Connected</span>`;
                    statusElement.innerHTML += `<br><small>Version: ${data.version}</small>`;
                } else {
                    statusElement.innerHTML = `<span style="color: red;">❌ Failed: ${data.error}</span>`;
                }
            } catch (error) {
                document.getElementById('db-status').innerHTML = 
                    `<span style="color: red;">❌ Error: ${error.message}</span>`;
            }
        }

        async function showDatabaseStats() {
            try {
                const response = await fetch('/api/desktop/db-stats');
                const data = await response.json();
                
                if (data.error) {
                    alert('Error: ' + data.error);
                    return;
                }
                
                const statsDiv = document.getElementById('db-stats');
                const contentDiv = document.getElementById('stats-content');
                
                contentDiv.innerHTML = `
                    <p>Tables: ${data.tables}</p>
                    <p>Users: ${data.users}</p>
                    <p>Migrations: ${data.migrations}</p>
                    <p>Database Size: ${data.database_size} MB</p>
                `;
                
                statsDiv.style.display = 'block';
            } catch (error) {
                alert('Failed to get database stats: ' + error.message);
            }
        }

        async function backupDatabase() {
            if (!confirm('Create database backup?')) return;
            
            try {
                const response = await fetch('/api/desktop/backup-database', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({})
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert(`Backup created successfully!\nLocation: ${data.filepath}\nSize: ${data.size} bytes`);
                } else {
                    alert('Backup failed: ' + data.error);
                }
            } catch (error) {
                alert('Backup failed: ' + error.message);
            }
        }

        // Test database connection on page load
        document.addEventListener('DOMContentLoaded', testDatabase);
    </script>
</body>
</html>