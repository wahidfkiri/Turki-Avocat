<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle Tâche Assignée</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .header {
            background-color: #4a90e2;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: white;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .task-details {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .task-details th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }
        .task-details td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .btn {
            display: inline-block;
            background-color: #4a90e2;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 0;
        }
        .note {
            background-color: #fffde7;
            padding: 15px;
            border-left: 4px solid #ffd600;
            margin: 20px 0;
        }
        .file-info {
            background-color: #e8f5e9;
            padding: 15px;
            border-left: 4px solid #4caf50;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nouvelle Tâche Assignée</h1>
        </div>
        
        <div class="content">
            <p>Bonjour {{ $user->name }},</p>
            
            <p>Une nouvelle tâche vous a été assignée. Voici les détails :</p>
            
            <h3>Détails de la tâche</h3>
            <table class="task-details">
                @foreach($taskDetails as $label => $value)
                <tr>
                    <th>{{ $label }}</th>
                    <td>{{ $value }}</td>
                </tr>
                @endforeach
            </table>
            
            @if($note)
            <div class="note">
                <h4>Note supplémentaire :</h4>
                <p>{{ $note }}</p>
            </div>
            @endif
            
            @if($hasFile)
            <div class="file-info">
                <h4>Fichier attaché :</h4>
                <p><strong>Nom du fichier :</strong> {{ $task->file_name }}</p>
                <p><a href="{{ $task->getFileUrlAttribute() }}" class="btn">Télécharger le fichier</a></p>
            </div>
            @endif
            
            <p>
                <a href="{{ url('/tasks/' . $task->id) }}" class="btn">
                    Voir la tâche dans l'application
                </a>
            </p>
            
            <p>Merci d'utiliser notre application!</p>
            
            <div class="footer">
                <p>Ceci est une notification automatique, merci de ne pas répondre à cet email.</p>
                <p>Si vous pensez avoir reçu cet email par erreur, veuillez contacter l'administrateur.</p>
            </div>
        </div>
    </div>
</body>
</html>