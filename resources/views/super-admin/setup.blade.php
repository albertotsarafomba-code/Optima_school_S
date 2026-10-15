<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configuration du Super Admin | OPTIMASCHOOL</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: #f1f5f9;
            color: #172033;
            font-family: Arial, Helvetica, sans-serif;
        }
        .card {
            width: 100%;
            max-width: 480px;
            padding: 32px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 12px 35px rgba(15, 23, 42, .08);
        }
        .brand { color: #1e3a8a; font-weight: 800; font-size: 25px; }
        h1 { margin: 12px 0; font-size: 24px; }
        .description { color: #475569; line-height: 1.6; font-size: 14px; }
        label { display: block; margin: 17px 0 7px; font-weight: 700; font-size: 14px; }
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
        }
        input:focus { outline: 2px solid #93c5fd; border-color: #1e3a8a; }
        button {
            width: 100%;
            margin-top: 24px;
            padding: 13px;
            border: 0;
            border-radius: 8px;
            background: #1e3a8a;
            color: white;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }
        .error { margin-top: 6px; color: #b91c1c; font-size: 13px; }
        .warning {
            margin-top: 18px;
            padding: 12px;
            background: #eff6ff;
            border-left: 4px solid #1e3a8a;
            font-size: 13px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
<main class="card">
    <div class="brand">OPTIMASCHOOL</div>
    <h1>Créer le premier Super Admin</h1>
    <p class="description">
        Cette page sert uniquement à initialiser le compte Super Administrateur
        de la plateforme. Elle sera automatiquement désactivée après la création.
    </p>

    <form method="POST" action="{{ route('super-admin.setup.store') }}">
        @csrf

        <label for="setup_key">Clé secrète d'initialisation</label>
        <input
            id="setup_key"
            name="setup_key"
            type="password"
            required
            autocomplete="off"
        >
        @error('setup_key')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="name">Nom complet</label>
        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name') }}"
            maxlength="255"
            required
            autocomplete="name"
        >
        @error('name')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="email">Adresse email</label>
        <input
            id="email"
            name="email"
            type="email"
            value="{{ old('email') }}"
            maxlength="255"
            required
            autocomplete="email"
        >
        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="password">Mot de passe (12 caractères minimum)</label>
        <input
            id="password"
            name="password"
            type="password"
            minlength="12"
            required
            autocomplete="new-password"
        >
        @error('password')
            <div class="error">{{ $message }}</div>
        @enderror

        <label for="password_confirmation">Confirmer le mot de passe</label>
        <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            minlength="12"
            required
            autocomplete="new-password"
        >

        <button type="submit">Créer le Super Admin</button>
    </form>

    <div class="warning">
        Ne communiquez jamais votre clé secrète. Après cette création,
        cette page ne permettra plus de créer un autre compte.
    </div>
</main>
</body>
</html>
