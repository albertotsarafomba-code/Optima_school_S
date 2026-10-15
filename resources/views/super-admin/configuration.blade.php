<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Configuration | OPTIMASCHOOL</title>

    <style>
        :root {
            --navy: #101d3d;
            --blue: #1e3a8a;
            --green: #16a34a;
            --background: #f4f7fb;
            --white: #ffffff;
            --text: #172033;
            --muted: #64748b;
            --border: #e2e8f0;
            --danger: #b91c1c;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--background);
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;
        }

        a { color: inherit; text-decoration: none; }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 258px;
            padding: 24px 16px;
            overflow-y: auto;
            background: var(--navy);
            color: #fff;
            z-index: 20;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 4px 8px 26px;
            border-bottom: 1px solid rgba(255,255,255,.13);
        }

        .brand img {
            width: 46px;
            height: 46px;
            object-fit: contain;
            border-radius: 9px;
            background: white;
        }

        .brand-name { font-size: 17px; font-weight: 800; }
        .brand-sub { margin-top: 5px; font-size: 12px; color: #cbd5e1; }

        .sidebar-section-label {
            margin: 26px 8px 10px;
            color: #a9b8d2;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-nav { display: grid; gap: 7px; }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 46px;
            padding: 11px 13px;
            color: #e2e8f0;
            border-radius: 8px;
            font-weight: 600;
            transition: background .15s ease;
        }

        .sidebar-link:hover { background: #1d2d51; }

        .sidebar-link.active {
            background: var(--blue);
            color: white;
            box-shadow: inset 3px 0 var(--green);
        }

        .sidebar-link svg {
            width: 21px;
            height: 21px;
            flex-shrink: 0;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .sidebar-note {
            margin: 32px 4px 0;
            padding: 15px 12px;
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 9px;
            color: #dbeafe;
            font-size: 12px;
            line-height: 1.7;
        }

        .workspace { min-height: 100vh; margin-left: 258px; }

        .topbar {
            min-height: 78px;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            background: white;
            border-bottom: 1px solid var(--border);
        }

        .topbar-title { font-weight: 800; font-size: 19px; }
        .topbar-subtitle { margin-top: 5px; color: var(--muted); font-size: 13px; }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            overflow: hidden;
            border-radius: 50%;
            background: #dbeafe;
            color: var(--blue);
            font-weight: 800;
        }

        .avatar img { width: 100%; height: 100%; object-fit: cover; }
        .admin-name { font-weight: 700; font-size: 13px; }
        .admin-role { margin-top: 4px; color: var(--muted); font-size: 12px; }

        .logout {
            padding: 10px 13px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            color: #334155;
            font-weight: 700;
            cursor: pointer;
        }

        .page { max-width: 1400px; margin: auto; padding: 30px; }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 24px;
        }

        h1 { margin: 0; font-size: 27px; }
        .intro { margin: 9px 0 0; color: var(--muted); line-height: 1.6; }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 11px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            color: var(--blue);
            font-weight: 700;
            white-space: nowrap;
        }

        .alert {
            margin-bottom: 20px;
            padding: 14px 16px;
            border: 1px solid #bbf7d0;
            border-radius: 9px;
            background: #f0fdf4;
            color: #166534;
            line-height: 1.5;
        }

        .alert-error {
            background: #fef2f2;
            border-color: #fecaca;
            color: var(--danger);
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
            align-items: start;
        }

        .card {
            min-width: 0;
            overflow: hidden;
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(15,23,42,.035);
        }

        .card-header {
            padding: 20px 22px;
            border-bottom: 1px solid var(--border);
        }

        .card-header h2 { margin: 0; font-size: 17px; }
        .card-header p { margin: 7px 0 0; color: var(--muted); font-size: 13px; line-height: 1.5; }
        .card-body { padding: 22px; }

        .field { margin-bottom: 18px; }
        label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 700; }

        input {
            width: 100%;
            min-height: 44px;
            padding: 11px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: white;
            color: var(--text);
            font: inherit;
        }

        input:focus {
            outline: 2px solid #bfdbfe;
            border-color: var(--blue);
        }

        input[type="file"] { padding: 8px; font-size: 13px; }

        .help { margin-top: 7px; color: var(--muted); font-size: 12px; line-height: 1.5; }
        .error { margin-top: 6px; color: var(--danger); font-size: 12px; }

        .button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            min-height: 43px;
            padding: 11px 16px;
            border: 0;
            border-radius: 8px;
            background: var(--blue);
            color: white;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .button:hover { background: #172f70; }
        .button-green { background: var(--green); }
        .button-green:hover { background: #15803d; }

        .logo-preview {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 18px;
            padding: 13px;
            border: 1px dashed #cbd5e1;
            border-radius: 9px;
        }

        .logo-preview img {
            width: 68px;
            height: 68px;
            object-fit: contain;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
        }

        .logo-title { font-weight: 700; }
        .logo-description { margin-top: 5px; color: var(--muted); font-size: 12px; line-height: 1.5; }

        .profile-preview {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .profile-preview .avatar { width: 58px; height: 58px; font-size: 18px; }
        .profile-title { font-weight: 700; }
        .profile-email { margin-top: 5px; color: var(--muted); font-size: 13px; }

        .security-note {
            margin-top: 18px;
            padding: 12px 13px;
            border-left: 4px solid var(--green);
            border-radius: 5px;
            background: #f0fdf4;
            color: #166534;
            font-size: 12px;
            line-height: 1.6;
        }

        .full-width { grid-column: 1 / -1; }

        @media (max-width: 1050px) {
            .settings-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: auto; }
        }

        @media (max-width: 760px) {
            .sidebar {
                position: relative;
                width: 100%;
                padding: 14px;
            }

            .brand { padding: 4px 6px 14px; }
            .sidebar-section-label { margin: 15px 5px 8px; }

            .sidebar-nav {
                display: flex;
                gap: 7px;
                overflow-x: auto;
                padding-bottom: 5px;
            }

            .sidebar-link { flex: 0 0 auto; min-height: 42px; padding: 9px 11px; font-size: 13px; }
            .sidebar-note { display: none; }
            .workspace { margin-left: 0; }
            .topbar { padding: 14px 18px; }
            .topbar-title { font-size: 16px; }
            .admin-info { display: none; }
            .page { padding: 22px 16px; }
            .page-heading { flex-direction: column; }
            h1 { font-size: 24px; }
        }

        @media (max-width: 420px) {
            .topbar { gap: 8px; }
            .topbar-right { gap: 8px; }
            .logout { padding: 9px; font-size: 12px; }
            .card-header, .card-body { padding: 17px; }
        }
    </style>
</head>
<body>

@php
    $platformName = $settings['platform_name'] ?? 'OPTIMASCHOOL';
    $platformLogo = $settings['platform_logo'] ?? null;
    $adminPhoto = $admin->profilePhotoUrl();
@endphp

<aside class="sidebar">
    <a href="{{ route('super-admin.dashboard') }}" class="brand">
        <img
            src="{{ $platformLogo ? \Illuminate\Support\Facades\Storage::url($platformLogo) : asset('images/LOGO_optima.jpg') }}"
            alt="Logo OPTIMASCHOOL"
        >
        <div>
            <div class="brand-name">{{ $platformName }}</div>
            <div class="brand-sub">Administration plateforme</div>
        </div>
    </a>

    <div class="sidebar-section-label">Menu principal</div>

    <nav class="sidebar-nav" aria-label="Menu principal">
        <a href="{{ route('super-admin.dashboard') }}" class="sidebar-link">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                <rect x="14" y="14" width="7" height="7" rx="1"></rect>
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('super-admin.abonnements.index') }}" class="sidebar-link">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                <path d="M3 10h18M7 15h4"></path>
            </svg>
            <span>Abonnements</span>
        </a>

        <a href="{{ route('super-admin.abonnements.index') }}" class="sidebar-link">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 3v18M17 7H9a3 3 0 0 0 0 6h6a3 3 0 0 1 0 6H6"></path>
            </svg>
            <span>Paiements</span>
        </a>
    </nav>

    <div class="sidebar-section-label">Administration</div>

    <nav class="sidebar-nav" aria-label="Administration">
        <a href="{{ route('super-admin.configuration.index') }}"
           class="sidebar-link active" aria-current="page">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="m19 13 2 1-2 4-2-1-2 1v3H9v-3l-2-1-2 1-2-4 2-1v-2L3 10l2-4 2 1 2-1V3h6v3l2 1 2-1 2 4-2 1z"></path>
            </svg>
            <span>Configuration</span>
        </a>
    </nav>

    <div class="sidebar-note">
        <strong>{{ $platformName }}</strong><br>
        Gestion sécurisée de la plateforme.
    </div>
</aside>

<div class="workspace">
    <header class="topbar">
        <div>
            <div class="topbar-title">Configuration</div>
            <div class="topbar-subtitle">Paramètres de la plateforme et du compte</div>
        </div>

        <div class="topbar-right">
            <div class="avatar">
                @if ($adminPhoto)
                    <img src="{{ $adminPhoto }}" alt="Photo du Super Admin">
                @else
                    {{ $admin->initials() }}
                @endif
            </div>

            <div class="admin-info">
                <div class="admin-name">{{ $admin->name }}</div>
                <div class="admin-role">Super Administrateur</div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout">Déconnexion</button>
            </form>
        </div>
    </header>

    <main class="page">
        <div class="page-heading">
            <div>
                <h1>Paramètres de la plateforme</h1>
                <p class="intro">
                    Gérez l'identité d'OPTIMASCHOOL, votre profil et la sécurité de votre compte.
                </p>
            </div>

            <a href="{{ route('super-admin.dashboard') }}" class="back-link">
                <span aria-hidden="true">←</span>
                Retour au dashboard
            </a>
        </div>

        @if (session('success'))
            <div class="alert" role="status">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                <strong>Veuillez vérifier les informations saisies.</strong>
                <ul style="margin-bottom:0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="settings-grid">
            <section class="card">
                <div class="card-header">
                    <h2>Configuration générale</h2>
                    <p>Personnalisez le nom et le logo affichés pour la plateforme.</p>
                </div>

                <div class="card-body">
                    <div class="logo-preview">
                        <img
                            src="{{ $platformLogo ? \Illuminate\Support\Facades\Storage::url($platformLogo) : asset('images/LOGO_optima.jpg') }}"
                            alt="Logo actuel"
                        >
                        <div>
                            <div class="logo-title">{{ $platformName }}</div>
                            <div class="logo-description">
                                Logo actuel de la plateforme.
                            </div>
                        </div>
                    </div>

                    <form method="POST"
                          action="{{ route('super-admin.configuration.platform') }}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="field">
                            <label for="platform_name">Nom de la plateforme</label>
                            <input
                                id="platform_name"
                                name="platform_name"
                                type="text"
                                maxlength="120"
                                value="{{ old('platform_name', $platformName) }}"
                                required
                            >
                            @error('platform_name')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="platform_logo">Logo de la plateforme</label>
                            <input
                                id="platform_logo"
                                name="platform_logo"
                                type="file"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            >
                            <div class="help">
                                Formats autorisés : JPG, PNG et WEBP. Taille maximale : 2 Mo.
                                Laissez ce champ vide pour conserver le logo actuel.
                            </div>
                            @error('platform_logo')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="button" type="submit">Enregistrer les paramètres</button>
                    </form>
                </div>
            </section>

            <section class="card">
                <div class="card-header">
                    <h2>Profil Super Admin</h2>
                    <p>Modifiez vos informations personnelles et votre photo de profil.</p>
                </div>

                <div class="card-body">
                    <div class="profile-preview">
                        <div class="avatar">
                            @if ($adminPhoto)
                                <img src="{{ $adminPhoto }}" alt="Photo actuelle">
                            @else
                                {{ $admin->initials() }}
                            @endif
                        </div>
                        <div>
                            <div class="profile-title">{{ $admin->name }}</div>
                            <div class="profile-email">{{ $admin->email }}</div>
                        </div>
                    </div>

                    <form method="POST"
                          action="{{ route('super-admin.configuration.profile') }}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="field">
                            <label for="name">Nom complet</label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                maxlength="255"
                                value="{{ old('name', $admin->name) }}"
                                autocomplete="name"
                                required
                            >
                            @error('name')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="email">Adresse email</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                maxlength="255"
                                value="{{ old('email', $admin->email) }}"
                                autocomplete="email"
                                required
                            >
                            @error('email')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="field">
                            <label for="profile_photo">Photo de profil</label>
                            <input
                                id="profile_photo"
                                name="profile_photo"
                                type="file"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            >
                            <div class="help">
                                JPG, PNG ou WEBP, 2 Mo maximum. Facultatif.
                            </div>
                            @error('profile_photo')
                                <div class="error">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="button" type="submit">Enregistrer mon profil</button>
                    </form>
                </div>
            </section>

            <section class="card full-width">
                <div class="card-header">
                    <h2>Sécurité du compte</h2>
                    <p>Modifiez votre mot de passe. Le mot de passe actuel est obligatoire.</p>
                </div>

                <div class="card-body">
                    <form method="POST"
                          action="{{ route('super-admin.configuration.password') }}">
                        @csrf
                        @method('PUT')

                        <div class="settings-grid">
                            <div class="field">
                                <label for="current_password">Mot de passe actuel</label>
                                <input
                                    id="current_password"
                                    name="current_password"
                                    type="password"
                                    autocomplete="current-password"
                                    required
                                >
                                @error('current_password')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="password">Nouveau mot de passe</label>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    minlength="12"
                                    autocomplete="new-password"
                                    required
                                >
                                <div class="help">12 caractères minimum.</div>
                                @error('password')
                                    <div class="error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="field">
                                <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    minlength="12"
                                    autocomplete="new-password"
                                    required
                                >
                            </div>
                        </div>

                        <button class="button button-green" type="submit">
                            Modifier le mot de passe
                        </button>

                        <div class="security-note">
                            Votre compte reste protégé : le mot de passe actuel est vérifié
                            avant tout changement. Utilisez un mot de passe unique et difficile à deviner.
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>
</div>

</body>
</html>
