<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des abonnements | OPTIMASCHOOL</title>

    <style>

    :root {
        --blue: #1e3a8a;
        --blue-dark: #142654;
        --blue-deep: #101d3d;
        --blue-light: #eff6ff;
        --green: #16a34a;
        --green-light: #f0fdf4;
        --ink: #172033;
        --muted: #64748b;
        --border: #e2e8f0;
        --surface: #ffffff;
        --background: #f4f7fc;
        --red: #dc2626;
        --orange: #d97706;
    }

    * {
        box-sizing: border-box;
    }

    html {
        min-height: 100%;
    }

    body {
        margin: 0;
        min-height: 100vh;
        color: var(--ink);
        background: var(--background);
        font-family: Inter, ui-sans-serif, system-ui, -apple-system,
            BlinkMacSystemFont, "Segoe UI", sans-serif;
        font-size: 15px;
        line-height: 1.55;
    }

    a {
        color: inherit;
        text-decoration: none;
    }

    button,
    input,
    select {
        font: inherit;
    }

    button,
    .button {
        cursor: pointer;
    }

    .sidebar {
        position: fixed;
        inset: 0 auto 0 0;
        z-index: 1000;
        width: 258px;
        display: flex;
        flex-direction: column;
        padding: 25px 17px 20px;
        overflow-y: auto;
        color: #fff;
        background: var(--blue-deep);
        box-shadow: 5px 0 24px rgb(15 23 42 / 8%);
    }

    .sidebar-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 8px 26px;
        border-bottom: 1px solid rgb(255 255 255 / 12%);
    }

    .sidebar-logo {
        width: 48px;
        height: 48px;
        flex: 0 0 48px;
        object-fit: contain;
        padding: 4px;
        border-radius: 12px;
        background: #fff;
    }

    .sidebar-brand-name {
        color: #fff;
        font-size: 16px;
        font-weight: 850;
        letter-spacing: -.35px;
    }

    .sidebar-brand-subtitle {
        margin-top: 5px;
        color: #b9c8e7;
        font-size: 11px;
    }

    .sidebar-section-label {
        margin: 27px 10px 11px;
        color: #91a5cc;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1.4px;
        text-transform: uppercase;
    }

    .sidebar-nav {
        display: grid;
        gap: 7px;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 12px;
        min-height: 48px;
        padding: 12px 13px;
        border: 1px solid transparent;
        border-radius: 11px;
        color: #d7e2f7;
        font-size: 13px;
        font-weight: 650;
        transition: background .18s ease, color .18s ease;
    }

    .sidebar-link:hover,
    .sidebar-link.active {
        color: #fff;
        background: #243d70;
    }

    .sidebar-link.active {
        border-color: rgb(147 197 253 / 18%);
        box-shadow: inset 3px 0 #4ade80;
    }

    .sidebar-link svg {
        width: 20px;
        height: 20px;
        flex: 0 0 20px;
        fill: none;
        stroke: currentColor;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .sidebar-note {
        margin-top: auto;
        padding: 22px 13px 0;
        color: #9fb1d2;
        font-size: 11px;
        line-height: 1.8;
    }

    .topbar {
        margin-left: 258px;
        background: var(--surface);
        border-bottom: 1px solid var(--border);
    }

    .topbar-inner {
        min-height: 82px;
        padding: 15px 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
    }

    .brand-logo {
        width: 45px;
        height: 45px;
        flex: 0 0 45px;
        object-fit: contain;
        padding: 3px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: #fff;
    }

    .brand-title {
        color: var(--blue);
        font-size: 16px;
        font-weight: 850;
        letter-spacing: -.3px;
    }

    .brand-subtitle {
        margin-top: 3px;
        color: var(--muted);
        font-size: 12px;
    }

    .top-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 20px;
    }

    .admin-profile {
        display: flex;
        align-items: center;
        gap: 11px;
        min-width: 0;
    }

    .avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 43px;
        height: 43px;
        flex: 0 0 43px;
        overflow: hidden;
        border-radius: 50%;
        color: #fff;
        background: var(--blue);
        font-size: 14px;
        font-weight: 800;
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .admin-name {
        color: var(--ink);
        font-size: 13px;
        font-weight: 750;
    }

    .admin-email {
        margin-top: 3px;
        color: var(--muted);
        font-size: 11px;
        overflow-wrap: anywhere;
    }

    .logout-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 41px;
        padding: 10px 13px;
        border: 1px solid #fecaca;
        border-radius: 9px;
        color: #b91c1c;
        background: #fff;
        font-size: 12px;
        font-weight: 750;
        white-space: nowrap;
    }

    .logout-btn:hover {
        background: #fef2f2;
    }

    .page {
        max-width: 1700px;
        margin-left: 258px;
        padding: 34px 32px 42px;
    }

    .welcome {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 26px;
    }

    .eyebrow {
        margin-bottom: 7px;
        color: var(--blue);
        font-size: 11px;
        font-weight: 850;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    h1 {
        margin: 0;
        color: #152443;
        font-size: clamp(25px, 3vw, 32px);
        font-weight: 850;
        letter-spacing: -.9px;
        line-height: 1.25;
    }

    h2 {
        margin: 0 0 19px;
        color: #172b50;
        font-size: 18px;
        font-weight: 800;
    }

    .page-description {
        margin: 10px 0 0;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.7;
    }

    .container {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 0;
    }

    .stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        margin: 24px 0;
    }

    .card {
        min-width: 0;
        margin-top: 0;
        padding: 23px;
        border: 1px solid var(--border);
        border-radius: 15px;
        background: var(--surface);
        box-shadow: 0 5px 18px rgb(15 23 42 / 3%);
    }

    .stats .card {
        position: relative;
        overflow: hidden;
        padding: 23px 25px;
    }

    .stats .card::before {
        position: absolute;
        inset: 0 auto 0 0;
        width: 4px;
        background: var(--blue);
        content: "";
    }

    .stats .card:nth-child(2)::before {
        background: var(--green);
    }

    .label {
        color: var(--muted);
        font-size: 13px;
        font-weight: 650;
    }

    .number {
        margin-top: 8px;
        color: #152443;
        font-size: clamp(27px, 3vw, 34px);
        font-weight: 850;
        letter-spacing: -.8px;
        line-height: 1.25;
    }

    .table-wrap {
        width: 100%;
        overflow-x: auto;
        border: 1px solid var(--border);
        border-radius: 11px;
    }

    table {
        width: 100%;
        min-width: 900px;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
    }

    th,
    td {
        padding: 15px 14px;
        text-align: left;
        vertical-align: middle;
        border-bottom: 1px solid #e9eef5;
    }

    th {
        color: #475569;
        background: #f5f8fd;
        font-size: 11px;
        font-weight: 850;
        letter-spacing: .45px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    td {
        color: #334155;
        font-size: 13px;
    }

    tbody tr:last-child td {
        border-bottom: 0;
    }

    tbody tr:hover {
        background: #f8fbff;
    }

    td strong {
        color: #172b50;
        font-size: 13px;
        font-weight: 800;
    }

    .button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 39px;
        padding: 10px 15px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
        line-height: 1.2;
        white-space: nowrap;
        transition: background .18s ease, transform .18s ease;
    }

    .button:hover {
        transform: translateY(-1px);
    }

    .button-blue {
        color: #fff;
        background: var(--blue);
    }

    .button-blue:hover {
        background: #172f72;
    }

    .button-white {
        color: var(--blue);
        background: #fff;
        border-color: #dbe5f4;
    }

    .button-green {
        color: #fff;
        background: var(--green);
    }

    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 27px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    .actif {
        color: #166534;
        background: #dcfce7;
    }

    .inactif {
        color: #334155;
        background: #e2e8f0;
    }

    .expire {
        color: #991b1b;
        background: #fee2e2;
    }

    .warning,
    .success,
    .alert {
        padding: 15px 18px;
        border-radius: 11px;
        font-size: 13px;
        line-height: 1.7;
    }

    .warning {
        margin-top: 22px;
        color: #92400e;
        background: #fffbeb;
        border: 1px solid #fde68a;
    }

    .success,
    .alert {
        margin-bottom: 20px;
        color: #166534;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
    }

    .muted {
        color: var(--muted);
        font-size: 12px;
        overflow-wrap: anywhere;
    }

    .table-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 19px;
    }

    .table-pagination nav {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .table-pagination nav a,
    .table-pagination nav span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        min-height: 36px;
        padding: 7px 11px;
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--blue);
        background: #fff;
        font-size: 12px;
        font-weight: 700;
    }

    .table-pagination nav [aria-current="page"] span {
        color: #fff;
        background: var(--blue);
        border-color: var(--blue);
    }

    .table-pagination nav [aria-disabled="true"] span {
        color: #94a3b8;
        background: #f8fafc;
    }

    @media (min-width: 1500px) {
        .page {
            padding-right: 42px;
            padding-left: 42px;
        }

        .topbar-inner {
            padding-right: 42px;
            padding-left: 42px;
        }
    }

    @media (max-width: 1100px) {
        .sidebar {
            width: 230px;
        }

        .topbar {
            margin-left: 230px;
        }

        .page {
            margin-left: 230px;
            padding-right: 22px;
            padding-left: 22px;
        }

        .topbar-inner {
            padding-right: 22px;
            padding-left: 22px;
        }

        .top-actions {
            gap: 12px;
        }
    }

    @media (max-width: 760px) {
        .sidebar {
            position: relative;
            inset: auto;
            width: 100%;
            padding: 13px 15px;
            overflow: visible;
        }

        .sidebar-brand {
            padding: 0 4px 13px;
        }

        .sidebar-logo {
            width: 40px;
            height: 40px;
            flex-basis: 40px;
        }

        .sidebar-section-label {
            margin: 14px 4px 7px;
        }

        .sidebar-nav {
            display: flex;
            gap: 7px;
            overflow-x: auto;
            padding-bottom: 3px;
        }

        .sidebar-link {
            min-height: 42px;
            flex: 0 0 auto;
            gap: 8px;
            padding: 9px 11px;
            font-size: 12px;
        }

        .sidebar-link svg {
            width: 18px;
            height: 18px;
            flex-basis: 18px;
        }

        .sidebar-link.active {
            box-shadow: inset 0 -3px #4ade80;
        }

        .sidebar-note {
            display: none;
        }

        .topbar {
            margin-left: 0;
        }

        .topbar-inner {
            min-height: auto;
            padding: 14px 16px;
            align-items: flex-start;
            flex-direction: column;
            gap: 14px;
        }

        .brand-logo {
            width: 39px;
            height: 39px;
            flex-basis: 39px;
        }

        .top-actions {
            width: 100%;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .admin-email {
            max-width: 200px;
        }

        .page {
            margin-left: 0;
            padding: 24px 16px 32px;
        }

        .welcome {
            align-items: flex-start;
            flex-direction: column;
            gap: 12px;
        }

        .stats {
            grid-template-columns: 1fr;
            gap: 13px;
        }

        .card {
            padding: 17px;
            border-radius: 12px;
        }

        .table-wrap {
            border-radius: 9px;
        }
    }

    @media (max-width: 420px) {
        .top-actions {
            align-items: flex-start;
            flex-direction: column;
        }

        .logout-btn {
            min-height: 38px;
        }

        .page {
            padding-right: 12px;
            padding-left: 12px;
        }

        .card {
            padding: 14px;
        }
    }



        /* Modale de confirmation de déconnexion Super Admin */
        .sa-logout-overlay {
            position: fixed;
            inset: 0;
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgb(15 23 42 / 68%);
            backdrop-filter: blur(3px);
        }

        .sa-logout-overlay.is-open {
            display: flex;
        }

        .sa-logout-dialog {
            width: min(100%, 430px);
            padding: 28px;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 24px 80px rgb(0 0 0 / 28%);
            animation: saLogoutAppear .18s ease-out;
        }

        .sa-logout-icon {
            display: grid;
            width: 56px;
            height: 56px;
            place-items: center;
            margin-bottom: 18px;
            border-radius: 16px;
            background: #e5e7eb;
            color: #111827;
        }

        .sa-logout-dialog h2 {
            margin: 0 0 10px;
            color: #172033;
            font-size: 22px;
            font-weight: 800;
            line-height: 1.35;
        }

        .sa-logout-dialog p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
            line-height: 1.75;
        }

        .sa-logout-actions {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 26px;
        }

        .sa-logout-button {
            display: inline-flex;
            min-height: 43px;
            align-items: center;
            justify-content: center;
            padding: 11px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
            background: #fff;
            color: #172033;
            font: inherit;
            font-size: 13px;
            font-weight: 750;
            cursor: pointer;
            transition: background .15s ease, transform .15s ease;
        }

        .sa-logout-button:hover {
            background: #f1f5f9;
        }

        .sa-logout-button:focus-visible {
            outline: 3px solid rgb(17 24 39 / 28%);
            outline-offset: 2px;
        }

        .sa-logout-confirm {
            border-color: #111827;
            background: #111827;
            color: #fff;
        }

        .sa-logout-confirm:hover {
            background: #000000;
            border-color: #000000;
        }

        .sa-logout-confirm:disabled {
            opacity: .65;
            cursor: wait;
        }

        @keyframes saLogoutAppear {
            from { opacity: 0; transform: translateY(8px) scale(.985); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @media (max-width: 480px) {
            .sa-logout-dialog {
                padding: 23px;
            }

            .sa-logout-actions {
                flex-direction: column-reverse;
            }

            .sa-logout-button {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .sa-logout-dialog {
                animation: none;
            }
        }

    </style>
</head>

<body>
@php
    $admin = auth()->user();
    $profilePhoto = $admin && method_exists($admin, 'profilePhotoUrl')
        ? $admin->profilePhotoUrl()
        : null;
    $adminInitials = $admin && method_exists($admin, 'initials')
        ? $admin->initials()
        : 'SA';
@endphp

<aside class="sidebar" aria-label="Navigation Super Administration">
    <a href="{{ route('super-admin.dashboard') }}" class="sidebar-brand">
        <img
            class="sidebar-logo"
            src="{{ asset('images/LOGO_optima.jpg') }}"
            alt="Logo OPTIMASCHOOL"
        >
        <div>
            <div class="sidebar-brand-name">OPTIMASCHOOL</div>
            <div class="sidebar-brand-subtitle">Super Administration</div>
        </div>
    </a>

    <div class="sidebar-section-label">Menu principal</div>

    <nav class="sidebar-nav" aria-label="Menu principal">
        <a href="{{ route('super-admin.dashboard') }}" class="sidebar-link">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
            </svg>
            <span>Tableau de bord</span>
        </a>

        <a href="{{ route('super-admin.abonnements.index') }}"
           class="sidebar-link active" aria-current="page">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                <path d="M16 3v4M8 3v4M3 10h18M8 15h3"></path>
            </svg>
            <span>Abonnements</span>
        </a>

        <a href="{{ route('super-admin.abonnements.index') }}" class="sidebar-link">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                <path d="M2 10h20M6 15h4"></path>
            </svg>
            <span>Vérification des paiements</span>
        </a>
    </nav>

    <div class="sidebar-section-label">Administration</div>

    <nav class="sidebar-nav" aria-label="Administration">
        <a href="{{ route('super-admin.configuration.index') }}" class="sidebar-link">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="M19 13.5a7.5 7.5 0 0 0 0-3l2-1.5-2-3-2.3 1a8 8 0 0 0-2.6-1.5L13.7 3h-3.4L10 5.5A8 8 0 0 0 7.4 7L5 6 3 9l2 1.5a7.5 7.5 0 0 0 0 3L3 15l2 3 2.4-1a8 8 0 0 0 2.6 1.5l.3 2.5h3.4l.4-2.5a8 8 0 0 0 2.6-1.5l2.3 1 2-3z"></path>
            </svg>
            <span>Configuration</span>
        </a>
    </nav>

    <div class="sidebar-note">
        <strong>OPTIMASCHOOL</strong><br>
        Plateforme de gestion scolaire.<br>
        © {{ date('Y') }} Tous droits réservés.
    </div>
</aside>

<header class="topbar">
    <div class="topbar-inner">
        <a href="{{ route('super-admin.dashboard') }}" class="brand">
            <img
                class="brand-logo"
                src="{{ asset('images/LOGO_optima.jpg') }}"
                alt="Logo OPTIMASCHOOL"
            >
            <div>
                <div class="brand-title">OPTIMASCHOOL</div>
                <div class="brand-subtitle">Plateforme de gestion scolaire</div>
            </div>
        </a>

        <div class="top-actions">
            <div class="admin-profile">
                <div class="avatar">
                    @if($profilePhoto)
                        <img src="{{ $profilePhoto }}" alt="Photo de profil">
                    @else
                        {{ $adminInitials }}
                    @endif
                </div>
                <div>
                    <div class="admin-name">
                        {{ $admin->name ?? 'Super Administrateur' }}
                    </div>
                    <div class="admin-email">{{ $admin->email ?? '' }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" data-superadmin-logout-form>
                @csrf
                <button type="submit" class="logout-btn">
                    <svg width="17" height="17" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M10 17l5-5-5-5"></path>
                        <path d="M15 12H3"></path>
                        <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"></path>
                    </svg>
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</header>

<main class="page">
    <div class="welcome">
        <div>
            <div class="eyebrow">Espace administration</div>
            <h1>Gestion des abonnements</h1>
            <p class="page-description">
                Consultez les abonnements et vérifiez les paiements soumis
                par les établissements.
            </p>
        </div>
    </div>
    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <div class="stats">
        <section class="card">
            <div class="label">Établissements affichés</div>
            <div class="number">{{ $establishments->total() }}</div>
        </section>

        <section class="card">
            <div class="label">Paiements en attente de vérification</div>
            <div class="number">{{ $paiementsEnAttente }}</div>
        </section>
    </div>

    <section class="card">
        <h2>Établissements</h2>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Établissement</th>
                        <th>Administrateur</th>
                        <th>Statut</th>
                        <th>Expiration</th>
                        <th>Tarif mensuel</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($establishments as $establishment)
                        @php
                            $abonnement = $establishment->abonnement;

                            $statut = $abonnement?->statut ?? 'inactif';

                            if (
                                $abonnement?->date_expiration
                                && $abonnement->date_expiration->lt(today())
                            ) {
                                $statutAffiche = 'Expiré';
                                $badgeClass = 'expire';
                            } elseif ($statut === 'actif') {
                                $statutAffiche = 'Actif';
                                $badgeClass = 'actif';
                            } else {
                                $statutAffiche = 'Inactif';
                                $badgeClass = 'inactif';
                            }
                        @endphp

                        <tr>
                            <td>
                                <strong>{{ $establishment->nom }}</strong>
                                <br>
                                <span class="muted">
                                    {{ $establishment->email ?: 'Email non renseigné' }}
                                </span>
                            </td>

                            <td>
                                {{ $establishment->user?->name ?? 'Non renseigné' }}
                                <br>
                                <span class="muted">
                                    {{ $establishment->user?->email ?? '' }}
                                </span>
                            </td>

                            <td>
                                <span class="badge {{ $badgeClass }}">
                                    {{ $statutAffiche }}
                                </span>
                            </td>

                            <td>
                                @if ($abonnement?->date_expiration)
                                    {{ $abonnement->date_expiration->format('d/m/Y') }}
                                @else
                                    <span class="muted">Non définie</span>
                                @endif
                            </td>

                            <td>
                                @if ($abonnement?->montant_mensuel !== null)
                                    {{ number_format((float) $abonnement->montant_mensuel, 0, ',', ' ') }}
                                    {{ $abonnement->devise ?? 'MGA' }}
                                @else
                                    <span class="muted">Tarif non défini</span>
                                @endif
                            </td>

                            <td>
                                <a class="button button-blue"
                                   href="{{ route('super-admin.abonnements.show', $establishment) }}">
                                    Consulter
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                Aucun établissement enregistré.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-pagination">
            {{ $establishments->links() }}
        </div>
    </section>

    <div class="warning">
        <strong>Important :</strong>
        l'expiration d'un abonnement ne bloque pas l'utilisation
        d'OPTIMASCHOOL. Le statut sert au suivi et aux notifications.
    </div>
</main>

<div
    class="sa-logout-overlay"
    id="sa-superadmin-logout-modal"
    aria-hidden="true"
>
    <section
        class="sa-logout-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="sa-superadmin-logout-title"
        aria-describedby="sa-superadmin-logout-description"
        tabindex="-1"
    >
        <div class="sa-logout-icon" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="1.8"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 17l5-5-5-5"></path>
                <path d="M15 12H3"></path>
                <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"></path>
            </svg>
        </div>

        <h2 id="sa-superadmin-logout-title">Confirmer la déconnexion</h2>

        <p id="sa-superadmin-logout-description">
            Voulez-vous vraiment vous déconnecter de votre espace
            Super Administration ?
        </p>

        <div class="sa-logout-actions">
            <button
                type="button"
                class="sa-logout-button"
                data-sa-logout-cancel
            >
                Annuler
            </button>

            <button
                type="button"
                class="sa-logout-button sa-logout-confirm"
                data-sa-logout-confirm
            >
                Oui, me déconnecter
            </button>
        </div>
    </section>
</div>

<script>
(() => {
    const modal = document.getElementById('sa-superadmin-logout-modal');
    const dialog = modal?.querySelector('.sa-logout-dialog');
    const cancelButton = modal?.querySelector('[data-sa-logout-cancel]');
    const confirmButton = modal?.querySelector('[data-sa-logout-confirm]');
    const logoutForm = document.querySelector('[data-superadmin-logout-form]');

    if (!modal || !dialog || !cancelButton || !confirmButton || !logoutForm) {
        return;
    }

    let previousFocus = null;
    let isSubmitting = false;

    const openModal = () => {
        previousFocus = document.activeElement;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        cancelButton.focus();
    };

    const closeModal = () => {
        if (isSubmitting) return;

        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';

        if (previousFocus && typeof previousFocus.focus === 'function') {
            previousFocus.focus();
        }
    };

    logoutForm.addEventListener('submit', (event) => {
        if (isSubmitting) return;

        event.preventDefault();
        openModal();
    });

    cancelButton.addEventListener('click', closeModal);

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    confirmButton.addEventListener('click', () => {
        if (isSubmitting) return;

        isSubmitting = true;
        confirmButton.disabled = true;
        confirmButton.textContent = 'Déconnexion...';

        HTMLFormElement.prototype.submit.call(logoutForm);
    });

    document.addEventListener('keydown', (event) => {
        if (!modal.classList.contains('is-open')) return;

        if (event.key === 'Escape') {
            event.preventDefault();
            closeModal();
            return;
        }

        if (event.key === 'Tab') {
            const focusable = [
                cancelButton,
                confirmButton
            ].filter((element) => !element.disabled);

            if (!focusable.length) {
                event.preventDefault();
                dialog.focus();
                return;
            }

            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    });
})();
</script>

</body>
</html>
