<!DOCTYPE html>
<html>
<head>
    <title>ONLYOFFICE Simple Test</title>
    <script src="http://localhost:8080/web-apps/apps/api/documents/api.js"></script>
    <style>
        body { margin: 0; padding: 20px; font-family: Arial; }
        #editor { width: 100%; height: 600px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <h1>Simple ONLYOFFICE Test</h1>
    <div id="editor"></div>
    
    <script>
        // Create a simple text content for the document
        var fileContent = "Simple test document content";
        var blob = new Blob([fileContent], { type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' });
        var fileUrl = URL.createObjectURL(blob);

        var config = {
            "document": {
                "title": "test.docx",
                "url": fileUrl,  // Use blob URL instead of Laravel
                "fileType": "docx",
                "key": "test_" + Date.now()
            },
            "documentType": "word",
            "editorConfig": {
                "mode": "edit",
                "callbackUrl": "https://httpbin.org/post"  // Public test endpoint
            }
        };

        console.log('Config:', config);
        
        var docEditor = new DocsAPI.DocEditor("editor", config);
        
        docEditor.onReady = function() {
            console.log('Editor ready');
        };
        
        docEditor.onError = function(error) {
            console.error('Error:', error);
        };
    </script>
</body>
</html>