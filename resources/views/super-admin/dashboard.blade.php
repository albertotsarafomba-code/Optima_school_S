<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Super Administration | OPTIMASCHOOL</title>

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

        * { box-sizing: border-box; }

        html { min-height: 100%; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system,
                BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--ink);
            background: var(--background);
            font-size: 15px;
        }

        button, a { font: inherit; }
        a { color: inherit; text-decoration: none; }
        button { cursor: pointer; }

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
            margin: 0;
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
            padding: 17px 13px 0;
            color: #9fb1d2;
            font-size: 11px;
            line-height: 1.7;
        }

        .topbar {
            margin-left: 258px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .topbar-inner {
            width: 100%;
            min-height: 82px;
            margin: auto;
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

        .brand-logo { display: none; }

        .brand-title {
            margin: 0;
            color: var(--blue);
            font-size: 18px;
            font-weight: 850;
            letter-spacing: -.4px;
        }

        .brand-subtitle {
            margin-top: 4px;
            color: var(--muted);
            font-size: 12px;
        }

        .top-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 18px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
        }

        .avatar {
            width: 43px;
            height: 43px;
            flex: 0 0 43px;
            border: 2px solid #dbeafe;
            border-radius: 50%;
            display: grid;
            place-items: center;
            overflow: hidden;
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
            max-width: 210px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 13px;
            font-weight: 800;
        }

        .admin-email {
            max-width: 240px;
            margin-top: 4px;
            color: var(--muted);
            font-size: 11px;
            overflow-wrap: anywhere;
        }

        .logout-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 13px;
            border: 1px solid #fecaca;
            border-radius: 10px;
            background: #fff;
            color: var(--red);
            font-size: 12px;
            font-weight: 750;
            cursor: pointer;
            transition: background .18s ease;
        }

        .logout-btn:hover { background: #fef2f2; }

        .page {
            width: auto;
            max-width: 1700px;
            margin: 0 0 0 258px;
            padding: 30px 32px 24px;
        }

        .welcome {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 22px;
            margin-bottom: 27px;
        }

        .eyebrow {
            color: var(--green);
            text-transform: uppercase;
            letter-spacing: 1.4px;
            font-size: 10px;
            font-weight: 850;
        }

        h1 {
            margin: 8px 0;
            color: var(--blue-dark);
            font-size: clamp(27px, 2.5vw, 35px);
            letter-spacing: -1px;
            line-height: 1.2;
        }

        .welcome p {
            max-width: 700px;
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .primary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 12px 16px;
            border: 0;
            border-radius: 10px;
            background: var(--blue);
            color: #fff;
            font-size: 12px;
            font-weight: 750;
            white-space: nowrap;
            box-shadow: 0 5px 12px rgb(30 58 138 / 15%);
            transition: background .2s ease, transform .2s ease;
        }

        .primary-btn:hover {
            background: #172f70;
            transform: translateY(-1px);
        }

        .alert {
            margin-bottom: 20px;
            padding: 14px 16px;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            background: var(--green-light);
            color: #166534;
            line-height: 1.6;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 17px;
            margin-bottom: 24px;
        }

        .stat-card {
            position: relative;
            min-width: 0;
            padding: 21px;
            border: 1px solid #e6ebf3;
            border-radius: 15px;
            background: var(--surface);
            box-shadow: 0 5px 18px rgb(15 23 42 / 3%);
            transition: box-shadow .2s ease, transform .2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 9px 24px rgb(15 23 42 / 7%);
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .stat-label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 750;
            line-height: 1.5;
        }

        .stat-icon {
            width: 43px;
            height: 43px;
            display: grid;
            place-items: center;
            flex: 0 0 43px;
            border-radius: 12px;
            background: var(--blue-light);
            color: var(--blue);
        }

        .stat-icon.green {
            background: var(--green-light);
            color: var(--green);
        }

        .stat-icon.orange {
            background: #fffbeb;
            color: var(--orange);
        }

        .stat-icon svg {
            width: 23px;
            height: 23px;
            stroke: currentColor;
            stroke-width: 1.8;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .stat-number {
            margin-top: 17px;
            color: var(--blue-dark);
            font-size: clamp(26px, 2.3vw, 34px);
            line-height: 1.1;
            font-weight: 850;
            letter-spacing: -1px;
            overflow-wrap: anywhere;
        }

        .stat-foot {
            margin-top: 9px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.6;
        }

        .content-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(0, 1fr);
            gap: 21px;
            align-items: start;
        }

        .panel {
            min-width: 0;
            overflow: hidden;
            border: 1px solid #e5eaf2;
            border-radius: 15px;
            background: var(--surface);
            box-shadow: 0 5px 18px rgb(15 23 42 / 3%);
        }

        .panel-header {
            padding: 20px 21px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px solid #edf1f6;
        }

        .panel-title {
            margin: 0;
            color: var(--blue-dark);
            font-size: 15px;
            font-weight: 850;
        }

        .panel-subtitle {
            margin-top: 5px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.6;
        }

        .panel-link {
            color: var(--blue);
            font-size: 11px;
            font-weight: 850;
            white-space: nowrap;
        }

        .panel-link:hover { text-decoration: underline; }

        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 14px 16px;
            border-bottom: 1px solid #eef2f7;
            vertical-align: middle;
        }

        th {
            background: #f8fafc;
            color: #64748b;
            font-size: 10px;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        td {
            color: #334155;
            font-size: 12px;
        }

        tbody tr { transition: background .15s ease; }
        tbody tr:hover { background: #f8fbff; }
        tbody tr:last-child td { border-bottom: 0; }

        .cell-title {
            color: var(--ink);
            font-size: 12px;
            font-weight: 800;
        }

        .cell-subtitle {
            margin-top: 4px;
            color: var(--muted);
            font-size: 10px;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 9px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 850;
            white-space: nowrap;
        }

        .badge-green { background: #dcfce7; color: #166534; }
        .badge-orange { background: #fef3c7; color: #92400e; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        .badge-gray { background: #f1f5f9; color: #475569; }

        .empty {
            padding: 30px 20px;
            text-align: center;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .finance-card {
            margin-bottom: 21px;
            padding: 23px;
            border: 1px solid #bbf7d0;
            border-radius: 15px;
            background: #f0fdf4;
        }

        .finance-label {
            color: #166534;
            font-size: 12px;
            font-weight: 800;
            line-height: 1.6;
        }

        .finance-total {
            margin-top: 10px;
            color: #14532d;
            font-size: clamp(27px, 2.7vw, 36px);
            font-weight: 850;
            letter-spacing: -.8px;
            overflow-wrap: anywhere;
        }

        .finance-note {
            margin-top: 8px;
            color: #166534;
            font-size: 11px;
            line-height: 1.7;
        }

        .quick-links {
            display: grid;
            gap: 11px;
            padding: 17px;
        }

        .quick-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            border: 1px solid var(--border);
            border-radius: 12px;
            transition: border-color .2s ease, background .2s ease;
        }

        .quick-link:hover {
            border-color: #93c5fd;
            background: #f8fbff;
        }

        .quick-icon {
            width: 41px;
            height: 41px;
            flex: 0 0 41px;
            display: grid;
            place-items: center;
            border-radius: 11px;
            background: var(--blue-light);
            color: var(--blue);
        }

        .quick-icon svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .quick-title { font-size: 12px; font-weight: 850; }

        .quick-description {
            margin-top: 4px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.6;
        }

        .quick-arrow {
            margin-left: auto;
            color: var(--muted);
            font-size: 21px;
        }

        .footer {
            margin-top: 27px;
            padding: 18px 0 8px;
            border-top: 1px solid var(--border);
            color: var(--muted);
            text-align: center;
            font-size: 11px;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgb(15 23 42 / 65%);
        }

        .modal-overlay.is-open { display: flex; }

        .logout-modal {
            width: min(100%, 420px);
            padding: 27px;
            border: 1px solid #e5eaf2;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 24px 80px rgb(0 0 0 / 25%);
        }

        .modal-icon {
            width: 52px;
            height: 52px;
            display: grid;
            place-items: center;
            margin-bottom: 17px;
            border-radius: 14px;
            background: #fee2e2;
            color: var(--red);
            font-size: 25px;
        }

        .logout-modal h2 {
            margin: 0 0 9px;
            color: var(--ink);
            font-size: 21px;
        }

        .logout-modal p {
            margin: 0;
            color: var(--muted);
            line-height: 1.7;
            font-size: 14px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 25px;
        }

        .modal-btn {
            padding: 11px 15px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: #fff;
            color: var(--ink);
            font-weight: 750;
            cursor: pointer;
        }

        .modal-btn-danger {
            border-color: var(--red);
            background: var(--red);
            color: #fff;
        }

        .modal-btn:disabled { opacity: .65; cursor: wait; }

        @media (min-width: 1600px) {
            .page { padding-right: 42px; padding-left: 42px; }
        }

        @media (max-width: 1200px) {
            .sidebar { width: 230px; }
            .topbar { margin-left: 230px; }
            .page { margin-left: 230px; padding-right: 22px; padding-left: 22px; }
            .topbar-inner { padding-right: 22px; padding-left: 22px; }
            .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .content-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 760px) {
            .sidebar {
                position: relative;
                inset: auto;
                width: 100%;
                padding: 14px 15px;
                overflow: visible;
            }

            .sidebar-brand {
                padding: 0 4px 13px;
            }

            .sidebar-logo {
                width: 42px;
                height: 42px;
                flex-basis: 42px;
            }

            .sidebar-section-label { margin: 15px 5px 8px; }

            .sidebar-nav {
                display: flex;
                gap: 7px;
                overflow-x: auto;
                padding-bottom: 3px;
            }

            .sidebar-link {
                flex: 0 0 auto;
                min-height: 42px;
                padding: 10px 12px;
                font-size: 12px;
            }

            .sidebar-link.active { box-shadow: inset 0 -3px #4ade80; }

            .sidebar-note { display: none; }

            .topbar { margin-left: 0; }

            .topbar-inner {
                align-items: flex-start;
                flex-direction: column;
                padding: 15px;
                gap: 13px;
            }

            .brand-title { font-size: 16px; }
            .brand-subtitle { font-size: 11px; }

            .top-actions {
                width: 100%;
                justify-content: space-between;
                gap: 10px;
            }

            .admin-email { max-width: 190px; }
            .admin-name { max-width: 180px; }
            .logout-btn { padding: 10px; }

            .page {
                width: 100%;
                margin: 0;
                padding: 23px 15px 20px;
            }

            .welcome { flex-direction: column; }
            .welcome .primary-btn { white-space: normal; }
            .stats { gap: 12px; }
            .stat-card { padding: 16px; }
            .stat-top { align-items: flex-start; }
            .stat-icon { width: 38px; height: 38px; flex-basis: 38px; }
            .stat-number { margin-top: 15px; font-size: 27px; }
            .panel-header { padding: 16px; }
            th, td { padding: 12px; }
        }

        @media (max-width: 390px) {
            .stats { grid-template-columns: 1fr; }
            .admin-email { max-width: 155px; }
            .brand-title { font-size: 15px; }
            .top-actions { align-items: flex-start; }
            .logout-btn { font-size: 11px; }
        }

    </style>
</head>

<body>
@php
    $admin = $admin ?? auth()->user();
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
        <a href="{{ route('super-admin.dashboard') }}" class="sidebar-link active" aria-current="page">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
            </svg>
            <span>Tableau de bord</span>
        </a>

        <a href="{{ route('super-admin.abonnements.index') }}" class="sidebar-link">
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
            <img
                class="brand-logo"
                src="{{ asset('images/LOGO_optima.jpg') }}"
                alt="Logo OPTIMASCHOOL"
                onerror="this.onerror=null;this.src='{{ asset('images/LOGO_optima.jpg') }}';"
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
                    <div class="admin-name">{{ $admin->name ?? 'Super Administrateur' }}</div>
                    <div class="admin-email">{{ $admin->email ?? '' }}</div>
                </div>
            </div>

            <form
                method="POST"
                action="{{ route('logout') }}"
                data-superadmin-logout-form
            >
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

<main class="page">
    @if(session('success'))
        <div class="alert" role="status">
            {{ session('success') }}
        </div>
    @endif

    <section class="welcome">
        <div>
            <div class="eyebrow">Espace administration</div>
            <h1>Tableau de bord</h1>
            <p>
                Bienvenue, {{ $admin->name ?? 'Super Administrateur' }}.
                Gérez les établissements et surveillez les abonnements
                de la plateforme.
            </p>
        </div>

        <a href="{{ route('super-admin.abonnements.index') }}" class="primary-btn">
            <svg width="19" height="19" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round"
                 aria-hidden="true">
                <rect x="3" y="4" width="18" height="17" rx="2"></rect>
                <path d="M16 2v4M8 2v4M3 10h18"></path>
            </svg>
            Gérer les abonnements
        </a>
    </section>

    <section class="stats" aria-label="Statistiques principales">
        <article class="stat-card">
            <div class="stat-top">
                <div class="stat-label">Établissements</div>
                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 21h18M5 21V7l7-4 7 4v14"></path>
                        <path d="M9 10h1M14 10h1M9 14h1M14 14h1M10 21v-4h4v4"></path>
                    </svg>
                </div>
            </div>
            <div class="stat-number">{{ number_format($establishmentsCount, 0, ',', ' ') }}</div>
            <div class="stat-foot">Nombre total d'établissements enregistrés</div>
        </article>

        <article class="stat-card">
            <div class="stat-top">
                <div class="stat-label">Utilisateurs</div>
                <div class="stat-icon green">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
            </div>
            <div class="stat-number">{{ number_format($usersCount, 0, ',', ' ') }}</div>
            <div class="stat-foot">Comptes utilisateurs de la plateforme</div>
        </article>

        <article class="stat-card">
            <div class="stat-top">
                <div class="stat-label">Abonnements actifs</div>
                <div class="stat-icon green">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="m8 12 2.5 2.5L16 9"></path>
                    </svg>
                </div>
            </div>
            <div class="stat-number">{{ number_format($activeSubscriptionsCount, 0, ',', ' ') }}</div>
            <div class="stat-foot">Abonnements actifs non expirés</div>
        </article>

        <article class="stat-card">
            <div class="stat-top">
                <div class="stat-label">Paiements en attente</div>
                <div class="stat-icon orange">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 7v5l3 2"></path>
                    </svg>
                </div>
            </div>
            <div class="stat-number">{{ number_format($pendingPaymentsCount, 0, ',', ' ') }}</div>
            <div class="stat-foot">Paiements à vérifier et à traiter</div>
        </article>
    </section>

    <section class="content-grid">
        <div>
            <article class="finance-card">
                <div class="finance-label">Total des paiements d'abonnement validés</div>
                <div class="finance-total">
                    {{ number_format((float) $validatedPaymentsTotal, 0, ',', ' ') }} MGA
                </div>
                <div class="finance-note">
                    Seuls les paiements dont le statut est « validé »
                    sont inclus dans ce total.
                </div>
            </article>

            <article class="panel">
                <div class="panel-header">
                    <div>
                        <h2 class="panel-title">Établissements récents</h2>
                        <div class="panel-subtitle">
                            Les cinq derniers établissements enregistrés
                        </div>
                    </div>
                    <a href="{{ route('super-admin.abonnements.index') }}" class="panel-link">
                        Abonnements →
                    </a>
                </div>

                @if($recentEstablishments->isNotEmpty())
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Établissement</th>
                                    <th>Abonnement</th>
                                    <th>Expiration</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentEstablishments as $establishment)
                                    @php
                                        $subscription = $establishment->abonnement;
                                        $subscriptionStatus = $subscription->statut ?? 'inactif';
                                        $isExpired = $subscription
                                            && $subscription->date_expiration
                                            && $subscription->date_expiration->lt(now()->startOfDay());
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="cell-title">{{ $establishment->nom }}</div>
                                            <div class="cell-subtitle">
                                                {{ $establishment->email ?: 'Email non renseigné' }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($subscriptionStatus === 'actif' && !$isExpired)
                                                <span class="badge badge-green">Actif</span>
                                            @elseif($subscriptionStatus === 'actif' && $isExpired)
                                                <span class="badge badge-red">Expiré</span>
                                            @elseif($subscriptionStatus === 'en_attente')
                                                <span class="badge badge-orange">En attente</span>
                                            @else
                                                <span class="badge badge-gray">Inactif</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($subscription && $subscription->date_expiration)
                                                {{ $subscription->date_expiration->format('d/m/Y') }}
                                            @else
                                                <span class="cell-subtitle">Non définie</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty">
                        Aucun établissement enregistré pour le moment.
                    </div>
                @endif
            </article>
        </div>

        <div>
            <article class="panel">
                <div class="panel-header">
                    <div>
                        <h2 class="panel-title">Derniers abonnements</h2>
                        <div class="panel-subtitle">
                            Vue rapide sur les tarifs et les échéances
                        </div>
                    </div>
                    <a href="{{ route('super-admin.abonnements.index') }}" class="panel-link">
                        Tout voir →
                    </a>
                </div>

                @if($recentSubscriptions->isNotEmpty())
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Établissement</th>
                                    <th>Tarif / mois</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSubscriptions as $subscription)
                                    <tr>
                                        <td>
                                            <div class="cell-title">
                                                {{ $subscription->establishment->nom ?? 'Établissement supprimé' }}
                                            </div>
                                            <div class="cell-subtitle">
                                                Expiration :
                                                {{ $subscription->date_expiration
                                                    ? $subscription->date_expiration->format('d/m/Y')
                                                    : 'Non définie' }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($subscription->montant_mensuel !== null)
                                                <div class="cell-title">
                                                    {{ number_format((float) $subscription->montant_mensuel, 0, ',', ' ') }}
                                                </div>
                                                <div class="cell-subtitle">
                                                    {{ $subscription->devise ?: 'MGA' }}
                                                </div>
                                            @else
                                                <span class="badge badge-orange">Tarif non défini</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty">
                        Aucun abonnement n'est encore configuré.
                    </div>
                @endif
            </article>

            <article class="panel" style="margin-top: 22px;">
                <div class="panel-header">
                    <div>
                        <h2 class="panel-title">Accès rapide</h2>
                        <div class="panel-subtitle">Actions principales du Super Admin</div>
                    </div>
                </div>

                <div class="quick-links">
                    <a href="{{ route('super-admin.abonnements.index') }}" class="quick-link">
                        <span class="quick-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <rect x="3" y="4" width="18" height="17" rx="2"></rect>
                                <path d="M16 2v4M8 2v4M3 10h18M8 14h3"></path>
                            </svg>
                        </span>
                        <span>
                            <span class="quick-title">Gestion des abonnements</span>
                            <div class="quick-description">
                                Consulter les établissements, tarifs et paiements.
                            </div>
                        </span>
                        <span class="quick-arrow" aria-hidden="true">›</span>
                    </a>

                    <a href="{{ route('super-admin.abonnements.index') }}" class="quick-link">
                        <span class="quick-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                <path d="M2 10h20M6 15h4"></path>
                            </svg>
                        </span>
                        <span>
                            <span class="quick-title">Vérification des paiements</span>
                            <div class="quick-description">
                                Examiner les paiements en attente de validation.
                            </div>
                        </span>
                        <span class="quick-arrow" aria-hidden="true">›</span>
                    </a>
                </div>
            </article>
        </div>
    </section>

    <footer class="footer">
        © {{ date('Y') }} OPTIMASCHOOL — Super Administration.
        Tous droits réservés.
    </footer>
</main>

<div
    class="modal-overlay"
    id="superadmin-logout-modal"
    aria-hidden="true"
>
    <section
        class="logout-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="superadmin-logout-title"
        aria-describedby="superadmin-logout-description"
        tabindex="-1"
    >
        <div class="modal-icon" aria-hidden="true">!</div>
        <h2 id="superadmin-logout-title">Confirmer la déconnexion</h2>
        <p id="superadmin-logout-description">
            Voulez-vous vraiment vous déconnecter de votre espace
            Super Administration ?
        </p>

        <div class="modal-actions">
            <button type="button" class="modal-btn" data-superadmin-logout-cancel>
                Annuler
            </button>
            <button type="button" class="modal-btn modal-btn-danger" data-superadmin-logout-confirm>
                Oui, me déconnecter
            </button>
        </div>
    </section>
</div>

<script>
(() => {
    const modal = document.getElementById('superadmin-logout-modal');
    const dialog = modal?.querySelector('.logout-modal');
    const cancelButton = modal?.querySelector('[data-superadmin-logout-cancel]');
    const confirmButton = modal?.querySelector('[data-superadmin-logout-confirm]');
    const logoutForm = document.querySelector('[data-superadmin-logout-form]');

    if (!modal || !dialog || !logoutForm || !cancelButton || !confirmButton) {
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
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';

        if (previousFocus && typeof previousFocus.focus === 'function') {
            previousFocus.focus();
        }
    };

    logoutForm.addEventListener('submit', (event) => {
        if (isSubmitting) {
            return;
        }

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
        if (isSubmitting) {
            return;
        }

        isSubmitting = true;
        confirmButton.disabled = true;
        confirmButton.textContent = 'Déconnexion...';

        HTMLFormElement.prototype.submit.call(logoutForm);
    });

    document.addEventListener('keydown', (event) => {
        if (!modal.classList.contains('is-open')) {
            return;
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            closeModal();
            return;
        }

        if (event.key === 'Tab') {
            const focusable = [...dialog.querySelectorAll(
                'button:not([disabled]), [href], input:not([disabled]), [tabindex]:not([tabindex="-1"])'
            )];

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
