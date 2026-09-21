<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Choisir un établissement - OPTIMA SCHOOL</title>

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
            background: #f4f7fb;
            color: #172033;
        }

        body {
            min-height: 100vh;
        }

        .page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* =========================================================
           HEADER
        ========================================================= */

        .header {
            height: 76px;
            background: #ffffff;
            border-bottom: 1px solid #e4e9f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 34px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 11px;
            background: #1e3a8a;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }

        .brand-title {
            color: #1e3a8a;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0.2px;
        }

        .brand-subtitle {
            color: #64748b;
            font-size: 12px;
            margin-top: 3px;
        }

        .logout-form {
            margin: 0;
        }

        .logout-button {
            border: 1px solid #dbe2ea;
            background: #ffffff;
            color: #334155;
            min-height: 40px;
            padding: 0 15px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition:
                background 0.2s ease,
                border-color 0.2s ease,
                color 0.2s ease;
        }

        .logout-button:hover {
            background: #fef2f2;
            border-color: #fecaca;
            color: #b91c1c;
        }

        .logout-button svg {
            width: 18px;
            height: 18px;
        }

        /* =========================================================
           MAIN
        ========================================================= */

        .main {
            width: 100%;
            max-width: 1180px;
            margin: 0 auto;
            padding: 42px 28px 55px;
            flex: 1;
        }

        .page-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 25px;
            margin-bottom: 30px;
        }

        .heading-left {
            max-width: 700px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #16a34a;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 9px;
        }

        .eyebrow svg {
            width: 16px;
            height: 16px;
        }

        .page-title {
            margin: 0;
            color: #172033;
            font-size: 30px;
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: -0.6px;
        }

        .page-description {
            margin: 10px 0 0;
            color: #64748b;
            font-size: 15px;
            line-height: 1.6;
        }

        .create-button {
            flex-shrink: 0;
            min-height: 44px;
            padding: 0 18px;
            background: #1e3a8a;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            font-size: 14px;
            font-weight: 700;
            border: 1px solid #1e3a8a;
            transition:
                background 0.2s ease,
                transform 0.2s ease;
        }

        .create-button:hover {
            background: #172f70;
            transform: translateY(-1px);
        }

        .create-button svg {
            width: 18px;
            height: 18px;
        }

        /* =========================================================
           ALERTS
        ========================================================= */

        .alert {
            width: 100%;
            border-radius: 9px;
            padding: 14px 16px;
            margin-bottom: 22px;
            display: flex;
            align-items: flex-start;
            gap: 11px;
            font-size: 14px;
            line-height: 1.5;
        }

        .alert svg {
            width: 19px;
            height: 19px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        /* =========================================================
           ESTABLISHMENT GRID
        ========================================================= */

        .establishments-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .establishment-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 21px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, 0.05);
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                transform 0.2s ease;
        }

        .establishment-card:hover {
            border-color: #bfdbfe;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.08);
            transform: translateY(-2px);
        }

        .establishment-card.current {
            border-color: #16a34a;
            box-shadow:
                0 0 0 1px #16a34a,
                0 8px 22px rgba(22, 163, 74, 0.08);
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
        }

        .establishment-info {
            min-width: 0;
            display: flex;
            gap: 13px;
            align-items: flex-start;
        }

        .establishment-icon {
            width: 46px;
            height: 46px;
            flex-shrink: 0;
            border-radius: 10px;
            background: #eff6ff;
            color: #1e3a8a;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .establishment-icon svg {
            width: 24px;
            height: 24px;
        }

        .establishment-name {
            margin: 1px 0 5px;
            color: #172033;
            font-size: 17px;
            line-height: 1.35;
            font-weight: 750;
            word-break: break-word;
        }

        .school-type {
            display: inline-flex;
            align-items: center;
            padding: 4px 9px;
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            border-radius: 999px;
            color: #15803d;
            font-size: 11px;
            font-weight: 700;
        }

        .current-badge {
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 9px;
            border-radius: 999px;
            background: #dcfce7;
            color: #166534;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .current-badge svg {
            width: 14px;
            height: 14px;
        }

        .card-details {
            margin-top: 18px;
            padding-top: 16px;
            border-top: 1px solid #edf1f5;
            display: grid;
            gap: 9px;
        }

        .detail-row {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            color: #64748b;
            font-size: 13px;
            line-height: 1.45;
        }

        .detail-row svg {
            width: 17px;
            height: 17px;
            color: #94a3b8;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .detail-row span {
            word-break: break-word;
        }

        /* =========================================================
           ACTIONS
        ========================================================= */

        .card-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 19px;
            padding-top: 16px;
            border-top: 1px solid #edf1f5;
            flex-wrap: wrap;
        }

        .action-button {
            min-height: 38px;
            padding: 0 12px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition:
                background 0.2s ease,
                border-color 0.2s ease,
                color 0.2s ease;
        }

        .action-button svg {
            width: 17px;
            height: 17px;
        }

        .select-button {
            background: #1e3a8a;
            color: #ffffff;
            border: 1px solid #1e3a8a;
        }

        .select-button:hover {
            background: #172f70;
            border-color: #172f70;
        }

        .edit-button {
            background: #ffffff;
            color: #1e3a8a;
            border: 1px solid #bfdbfe;
        }

        .edit-button:hover {
            background: #eff6ff;
            border-color: #93c5fd;
        }

        .delete-button {
            background: #ffffff;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .delete-button:hover {
            background: #fef2f2;
            border-color: #fca5a5;
        }

        .disabled-button {
            background: #f1f5f9;
            color: #94a3b8;
            border: 1px solid #e2e8f0;
            cursor: not-allowed;
        }

        /* =========================================================
           EMPTY STATE
        ========================================================= */

        .empty-state {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 55px 25px;
            text-align: center;
            box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 17px;
            border-radius: 14px;
            background: #eff6ff;
            color: #1e3a8a;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-icon svg {
            width: 31px;
            height: 31px;
        }

        .empty-title {
            margin: 0;
            color: #172033;
            font-size: 20px;
            font-weight: 800;
        }

        .empty-description {
            max-width: 500px;
            margin: 9px auto 20px;
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }

        /* =========================================================
           DELETE MODAL
        ========================================================= */

        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 100;
            background: rgba(15, 23, 42, 0.52);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border-radius: 13px;
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.25);
            overflow: hidden;
            animation: modalIn 0.18s ease-out;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: translateY(8px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-header {
            padding: 21px 22px 14px;
            display: flex;
            align-items: flex-start;
            gap: 13px;
        }

        .modal-icon {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            border-radius: 10px;
            background: #fef2f2;
            color: #dc2626;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-icon svg {
            width: 22px;
            height: 22px;
        }

        .modal-title {
            margin: 0;
            color: #172033;
            font-size: 18px;
            font-weight: 800;
        }

        .modal-text {
            margin: 6px 0 0;
            color: #64748b;
            font-size: 13px;
            line-height: 1.55;
        }

        .modal-establishment {
            margin: 14px 22px 0;
            padding: 11px 13px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 14px;
            font-weight: 700;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 9px;
            padding: 20px 22px 21px;
        }

        .modal-button {
            min-height: 40px;
            padding: 0 15px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .cancel-button {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .cancel-button:hover {
            background: #f8fafc;
        }

        .confirm-delete-button {
            background: #dc2626;
            color: #ffffff;
            border: 1px solid #dc2626;
        }

        .confirm-delete-button:hover {
            background: #b91c1c;
            border-color: #b91c1c;
        }

        /* =========================================================
           FOOTER
        ========================================================= */

        .footer {
            border-top: 1px solid #e4e9f0;
            background: #ffffff;
            padding: 17px 25px;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 850px) {
            .header {
                padding: 0 20px;
            }

            .main {
                padding: 32px 20px 45px;
            }

            .page-heading {
                flex-direction: column;
                align-items: stretch;
            }

            .create-button {
                align-self: flex-start;
            }

            .establishments-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 560px) {
            .header {
                height: 68px;
                padding: 0 15px;
            }

            .brand-subtitle {
                display: none;
            }

            .brand-title {
                font-size: 16px;
            }

            .brand-logo {
                width: 40px;
                height: 40px;
            }

            .logout-button span {
                display: none;
            }

            .logout-button {
                width: 40px;
                padding: 0;
                justify-content: center;
            }

            .main {
                padding: 25px 14px 35px;
            }

            .page-title {
                font-size: 25px;
            }

            .page-description {
                font-size: 14px;
            }

            .establishment-card {
                padding: 17px;
            }

            .card-top {
                flex-direction: column;
            }

            .current-badge {
                align-self: flex-start;
            }

            .card-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .action-button {
                width: 100%;
            }

            .modal-actions {
                flex-direction: column-reverse;
            }

            .modal-button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="page">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <header class="header">

        <div class="brand">

            <div class="brand-logo">
                OS
            </div>

            <div class="brand-text">
                <div class="brand-title">
                    OPTIMA SCHOOL
                </div>

                <div class="brand-subtitle">
                    Gestion scolaire
                </div>
            </div>

        </div>

        {{-- Déconnexion --}}
        <form
            method="POST"
            action="{{ route('logout') }}"
            class="logout-form"
        >
            @csrf

            <button
                type="submit"
                class="logout-button"
                title="Se déconnecter"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 12h8.25m0 0-3-3m3 3-3 3"
                    />
                </svg>

                <span>Déconnexion</span>
            </button>
        </form>

    </header>


    {{-- =========================================================
         MAIN
    ========================================================== --}}
    <main class="main">

        {{-- Messages --}}
        @if(session('success'))
            <div class="alert alert-success">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                    />
                </svg>

                <div>
                    {{ session('success') }}
                </div>

            </div>
        @endif


        @if(session('error'))
            <div class="alert alert-error">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v3.75m0 3.75h.007v.008H12v-.008Z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M10.34 3.94 2.97 16.5A1.875 1.875 0 0 0 4.59 19.31h14.82a1.875 1.875 0 0 0 1.62-2.81L13.66 3.94a1.875 1.875 0 0 0-3.32 0Z"
                    />
                </svg>

                <div>
                    {{ session('error') }}
                </div>

            </div>
        @endif


        {{-- Titre --}}
        <div class="page-heading">

            <div class="heading-left">

                <div class="eyebrow">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.75 21h16.5M4.5 18.75V9.5L12 4l7.5 5.5v9.25M8.25 21v-5.25h7.5V21"
                        />
                    </svg>

                    Gestion des établissements

                </div>

                <h1 class="page-title">
                    Choisir un établissement
                </h1>

                <p class="page-description">
                    Sélectionnez l’établissement que vous souhaitez gérer,
                    ou utilisez les actions disponibles pour le modifier
                    ou le supprimer.
                </p>

            </div>


            {{-- Créer --}}
            <a
                href="{{ route('establishments.create') }}"
                class="create-button"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 4.5v15m7.5-7.5h-15"
                    />
                </svg>

                Nouvel établissement

            </a>

        </div>


        {{-- =====================================================
             LISTE DES ÉTABLISSEMENTS
        ====================================================== --}}

        @if($establishments->count())

            <div class="establishments-grid">

                @foreach($establishments as $establishment)

                    @php
                        $isCurrent =
                            (int) session('establishment_id')
                            ===
                            (int) $establishment->id;
                    @endphp

                    <article
                        class="establishment-card {{ $isCurrent ? 'current' : '' }}"
                    >

                        <div class="card-top">

                            <div class="establishment-info">

                                <div class="establishment-icon">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M3.75 21h16.5M4.5 18.75V9.5L12 4l7.5 5.5v9.25M8.25 21v-5.25h7.5V21"
                                        />
                                    </svg>

                                </div>

                                <div>

                                    <h2 class="establishment-name">
                                        {{ $establishment->nom }}
                                    </h2>

                                    @if($establishment->schoolType)
                                        <span class="school-type">
                                            {{ $establishment->schoolType->nom }}
                                        </span>
                                    @endif

                                </div>

                            </div>


                            @if($isCurrent)

                                <span class="current-badge">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m4.5 12.75 6 6 9-13.5"
                                        />
                                    </svg>

                                    Actuellement sélectionné

                                </span>

                            @endif

                        </div>


                        {{-- Informations --}}
                        <div class="card-details">

                            @if($establishment->adresse)

                                <div class="detail-row">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M19.5 10.5c0 7.142-7.5 10.5-7.5 10.5S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"
                                        />
                                    </svg>

                                    <span>
                                        {{ $establishment->adresse }}
                                    </span>

                                </div>

                            @endif


                            @if($establishment->telephone)

                                <div class="detail-row">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.09l-4.423-1.106a1.125 1.125 0 0 0-1.173.417l-.97 1.293a1.125 1.125 0 0 1-1.21.38 12.03 12.03 0 0 1-7.54-7.54 1.125 1.125 0 0 1 .38-1.21l1.293-.97c.37-.278.535-.75.417-1.173L6.967 2.102A1.125 1.125 0 0 0 5.877 1.25H4.5A2.25 2.25 0 0 0 2.25 3.5v3.25Z"
                                        />
                                    </svg>

                                    <span>
                                        {{ $establishment->telephone }}
                                    </span>

                                </div>

                            @endif


                            @if($establishment->email)

                                <div class="detail-row">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25H4.5a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15A2.25 2.25 0 0 0 2.25 6.75m19.5 0-8.69 5.793a1.875 1.875 0 0 1-2.12 0L2.25 6.75"
                                        />
                                    </svg>

                                    <span>
                                        {{ $establishment->email }}
                                    </span>

                                </div>

                            @endif

                        </div>


                        {{-- =================================================
                             ACTIONS
                        ================================================== --}}
                        <div class="card-actions">

                            {{-- Sélectionner --}}
                            @if($isCurrent)

                                <span class="action-button disabled-button">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="2"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m4.5 12.75 6 6 9-13.5"
                                        />
                                    </svg>

                                    Sélectionné
                                </span>

                            @else

                                <a
                                    href="{{ route('establishments.select', $establishment) }}"
                                    class="action-button select-button"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12H3m0 0 4.5-4.5M3 12l4.5 4.5M21 4.5v15a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 15 19.5v-15A1.5 1.5 0 0 1 16.5 3h3A1.5 1.5 0 0 1 21 4.5Z"
                                        />
                                    </svg>

                                    Sélectionner

                                </a>

                            @endif


                            {{-- Modifier --}}
                            <a
                                href="{{ route('establishments.edit', $establishment) }}"
                                class="action-button edit-button"
                            >

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.8"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.687a1.875 1.875 0 1 1 2.652 2.652l-1.687 1.687M16.862 4.487 7.5 13.849 6.75 17.25l3.401-.75 9.363-9.363M16.862 4.487 19.5 7.125"
                                    />

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M19.5 13.5v5.25A2.25 2.25 0 0 1 17.25 21H6.75a2.25 2.25 0 0 1-2.25-2.25V8.25A2.25 2.25 0 0 1 6.75 6h5.25"
                                    />
                                </svg>

                                Modifier

                            </a>


                            {{-- Supprimer --}}
                            @if(!$isCurrent)

                                <button
                                    type="button"
                                    class="action-button delete-button"
                                    onclick="openDeleteModal(
                                        '{{ $establishment->id }}',
                                        @js($establishment->nom)
                                    )"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M6 7.5h12M9.75 7.5V5.25A1.25 1.25 0 0 1 11 4h2a1.25 1.25 0 0 1 1.25 1.25V7.5m-7.5 0 .75 12.25A2.25 2.25 0 0 0 9.745 22h4.51a2.25 2.25 0 0 0 2.245-2.25L17.25 7.5M10.5 11v6m3-6v6"
                                        />
                                    </svg>

                                    Supprimer

                                </button>

                            @else

                                <span
                                    class="action-button disabled-button"
                                    title="Sélectionnez d'abord un autre établissement"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 9v3.75m0 3.75h.007v.008H12v-.008Z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M10.34 3.94 2.97 16.5A1.875 1.875 0 0 0 4.59 19.31h14.82a1.875 1.875 0 0 0 1.62-2.81L13.66 3.94a1.875 1.875 0 0 0-3.32 0Z"
                                        />
                                    </svg>

                                    Sélectionné

                                </span>

                            @endif

                        </div>

                    </article>

                @endforeach

            </div>

        @else

            {{-- Aucun établissement --}}
            <div class="empty-state">

                <div class="empty-icon">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.7"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.75 21h16.5M4.5 18.75V9.5L12 4l7.5 5.5v9.25M8.25 21v-5.25h7.5V21"
                        />
                    </svg>

                </div>

                <h2 class="empty-title">
                    Aucun établissement
                </h2>

                <p class="empty-description">
                    Vous n'avez encore aucun établissement.
                    Commencez par créer votre premier établissement
                    pour accéder à la gestion scolaire.
                </p>

                <a
                    href="{{ route('establishments.create') }}"
                    class="create-button"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 4.5v15m7.5-7.5h-15"
                        />
                    </svg>

                    Créer mon établissement

                </a>

            </div>

        @endif

    </main>


    {{-- =========================================================
         FOOTER
    ========================================================== --}}
    <footer class="footer">
        © {{ date('Y') }} OPTIMA SCHOOL — Gestion scolaire
    </footer>

</div>


{{-- =============================================================
     MODAL DE CONFIRMATION SUPPRESSION
============================================================== --}}
<div
    id="deleteModal"
    class="modal-overlay"
    role="dialog"
    aria-modal="true"
    aria-labelledby="deleteModalTitle"
>

    <div class="modal">

        <div class="modal-header">

            <div class="modal-icon">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v3.75m0 3.75h.007v.008H12v-.008Z"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M10.34 3.94 2.97 16.5A1.875 1.875 0 0 0 4.59 19.31h14.82a1.875 1.875 0 0 0 1.62-2.81L13.66 3.94a1.875 1.875 0 0 0-3.32 0Z"
                    />
                </svg>

            </div>

            <div>

                <h2
                    id="deleteModalTitle"
                    class="modal-title"
                >
                    Supprimer l'établissement ?
                </h2>

                <p class="modal-text">
                    Cette action est définitive. Les données associées
                    à cet établissement peuvent également être concernées
                    selon les règles de votre base de données.
                </p>

            </div>

        </div>


        <div
            id="deleteEstablishmentName"
            class="modal-establishment"
        >
        </div>


        <div class="modal-actions">

            <button
                type="button"
                class="modal-button cancel-button"
                onclick="closeDeleteModal()"
            >
                Annuler
            </button>

            <form
                id="deleteEstablishmentForm"
                method="POST"
                style="margin: 0;"
            >
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="modal-button confirm-delete-button"
                >
                    Oui, supprimer
                </button>
            </form>

        </div>

    </div>

</div>


<script>
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteEstablishmentForm');
    const deleteName = document.getElementById('deleteEstablishmentName');

    function openDeleteModal(id, name) {
        deleteForm.action = `/establishments/${id}`;
        deleteName.textContent = name;

        deleteModal.classList.add('show');

        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        deleteModal.classList.remove('show');

        document.body.style.overflow = '';
    }

    deleteModal.addEventListener('click', function (event) {
        if (event.target === deleteModal) {
            closeDeleteModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>

</body>
</html>
