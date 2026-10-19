<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Abonnement établissement | OPTIMASCHOOL</title>


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
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system,
                BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--ink);
            background: var(--background);
            font-size: 14px;
            line-height: 1.6;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        textarea {
            font: inherit;
        }

        button {
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
            padding: 25px 13px 0;
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
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 13px;
            min-width: 0;
        }

        .brand-title {
            margin: 0;
            color: var(--blue);
            font-size: 18px;
            font-weight: 850;
            letter-spacing: -.4px;
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
            display: grid;
            place-items: center;
            width: 43px;
            height: 43px;
            flex: 0 0 43px;
            overflow: hidden;
            border-radius: 50%;
            background: var(--blue-light);
            color: var(--blue);
            font-size: 14px;
            font-weight: 850;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-name {
            color: var(--ink);
            font-size: 13px;
            font-weight: 800;
        }

        .admin-email {
            max-width: 230px;
            overflow-wrap: anywhere;
            color: var(--muted);
            font-size: 11px;
        }

        .logout-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 41px;
            padding: 9px 13px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #fff;
            color: #475569;
            font-size: 12px;
            font-weight: 750;
        }

        .logout-btn:hover {
            border-color: #fecaca;
            background: #fef2f2;
            color: var(--red);
        }

        .container {
            width: auto;
            max-width: none;
            min-width: 0;
            margin: 0 0 0 258px;
            padding: 30px 32px 45px;
        }

        .page-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 26px;
        }

        .eyebrow {
            margin-bottom: 6px;
            color: var(--green);
            font-size: 11px;
            font-weight: 850;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }

        .page-heading h1 {
            margin: 0;
            color: var(--blue);
            font-size: clamp(23px, 2.3vw, 31px);
            font-weight: 850;
            line-height: 1.25;
            letter-spacing: -.7px;
            overflow-wrap: anywhere;
        }

        .page-heading p {
            max-width: 720px;
            margin: 9px 0 0;
            color: var(--muted);
            font-size: 13px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 41px;
            padding: 10px 15px;
            border: 1px solid transparent;
            border-radius: 9px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 750;
            line-height: 1.4;
            transition: background .18s ease, transform .18s ease;
        }

        .button:hover {
            transform: translateY(-1px);
        }

        .blue {
            background: var(--blue);
            color: white;
        }

        .blue:hover {
            background: var(--blue-dark);
        }

        .green {
            background: var(--green);
            color: white;
        }

        .green:hover {
            background: #15803d;
        }

        .red {
            background: #b91c1c;
            color: white;
        }

        .red:hover {
            background: #991b1b;
        }

        .light {
            border-color: var(--border);
            background: white;
            color: var(--blue);
        }

        .light:hover {
            background: var(--blue-light);
        }

        .card {
            min-width: 0;
            margin-bottom: 22px;
            padding: 25px;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: var(--surface);
            box-shadow: 0 5px 18px rgb(15 23 42 / 3%);
        }

        .card h2 {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 22px;
            color: var(--blue);
            font-size: 18px;
            font-weight: 850;
            letter-spacing: -.3px;
        }

        .card h2::before {
            width: 4px;
            height: 23px;
            flex: 0 0 4px;
            border-radius: 5px;
            background: var(--green);
            content: "";
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .grid > div {
            min-width: 0;
            padding: 17px;
            border: 1px solid #edf1f7;
            border-radius: 11px;
            background: #f8faff;
        }

        .label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 650;
        }

        .value {
            margin-top: 7px;
            color: var(--ink);
            font-size: 16px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .muted {
            color: var(--muted);
        }

        .card > .muted {
            margin: 20px 0 0;
            font-size: 12px;
            line-height: 1.8;
        }

        .success,
        .error {
            padding: 15px 17px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 11px;
            font-size: 13px;
        }

        .success {
            border-color: #bbf7d0;
            background: var(--green-light);
            color: #166534;
        }

        .error {
            border-color: #fecaca;
            background: #fef2f2;
            color: #991b1b;
        }

        .error ul {
            margin-bottom: 0;
            padding-left: 21px;
        }

        .tarif-description {
            margin: -8px 0 22px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .tarif-form {
            max-width: 620px;
            padding: 21px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #f8faff;
        }

        .field-label {
            display: block;
            margin-bottom: 8px;
            color: var(--ink);
            font-size: 13px;
            font-weight: 750;
        }

        input[type="number"],
        textarea {
            width: 100%;
            min-height: 45px;
            padding: 12px 13px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            outline: none;
            background: white;
            color: var(--ink);
            font-size: 14px;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        input[type="number"]:focus,
        textarea:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgb(30 58 138 / 10%);
        }

        textarea {
            min-height: 85px;
            resize: vertical;
        }

        .field-error {
            margin-top: 7px;
            color: var(--red);
            font-size: 12px;
        }

        .field-hint {
            margin-top: 8px;
            color: var(--muted);
            font-size: 12px;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
            border: 1px solid var(--border);
            border-radius: 11px;
        }

        table {
            width: 100%;
            min-width: 1000px;
            border-collapse: collapse;
            background: white;
        }

        th,
        td {
            padding: 15px 13px;
            border-bottom: 1px solid #edf1f7;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f1f5fb;
            color: #475569;
            font-size: 11px;
            font-weight: 850;
            letter-spacing: .45px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        td {
            color: #334155;
            font-size: 12px;
            line-height: 1.7;
        }

        tbody tr:last-child td {
            border-bottom: 0;
        }

        tbody tr:hover {
            background: #fbfdff;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 850;
            white-space: nowrap;
        }

        .pending {
            background: #fef3c7;
            color: #92400e;
        }

        .valid {
            background: #dcfce7;
            color: #166534;
        }

        .rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .inactive {
            background: #e2e8f0;
            color: #334155;
        }

        .action-form {
            min-width: 180px;
            margin-bottom: 13px;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
        }

        .action-form:last-child {
            margin-bottom: 0;
        }

        .action-form label {
            display: block;
            margin-bottom: 6px;
            color: #475569;
            font-size: 11px;
            font-weight: 750;
        }

        .action-form .button {
            width: 100%;
            margin-top: 9px;
        }

        .empty-state {
            padding: 30px !important;
            color: var(--muted);
            text-align: center;
        }

        @media (min-width: 1600px) {
            .container {
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

            .container {
                margin-left: 230px;
                padding: 25px 22px 35px;
            }

            .topbar-inner {
                padding-right: 22px;
                padding-left: 22px;
            }

            .admin-email {
                max-width: 150px;
            }

            .top-actions {
                gap: 12px;
            }

            .card {
                padding: 21px;
            }
        }

        @media (max-width: 760px) {
            .sidebar {
                position: relative;
                inset: auto;
                width: 100%;
                max-height: none;
                padding: 13px 14px;
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
                margin: 15px 5px 8px;
            }

            .sidebar-nav {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
            }

            .sidebar-link {
                min-height: 40px;
                padding: 9px 10px;
                font-size: 12px;
            }

            .sidebar-link svg {
                width: 17px;
                height: 17px;
                flex-basis: 17px;
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
                min-height: 70px;
                padding: 13px 16px;
                gap: 12px;
            }

            .brand-title {
                font-size: 15px;
            }

            .brand-subtitle {
                font-size: 11px;
            }

            .top-actions {
                gap: 8px;
            }

            .admin-profile {
                gap: 7px;
            }

            .avatar {
                width: 37px;
                height: 37px;
                flex-basis: 37px;
            }

            .admin-name {
                max-width: 120px;
                overflow-wrap: anywhere;
                font-size: 11px;
            }

            .admin-email {
                display: none;
            }

            .logout-btn {
                min-height: 37px;
                padding: 8px;
                font-size: 0;
            }

            .logout-btn svg {
                width: 19px;
                height: 19px;
            }

            .container {
                margin-left: 0;
                padding: 22px 15px 32px;
            }

            .page-heading {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
            }

            .page-heading .button {
                align-self: flex-start;
            }

            .page-heading h1 {
                font-size: 25px;
            }

            .grid {
                grid-template-columns: minmax(0, 1fr);
                gap: 11px;
            }

            .card {
                padding: 18px 15px;
                margin-bottom: 16px;
                border-radius: 12px;
            }

            .card h2 {
                font-size: 16px;
                margin-bottom: 17px;
            }

            .tarif-form {
                padding: 15px;
            }

            .table-wrap {
                border-radius: 8px;
            }
        }

        @media (max-width: 420px) {
            .sidebar-link {
                flex: 1 1 100%;
            }

            .topbar-inner {
                align-items: flex-start;
            }

            .brand-subtitle {
                max-width: 160px;
            }

            .admin-profile > div:not(.avatar) {
                display: none;
            }

            .page-heading h1 {
                font-size: 22px;
            }

            .container {
                padding-right: 12px;
                padding-left: 12px;
            }

            .button {
                max-width: 100%;
                white-space: normal;
                text-align: center;
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
                <path d="m19.4 15 .1.1a1.7 1.7 0 0 1-2.4 2.4l-.1-.1a1.7 1.7 0 0 0-2.9 1.2v.2a1.7 1.7 0 0 1-3.4 0v-.2a1.7 1.7 0 0 0-2.9-1.2l-.1.1a1.7 1.7 0 0 1-2.4-2.4l.1-.1a1.7 1.7 0 0 0-1.2-2.9H4a1.7 1.7 0 0 1 0-3.4h.2a1.7 1.7 0 0 0 1.2-2.9l-.1-.1a1.7 1.7 0 0 1 2.4-2.4l.1.1a1.7 1.7 0 0 0 2.9-1.2V2a1.7 1.7 0 0 1 3.4 0v.2a1.7 1.7 0 0 0 2.9 1.2l.1-.1a1.7 1.7 0 0 1 2.4 2.4l-.1.1a1.7 1.7 0 0 0 1.2 2.9h.2a1.7 1.7 0 0 1 0 3.4h-.2a1.7 1.7 0 0 0-1.2 2.9Z"></path>
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
                    <div class="admin-name">{{ $admin->name ?? 'Super Administrateur' }}</div>
                    <div class="admin-email">{{ $admin->email ?? '' }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" data-superadmin-logout-form>
                @csrf
                <button type="submit" class="logout-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24"
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


<main class="container">
    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="error">
            <strong>Une erreur est survenue :</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="page-heading">
        <div>
            <div class="eyebrow">Gestion des abonnements</div>
            <h1>{{ $establishment->nom }}</h1>
            <p>Consultez les informations de l’établissement, gérez son tarif mensuel et vérifiez ses paiements.</p>
        </div>

        <a class="button light"
           href="{{ route('super-admin.abonnements.index') }}">
            <svg width="17" height="17" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round"
                 aria-hidden="true">
                <path d="m15 18-6-6 6-6"></path>
            </svg>
            Retour à la liste
        </a>
    </div>


    <section class="card">
        <h2>Informations de l'établissement</h2>

        <div class="grid">
            <div>
                <div class="label">Administrateur</div>
                <div class="value">
                    {{ $establishment->user?->name ?? 'Non renseigné' }}
                </div>
            </div>

            <div>
                <div class="label">Email administrateur</div>
                <div class="value">
                    {{ $establishment->user?->email ?? 'Non renseigné' }}
                </div>
            </div>

            <div>
                <div class="label">Adresse</div>
                <div class="value">
                    {{ $establishment->adresse ?: 'Non renseignée' }}
                </div>
            </div>

            <div>
                <div class="label">Téléphone</div>
                <div class="value">
                    {{ $establishment->telephone ?: 'Non renseigné' }}
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <h2>État de l'abonnement</h2>

        <div class="grid">
            <div>
                <div class="label">Statut enregistré</div>
                <div class="value">
                    <span class="badge {{ in_array($abonnement->statut, ['actif', 'active', 'valide']) ? 'valid' : (in_array($abonnement->statut, ['expire', 'expiré', 'suspendu']) ? 'rejected' : 'inactive') }}">{{ ucfirst($abonnement->statut) }}</span>
                </div>
            </div>

            <div>
                <div class="label">Date de début</div>
                <div class="value">
                    {{ $abonnement->date_debut?->format('d/m/Y') ?? 'Non définie' }}
                </div>
            </div>

            <div>
                <div class="label">Date d'expiration</div>
                <div class="value">
                    {{ $abonnement->date_expiration?->format('d/m/Y') ?? 'Non définie' }}
                </div>
            </div>

            <div>
                <div class="label">Tarif mensuel</div>
                <div class="value">
                    @if ($abonnement->montant_mensuel !== null)
                        {{ number_format((float) $abonnement->montant_mensuel, 0, ',', ' ') }}
                        {{ $abonnement->devise }}
                    @else
                        Tarif non défini
                    @endif
                </div>
            </div>
        </div>

        <p class="muted">
            La validation d'un paiement en attente renouvelle l'abonnement
            d'un mois. Aucun tarif fixe n'est imposé par cette page.
        </p>
    </section>

    <section class="card">
        <h2>Configurer le tarif mensuel</h2>

        <p class="muted">
            Définissez le montant mensuel applicable à cet établissement.
            Le tarif enregistré sera utilisé lors des prochaines demandes
            de paiement de son administrateur.
        </p>

        <form
            method="POST"
            action="{{ route('super-admin.abonnements.tarif', $establishment) }}"
        >
            @csrf

            <div class="tarif-form">
                <label
                    for="montant_mensuel"
                    class="field-label"
                >
                    Tarif mensuel (MGA)
                </label>

                <input
                    id="montant_mensuel"
                    name="montant_mensuel"
                    type="number"
                    min="0.01"
                    max="9999999999.99"
                    step="0.01"
                    required
                    value="{{ old('montant_mensuel', $abonnement->montant_mensuel) }}"
                    placeholder="Saisir le tarif mensuel"

                >

                @error('montant_mensuel')
                    <div class="field-error">
                        {{ $message }}
                    </div>
                @enderror

                <div class="field-hint">
                    Aucun montant n'est imposé par défaut.
                </div>

                <button
                    type="submit"
                    class="button blue"
                    onclick="return confirm('Confirmer l’enregistrement de ce tarif mensuel ?');"
                >
                    Enregistrer le tarif
                </button>
            </div>
        </form>
    </section>

    <section class="card">
        <h2>Historique des paiements</h2>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date de soumission</th>
                        <th>Montant</th>
                        <th>Mode</th>
                        <th>Référence</th>
                        <th>Statut</th>
                        <th>Vérification</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($abonnement->paiements->sortByDesc('created_at') as $paiement)
                        @php
                            $badge = match ($paiement->statut) {
                                'valide' => 'valid',
                                'rejete' => 'rejected',
                                default => 'pending',
                            };
                        @endphp

                        <tr>
                            <td>
                                {{ $paiement->date_soumission?->format('d/m/Y H:i') ?? $paiement->created_at?->format('d/m/Y H:i') ?? '—' }}
                            </td>

                            <td>
                                {{ number_format((float) $paiement->montant, 0, ',', ' ') }}
                                {{ $abonnement->devise ?? 'MGA' }}
                            </td>

                            <td>{{ $paiement->mode_paiement }}</td>

                            <td>
                                {{ $paiement->reference_transaction ?: 'Non renseignée' }}
                            </td>

                            <td>
                                <span class="badge {{ $badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $paiement->statut)) }}
                                </span>
                            </td>

                            <td>
                                {{ $paiement->verificateur?->name ?? '—' }}

                                @if ($paiement->date_verification)
                                    <br>
                                    <span class="muted">
                                        {{ $paiement->date_verification->format('d/m/Y H:i') }}
                                    </span>
                                @endif

                                @if ($paiement->note_verification)
                                    <br>
                                    <span class="muted">
                                        {{ $paiement->note_verification }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if ($paiement->preuve_paiement)
                                    <a
                                        class="button blue"
                                        href="{{ route('super-admin.abonnements.paiements.verification', $paiement) }}"
                                    >
                                        Vérifier le paiement
                                    </a>
                                @else
                                    <span class="muted">Aucun justificatif</span>
                                @endif

                                @if ($paiement->statut === 'en_attente')
                                    <form class="action-form"
                                          method="POST"
                                          action="{{ route('super-admin.abonnements.paiements.valider', $paiement) }}"
                                          onsubmit="return confirm('Confirmez-vous avoir vérifié la transaction réelle et vouloir valider ce paiement ?');">
                                        @csrf
                                        <button type="submit" class="button green">
                                            Valider le paiement
                                        </button>
                                    </form>

                                    <form class="action-form"
                                          method="POST"
                                          action="{{ route('super-admin.abonnements.paiements.rejeter', $paiement) }}"
                                          onsubmit="return confirm('Confirmez-vous le refus de ce paiement ?');">
                                        @csrf

                                        <label for="note-{{ $paiement->id }}">
                                            Motif du refus
                                        </label>

                                        <textarea
                                            id="note-{{ $paiement->id }}"
                                            name="note_verification"
                                            required
                                            maxlength="2000"
                                            placeholder="Expliquez pourquoi le paiement est refusé."
                                        ></textarea>

                                        <button type="submit" class="button red">
                                            Refuser le paiement
                                        </button>
                                    </form>
                                @else
                                    <span class="muted">Déjà traité</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                Aucun paiement soumis pour cet abonnement.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
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
