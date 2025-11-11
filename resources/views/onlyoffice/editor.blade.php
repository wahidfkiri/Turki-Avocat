<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>OnlyOffice Editor</title>
    <script src="http://localhost:8080/web-apps/apps/api/documents/api.js"></script>
</head>
<body style="margin:0; padding:0;">
    <div id="placeholder" style="width:100%; height:100vh;"></div>

    <script>
        const config = @json($config);
        const docEditor = new DocsAPI.DocEditor("placeholder", config);
    </script>
</body>
</html>
