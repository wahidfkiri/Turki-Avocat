<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>OnlyOffice Editor</title>
    <script src="http://217.182.168.27:8080/web-apps/apps/api/documents/api.js"></script>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden; /* prevent scrolling */
        }
        #placeholder {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div id="placeholder"></div>

    <script>
        const config = @json($config);
        new DocsAPI.DocEditor("placeholder", config);
    </script>
</body>
</html>
