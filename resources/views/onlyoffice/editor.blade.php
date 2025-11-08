<!DOCTYPE html>
<html>
<head>
    <title>OnlyOffice Editor</title>
    <script type="text/javascript" src="http://{{ request()->getHost() }}:8082/web-apps/apps/api/documents/api.js"></script>
</head>
<body style="margin:0; padding:0; height:100vh;">
    <div id="placeholder" style="height:100vh;"></div>

    <script>
        const config = @json($config);

        // ⚠️ Token doit être dans document.token
        config.document.token = config.token;

        const docEditor = new DocsAPI.DocEditor("placeholder", {
            document: config.document,
            documentType: config.documentType,
            editorConfig: config.editorConfig
        });
    </script>
</body>
</html>
