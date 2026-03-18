<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accès refusé — StockFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="text-center text-white">
        <i class="bi bi-shield-x" style="font-size: 5rem; color: #ef4444;"></i>
        <h1 class="mt-3">Accès refusé</h1>
        <p class="text-muted">Vous n'avez pas les permissions nécessaires pour accéder à cette page.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
            <i class="bi bi-house"></i> Retour au tableau de bord
        </a>
    </div>
</body>
</html>