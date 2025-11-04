<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ONLYOFFICE Editor</title>
    <script src="http://91.134.242.86:8080/web-apps/apps/api/documents/api.js"></script>
</head>
<body>
    <div id="placeholder" style="height: 800px;"></div>

    <script>
        const config = @json($config);
        new DocsAPI.DocEditor("placeholder", config);
    </script>
</body>
</html>
