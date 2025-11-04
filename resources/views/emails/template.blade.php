{{-- resources/views/emails/template.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Eaboutify' }}</title>
    <style>
        /* Styles responsive pour Eaboutify */
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
        }
        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
            }
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Eaboutify.com</h1>
            <p>Votre partenaire digital</p>
        </div>
        
        <div class="content">
            {!! $content !!}
            
            <div class="signature">
                <p>Cordialement,<br>
                <strong>L'équipe Eaboutify</strong><br>
                <a href="https://eaboutify.com">eaboutify.com</a></p>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Eaboutify. Tous droits réservés.</p>
            <p>
                <a href="https://eaboutify.com/privacy">Politique de confidentialité</a> | 
                <a href="https://eaboutify.com/contact">Contact</a>
            </p>
        </div>
    </div>
</body>
</html>