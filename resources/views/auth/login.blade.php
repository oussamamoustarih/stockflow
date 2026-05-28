<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockFlow — Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="/assets/img/logo.svg" type="image/svg+xml">
    <style>
    body {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .login-card {
        background: #fff;
        border-radius: 20px;
        padding: 45px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.4);
    }
    .logo {
        text-align: center;
        margin-bottom: 35px;
    }
    .logo i {
        font-size: 3.5rem;
        color: #3b82f6;
    }
    .logo h1 {
        font-size: 2rem;
        font-weight: 800;
        color: #1e293b;
        margin: 10px 0 5px;
        letter-spacing: -0.5px;
    }
    .logo p {
        color: #475569;
        font-size: 0.95rem;
        font-weight: 500;
    }
    .form-label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 6px;
    }
    .label-icon-email {
        color: #3b82f6;
        margin-right: 6px;
    }
    .label-icon-password {
        color: #10b981;
        margin-right: 6px;
    }
    .btn-login {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        padding: 13px;
        font-size: 1rem;
        font-weight: 700;
        border-radius: 10px;
        letter-spacing: 0.3px;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(59,130,246,0.4);
    }
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59,130,246,0.5);
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    }
    .form-control {
        border-radius: 10px;
        padding: 11px 15px;
        border: 1.5px solid #e2e8f0;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
    }
    .form-check-label {
        color: #64748b;
        font-size: 0.9rem;
    }
    .divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #e2e8f0, transparent);
        margin: 25px 0;
    }
    @media (max-width: 575.98px) {
        body {
            align-items: stretch;
            padding: 14px;
        }
        .login-card {
            padding: 28px 20px;
            border-radius: 16px;
            max-width: none;
            margin: auto 0;
        }
        .logo {
            margin-bottom: 24px;
        }
        .logo i {
            font-size: 2.8rem;
        }
        .logo h1 {
            font-size: 1.65rem;
        }
    }
</style>
</head>
<body>

<div class="login-card">
    <div class="logo">
        <i class="bi bi-boxes"></i>
        <h1>StockFlow</h1>
        <p>Gestion de stock pour PME</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-3">
            <label class="form-label">
                <i class="bi bi-envelope-fill label-icon-email"></i>
                Adresse email
            </label>
            <input type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   placeholder="exemple@stockflow.com"
                   required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mot de passe --}}
        <div class="mb-3">
            <label class="form-label">
                <i class="bi bi-shield-lock-fill label-icon-password"></i>
                Mot de passe
            </label>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="••••••••"
                   required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Se souvenir --}}
        <div class="mb-4 form-check">
            <input type="checkbox" name="remember"
                   class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">
                Se souvenir de moi
            </label>
        </div>

        <div class="divider"></div>

        <button type="submit" class="btn btn-login btn-primary w-100 text-white">
            <i class="bi bi-box-arrow-in-right"></i> Se connecter
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function() {
    // Vider complètement l'historique
    window.history.replaceState(null, null, '/login');

    // Bloquer le retour
    window.addEventListener('popstate', function() {
        window.location.replace('/login');
    });

    // Pousser 20 fois pour écraser tout l'historique précédent
    for (let i = 0; i < 20; i++) {
        window.history.pushState(null, null, '/login');
    }
})();
</script>
</body>
</html>
