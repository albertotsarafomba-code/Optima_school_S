<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Mes établissements - OPTIMASCHOOL
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --primary: #1e3a8a;
            --primary-dark: #172f72;
            --primary-soft: #eff6ff;
            --green: #16a34a;
            --green-dark: #15803d;
            --green-soft: #f0fdf4;
            --danger: #dc2626;
            --danger-soft: #fef2f2;
            --text: #172033;
            --text-secondary: #475569;
            --muted: #64748b;
            --border: #e2e8f0;
            --border-light: #eef2f7;
            --surface: #ffffff;
            --background: #f5f7fb;
        }

        html {
            min-height: 100%;
        }

        body {
            position: relative;

            margin: 0;
            min-height: 100vh;
            padding: 0;

            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            background: #f5f7fb;

            color: var(--text);
            -webkit-font-smoothing: antialiased;
        }

        .school-background {
            position: fixed;
            inset: -18px;

            z-index: -2;

            background-image:
                url('{{ asset('images/school.jpg') }}');

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;

            filter: none;

            transform: scale(1.02);
        }

        .school-background-overlay {
            position: fixed;
            inset: 0;

            z-index: -1;

            background:
                rgba(248, 250, 252, 0.72);
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        button,
        a {
            -webkit-tap-highlight-color: transparent;
        }

        /* =========================================================
           HEADER
        ========================================================= */

        .top-header {
            position: sticky;
            top: 0;
            z-index: 100;

            min-height: 72px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 34px;

            background:
                rgba(255, 255, 255, 0.96);

            border-bottom:
                1px solid rgba(226, 232, 240, 0.9);

            box-shadow:
                0 1px 8px rgba(15, 23, 42, 0.035);

            backdrop-filter: blur(10px);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .brand-logo {
            width: 43px;
            height: 43px;

            object-fit: contain;
            border-radius: 50%;

            background: #ffffff;

            box-shadow:
                0 2px 7px rgba(15, 23, 42, 0.08);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }

        .brand-name {
            color: var(--primary);

            font-size: 17px;
            font-weight: 800;
            letter-spacing: 0.15px;
        }

        .brand-subtitle {
            margin-top: 4px;

            color: var(--muted);

            font-size: 12px;
            font-weight: 500;
        }

        .logout-form {
            margin: 0;
        }

        .logout-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            min-height: 39px;
            padding: 8px 13px;

            background: #ffffff;

            border: 1px solid #d7dee8;
            border-radius: 9px;

            color: #475569;

            font-size: 12px;
            font-weight: 650;

            cursor: pointer;

            transition:
                background .2s ease,
                border-color .2s ease,
                color .2s ease,
                box-shadow .2s ease;
        }

        .logout-button:hover {
            background: #f8fafc;
            border-color: #b8c4d3;
            color: var(--primary);

            box-shadow:
                0 3px 10px rgba(15, 23, 42, 0.06);
        }

        .logout-button svg {
            width: 17px;
            height: 17px;
        }

        /* =========================================================
           MAIN
        ========================================================= */

        .main-container {
            width: min(1180px, calc(100% - 48px));

            margin: 0 auto;

            padding: 42px 0 64px;
        }

        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;

            gap: 25px;

            margin-bottom: 27px;
        }

        .page-title-wrapper {
            min-width: 0;
        }

        .page-title {
            position: relative;

            margin: 0;

            color: var(--text);

            font-size: 29px;
            line-height: 1.2;
            font-weight: 800;

            letter-spacing: -0.4px;
        }

        .page-title::after {
            content: "";

            display: block;

            width: 38px;
            height: 3px;

            margin-top: 9px;

            background: var(--green);

            border-radius: 99px;
        }

        .page-description {
            margin: 10px 0 0;

            color: var(--muted);

            font-size: 14px;
            line-height: 1.6;
        }

        .create-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            flex-shrink: 0;

            min-height: 42px;
            padding: 9px 16px;

            background: var(--primary);

            border: 1px solid var(--primary);
            border-radius: 9px;

            color: #ffffff;

            text-decoration: none;

            font-size: 13px;
            font-weight: 700;

            box-shadow:
                0 5px 13px rgba(30, 58, 138, 0.16);

            transition:
                background .2s ease,
                border-color .2s ease,
                transform .2s ease,
                box-shadow .2s ease;
        }

        .create-button:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);

            transform: translateY(-1px);

            box-shadow:
                0 8px 18px rgba(30, 58, 138, 0.21);
        }

        .create-button svg {
            width: 17px;
            height: 17px;
        }

        /* =========================================================
           ALERTS
        ========================================================= */

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 11px;

            margin-bottom: 22px;

            padding: 13px 15px;

            border-radius: 10px;

            font-size: 13px;
            line-height: 1.5;

            box-shadow:
                0 2px 8px rgba(15, 23, 42, 0.025);
        }

        .alert-success {
            background: var(--green-soft);
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-error {
            background: var(--danger-soft);
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert svg {
            width: 18px;
            height: 18px;

            flex-shrink: 0;
            margin-top: 1px;
        }

        /* =========================================================
           ESTABLISHMENT GRID
        ========================================================= */

        .establishments-grid {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 20px;
        }

        /* =========================================================
           CARD
        ========================================================= */

        .establishment-card {
            position: relative;

            overflow: hidden;

            background: var(--surface);

            border: 1px solid var(--border);

            border-radius: 14px;

            box-shadow:
                0 2px 5px rgba(15, 23, 42, 0.025),
                0 10px 28px rgba(15, 23, 42, 0.035);

            transition:
                border-color .22s ease,
                box-shadow .22s ease,
                transform .22s ease;
        }

        .establishment-card::before {
            content: "";

            position: absolute;
            top: 0;
            left: 0;
            right: 0;

            height: 3px;

            background:
                linear-gradient(
                    90deg,
                    var(--primary),
                    var(--green)
                );

            opacity: .9;
        }

        .establishment-card:hover {
            border-color: #cbd5e1;

            transform: translateY(-2px);

            box-shadow:
                0 7px 16px rgba(15, 23, 42, 0.06),
                0 18px 38px rgba(15, 23, 42, 0.055);
        }

        .card-content {
            padding: 24px;
        }

        .card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;

            gap: 15px;

            margin-bottom: 19px;
        }

        .establishment-name {
            margin: 0;

            color: var(--text);

            font-size: 18px;
            line-height: 1.35;
            font-weight: 750;

            letter-spacing: -0.1px;

            word-break: break-word;
        }

        .school-type {
            display: inline-flex;
            align-items: center;

            margin-top: 8px;

            padding: 5px 9px;

            background: var(--primary-soft);

            border: 1px solid #dbeafe;
            border-radius: 7px;

            color: var(--primary);

            font-size: 11px;
            font-weight: 700;
        }

        .current-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;

            flex-shrink: 0;

            padding: 6px 9px;

            background: var(--green-soft);

            border: 1px solid #bbf7d0;
            border-radius: 7px;

            color: var(--green-dark);

            font-size: 11px;
            font-weight: 700;
        }

        .current-badge svg {
            width: 14px;
            height: 14px;
        }

        /* =========================================================
           CARD INFORMATION
        ========================================================= */

        .card-info {
            display: grid;
            gap: 11px;

            min-height: 82px;

            margin-bottom: 21px;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .info-icon {
            width: 17px;
            height: 17px;

            flex-shrink: 0;

            margin-top: 1px;

            color: #64748b;
        }

        .info-text {
            min-width: 0;

            color: var(--text-secondary);

            font-size: 13px;
            line-height: 1.5;

            word-break: break-word;
        }

        /* =========================================================
           CARD ACTIONS
        ========================================================= */

        .card-actions {
            display: flex;
            align-items: center;
            gap: 8px;

            padding-top: 16px;

            border-top:
                1px solid var(--border-light);
        }

        .action-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;

            min-height: 39px;

            padding: 8px 12px;

            border-radius: 8px;

            font-size: 12px;
            font-weight: 700;

            cursor: pointer;

            transition:
                background .2s ease,
                border-color .2s ease,
                color .2s ease,
                transform .2s ease,
                box-shadow .2s ease;
        }

        .action-button svg {
            width: 16px;
            height: 16px;
        }

        .select-button {
            flex: 1;

            background: var(--primary);

            border: 1px solid var(--primary);

            color: #ffffff;

            box-shadow:
                0 4px 10px rgba(30, 58, 138, 0.12);
        }

        .select-button:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);

            transform: translateY(-1px);

            box-shadow:
                0 6px 14px rgba(30, 58, 138, 0.17);
        }

        .edit-button {
            background: #ffffff;

            border: 1px solid #cbd5e1;

            color: #475569;
        }

        .edit-button:hover {
            background: #f8fafc;

            border-color: #94a3b8;

            color: var(--primary);
        }

        .delete-button {
            background: #ffffff;

            border: 1px solid #fecaca;

            color: var(--danger);
        }

        .delete-button:hover {
            background: var(--danger-soft);

            border-color: #fca5a5;

            color: #b91c1c;
        }

        .disabled-button {
            flex: 1;

            background: #f8fafc;

            border: 1px solid #e2e8f0;

            color: #94a3b8;

            cursor: default;
        }

        /* =========================================================
           MODAL OVERLAY
        ========================================================= */

        .modal-overlay {
            position: fixed;
            inset: 0;

            z-index: 1000;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;

            background:
                rgba(15, 23, 42, 0.58);

            opacity: 0;
            visibility: hidden;
            pointer-events: none;

            transition:
                opacity .22s ease,
                visibility .22s ease;

            backdrop-filter: blur(3px);
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .modal {
            width: min(540px, 100%);
            max-height: calc(100vh - 40px);

            overflow-y: auto;

            background: #ffffff;

            border: 1px solid #e2e8f0;
            border-radius: 15px;

            box-shadow:
                0 24px 70px rgba(15, 23, 42, 0.23);

            transform:
                translateY(10px)
                scale(.985);

            transition:
                transform .22s ease;
        }

        .modal-overlay.show .modal {
            transform:
                translateY(0)
                scale(1);
        }

        .modal-header {
            display: flex;
            align-items: flex-start;
            gap: 13px;

            padding: 22px 22px 18px;
        }

        .modal-icon {
            width: 41px;
            height: 41px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            border-radius: 10px;
        }

        .modal-icon svg {
            width: 21px;
            height: 21px;
        }

        .modal-title {
            margin: 0;

            color: var(--text);

            font-size: 17px;
            line-height: 1.35;
            font-weight: 750;
        }

        .modal-text {
            margin: 5px 0 0;

            color: var(--muted);

            font-size: 13px;
            line-height: 1.5;
        }

        /* =========================================================
           SELECT MODAL
        ========================================================= */

        .select-modal {
            width: min(470px, 100%);

            background:
                linear-gradient(
                    145deg,
                    #0f172a 0%,
                    #111827 58%,
                    #0b1220 100%
                );

            border:
                1px solid rgba(37, 99, 235, .45);

            border-radius: 17px;

            box-shadow:
                0 25px 70px rgba(0, 0, 0, .55),
                0 0 0 1px rgba(22, 163, 74, .08);
        }

        .select-modal-logo-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;

            padding: 25px 22px 10px;
        }

        .select-modal-logo {
            width: 64px;
            height: 64px;

            object-fit: contain;
            border-radius: 50%;

            background: #ffffff;
            padding: 3px;

            box-shadow:
                0 0 0 3px rgba(37, 99, 235, .20),
                0 0 0 6px rgba(22, 163, 74, .10),
                0 10px 25px rgba(0, 0, 0, .35);
        }

        .select-modal .modal-header {
            padding: 18px 24px 20px;
        }

        .select-modal-icon {
            background:
                rgba(37, 99, 235, .15);

            color: #60a5fa;

            border:
                1px solid rgba(37, 99, 235, .30);
        }

        .select-modal .modal-title {
            color: #ffffff;
            font-size: 18px;
            font-weight: 750;
        }

        .select-modal .modal-text {
            color: #94a3b8;
            line-height: 1.6;
        }

        .select-modal-establishment {
            margin: 0 24px 20px;

            padding: 16px 17px;

            background: #111827;

            border:
                1px solid rgba(37, 99, 235, .30);

            border-left:
                3px solid var(--green);

            border-radius: 10px;

            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, .03),
                0 8px 20px rgba(0, 0, 0, .20);
        }

        .select-modal-establishment-name {
            color: #ffffff;

            font-size: 15px;
            font-weight: 750;

            word-break: break-word;
        }

        .select-modal-establishment-type {
            margin-top: 5px;

            color: #60a5fa;

            font-size: 12px;
            font-weight: 600;
        }

        .select-modal .modal-actions {
            padding: 16px 24px 22px;

            border-top:
                1px solid rgba(148, 163, 184, .12);
        }

        .select-modal .cancel-button {
            background: #1e293b;

            border: 1px solid #334155;

            color: #cbd5e1;
        }

        .select-modal .cancel-button:hover {
            background: #273449;
            border-color: #475569;
            color: #ffffff;
        }

        .select-modal .confirm-select-button {
            position: relative;
            overflow: hidden;

            background: #2563eb;

            border: 1px solid #2563eb;

            color: #ffffff;

            box-shadow:
                0 6px 16px rgba(37, 99, 235, .30);
        }

        .select-modal .confirm-select-button:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;

            box-shadow:
                0 8px 20px rgba(37, 99, 235, .40);
        }

        .select-modal .confirm-select-button::after {
            content: "";

            position: absolute;

            left: 0;
            bottom: 0;

            width: 0;
            height: 3px;

            background: #22c55e;

            transition: width .25s ease;
        }

        .select-modal .confirm-select-button:hover::after {
            width: 100%;
        }

        /* =========================================================
           EDIT MODAL
        ========================================================= */

        .edit-modal-icon {
            background: var(--green-soft);
            color: var(--green-dark);
        }

        .form-container {
            padding: 0 22px 22px;
        }

        .form-grid {
            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            color: #334155;

            font-size: 12px;
            font-weight: 700;
        }

        .form-input,
        .form-select {
            width: 100%;
            min-height: 41px;

            padding: 9px 11px;

            background: #ffffff;

            border: 1px solid #cbd5e1;
            border-radius: 8px;

            color: var(--text);

            font-size: 13px;

            outline: none;

            transition:
                border-color .2s ease,
                box-shadow .2s ease,
                background .2s ease;
        }

        .form-input:hover,
        .form-select:hover {
            border-color: #aebaca;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: var(--primary);

            box-shadow:
                0 0 0 3px rgba(30, 58, 138, .10);
        }

        .form-input.input-error,
        .form-select.input-error {
            border-color: var(--danger);
            background: #fffafa;
        }

        .form-error {
            min-height: 17px;

            color: var(--danger);

            font-size: 11px;
            line-height: 1.4;
        }

        /* =========================================================
           DELETE MODAL
        ========================================================= */

        .delete-modal-icon {
            background: var(--danger-soft);
            color: var(--danger);
        }

        .delete-warning {
            margin: 0 22px 18px;

            padding: 13px 14px;

            background: #fff7ed;

            border: 1px solid #fed7aa;
            border-radius: 9px;

            color: #9a3412;

            font-size: 12px;
            line-height: 1.5;
        }

        /* =========================================================
           MODAL ACTIONS
        ========================================================= */

        .modal-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;

            gap: 9px;

            padding: 16px 22px 20px;

            border-top: 1px solid var(--border-light);
        }

        .modal-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-height: 40px;

            padding: 8px 15px;

            border-radius: 8px;

            font-size: 12px;
            font-weight: 700;

            cursor: pointer;

            text-decoration: none;

            transition:
                background .2s ease,
                border-color .2s ease,
                color .2s ease,
                transform .2s ease;
        }

        .cancel-button {
            background: #ffffff;

            border: 1px solid #cbd5e1;

            color: #475569;
        }

        .cancel-button:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .confirm-edit-button {
            background: var(--green-dark);

            border: 1px solid var(--green-dark);

            color: #ffffff;
        }

        .confirm-edit-button:hover {
            background: #166534;
            border-color: #166534;

            transform: translateY(-1px);
        }

        .confirm-delete-button {
            background: var(--danger);

            border: 1px solid var(--danger);

            color: #ffffff;
        }

        .confirm-delete-button:hover {
            background: #b91c1c;
            border-color: #b91c1c;

            transform: translateY(-1px);
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 900px) {

            .establishments-grid {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 650px) {

            .top-header {
                min-height: 68px;

                padding: 11px 18px;
            }

            .brand-subtitle {
                display: none;
            }

            .main-container {
                width: calc(100% - 28px);

                padding-top: 28px;
            }

            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .page-title {
                font-size: 26px;
            }

            .create-button {
                width: 100%;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: auto;
            }

            .card-actions {
                flex-wrap: wrap;
            }

            .select-button,
            .disabled-button {
                flex-basis: 100%;
            }

            .edit-button,
            .delete-button {
                flex: 1;
            }

        }

        @media (max-width: 450px) {

            .top-header {
                padding-left: 14px;
                padding-right: 14px;
            }

            .brand-name {
                font-size: 15px;
            }

            .logout-button {
                padding-left: 10px;
                padding-right: 10px;
            }

            .logout-button svg {
                width: 16px;
                height: 16px;
            }

            .main-container {
                width: calc(100% - 22px);

                padding-top: 23px;
                padding-bottom: 45px;
            }

            .card-content {
                padding: 20px;
            }

            .establishment-name {
                font-size: 17px;
            }

            .modal-overlay {
                padding: 12px;
            }

            .modal-header {
                padding: 18px 17px 15px;
            }

            .form-container {
                padding: 0 17px 18px;
            }

            .select-modal-establishment {
                margin-left: 17px;
                margin-right: 17px;
            }

            .select-modal .modal-actions {
                padding-left: 17px;
                padding-right: 17px;
            }

            .modal-button {
                flex: 1;
            }

        }

    </style>

</head>


<body>

    <div class="school-background" aria-hidden="true"></div>

    <div class="school-background-overlay" aria-hidden="true"></div>


    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <header class="top-header">

        <div class="brand">

            <img
                src="{{ asset('images/optimaschool-logo.png') }}"
                alt="OPTIMASCHOOL"
                class="brand-logo"
            >

            <div class="brand-text">

                <div class="brand-name">
                    OPTIMASCHOOL
                </div>

                <div class="brand-subtitle">
                    Gestion scolaire
                </div>

            </div>

        </div>


        <form
            method="POST"
            action="{{ route('logout') }}"
            class="logout-form"
        >

            @csrf

            <button
                type="submit"
                class="logout-button"
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
                        d="M18 12H9m9 0-3-3m3 3-3 3"
                    />

                </svg>

                Déconnexion

            </button>

        </form>

    </header>


    {{-- =========================================================
         MAIN
    ========================================================== --}}

    <main class="main-container">


        @if(session('success'))

            <div class="alert alert-success">

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
                        d="M9 12.75 11.25 15 15 9.75"
                    />

                    <circle
                        cx="12"
                        cy="12"
                        r="9"
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
                    stroke-width="1.8"
                    stroke="currentColor"
                >

                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 8v4m0 4h.01"
                    />

                </svg>

                <div>
                    {{ session('error') }}
                </div>

            </div>

        @endif


        @if($errors->any())

            <div class="alert alert-error">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.8"
                    stroke="currentColor"
                >

                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 8v4m0 4h.01"
                    />

                </svg>

                <div>

                    @foreach($errors->all() as $error)

                        <div>
                            {{ $error }}
                        </div>

                    @endforeach

                </div>

            </div>

        @endif


        {{-- PAGE HEADER --}}

        <div class="page-header">

            <div class="page-title-wrapper">

                <h1 class="page-title">
                    Mes établissements
                </h1>

                <p class="page-description">
                    Sélectionnez l'établissement que vous souhaitez gérer.
                </p>

            </div>


            @if(auth()->user()->role === 'admin')

                <a
                    href="{{ route('establishments.create') }}"
                    class="create-button"
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
                            d="M12 5v14M5 12h14"
                        />

                    </svg>

                    Nouvel établissement

                </a>

            @endif

        </div>


        {{-- =====================================================
             ESTABLISHMENTS
        ====================================================== --}}

        <div class="establishments-grid">

            @forelse($establishments as $establishment)

                @php

                    $isCurrent =
                        (int) session('establishment_id')
                        ===
                        (int) $establishment->id;

                @endphp


                <article class="establishment-card">

                    <div class="card-content">

                        <div class="card-top">

                            <div>

                                <h2 class="establishment-name">
                                    {{ $establishment->nom }}
                                </h2>

                                <div class="school-type">
                                    {{ $establishment->schoolType?->name ?? 'Type non défini' }}
                                </div>

                            </div>


                            @if($isCurrent)

                                <div class="current-badge">

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
                                            d="M9 12.75 11.25 15 15 9.75"
                                        />

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="9"
                                        />

                                    </svg>

                                    Actuel

                                </div>

                            @endif

                        </div>


                        <div class="card-info">

                            @if($establishment->adresse)

                                <div class="info-row">

                                    <svg
                                        class="info-icon"
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

                                    <div class="info-text">
                                        {{ $establishment->adresse }}
                                    </div>

                                </div>

                            @endif


                            @if($establishment->telephone)

                                <div class="info-row">

                                    <svg
                                        class="info-icon"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.09l-4.423-1.106a1.125 1.125 0 0 0-1.173.417l-.97 1.293c-.13.173-.351.236-.55.156a12.035 12.035 0 0 1-7.22-7.22c-.08-.199-.017-.42.156-.55l1.293-.97c.35-.263.51-.712.417-1.173L7.108 3.477A1.125 1.125 0 0 0 6.018 2.625H4.5A2.25 2.25 0 0 0 2.25 4.875v1.875Z"
                                        />

                                    </svg>

                                    <div class="info-text">
                                        {{ $establishment->telephone }}
                                    </div>

                                </div>

                            @endif


                            @if($establishment->email)

                                <div class="info-row">

                                    <svg
                                        class="info-icon"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25H4.5a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15A2.25 2.25 0 0 0 2.25 6.75m19.5 0-8.69 5.793a2 2 0 0 1-2.12 0L2.25 6.75"
                                        />

                                    </svg>

                                    <div class="info-text">
                                        {{ $establishment->email }}
                                    </div>

                                </div>

                            @endif

                        </div>


                        {{-- ACTIONS --}}

                        <div class="card-actions">

                            @if(!$isCurrent)

                                <button
                                    type="button"
                                    class="action-button select-button"
                                    onclick="openSelectModal(
                                        @js(route('establishments.select', $establishment)),
                                        @js($establishment->nom),
                                        @js($establishment->schoolType?->name)
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
                                            d="M9 12.75 11.25 15 15 9.75"
                                        />

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="9"
                                        />

                                    </svg>

                                    Sélectionner

                                </button>

                            @else

                                <div class="action-button disabled-button">

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
                                            d="M9 12.75 11.25 15 15 9.75"
                                        />

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="9"
                                        />

                                    </svg>

                                    Établissement actuel

                                </div>

                            @endif


                            @if(auth()->user()->role === 'admin')

                                <button
                                    type="button"
                                    class="action-button edit-button"
                                    onclick="openEditModal(
                                        @js(route('establishments.update', $establishment)),
                                        @js($establishment->school_type_id),
                                        @js($establishment->nom),
                                        @js($establishment->adresse),
                                        @js($establishment->telephone),
                                        @js($establishment->email)
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
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652l-9.193 9.193a4.5 4.5 0 0 1-1.897 1.13l-3.27.981.981-3.27a4.5 4.5 0 0 1 1.13-1.897l9.193-9.193Z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m18 7.5 3 3"
                                        />

                                    </svg>

                                    Modifier

                                </button>


                                @if(!$isCurrent)

                                    <button
                                        type="button"
                                        class="action-button delete-button"
                                        onclick="openDeleteModal(
                                            @js(route('establishments.destroy', $establishment))
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
                                                d="M6 7.5h12"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 7.5V5.25A1.25 1.25 0 0 1 10.25 4h3.5A1.25 1.25 0 0 1 15 5.25V7.5"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M7.5 7.5v11.25A1.25 1.25 0 0 0 8.75 20h6.5a1.25 1.25 0 0 0 1.25-1.25V7.5"
                                            />

                                        </svg>

                                        Supprimer

                                    </button>

                                @endif

                            @endif

                        </div>

                    </div>

                </article>

            @empty

                <div
                    class="establishment-card"
                    style="grid-column: 1 / -1;"
                >

                    <div
                        class="card-content"
                        style="text-align:center;"
                    >

                        <h2 class="establishment-name">
                            Aucun établissement
                        </h2>

                        <p class="page-description">
                            Aucun établissement n'est actuellement disponible pour votre compte.
                        </p>

                    </div>

                </div>

            @endforelse

        </div>

    </main>


    {{-- =========================================================
         MODAL SELECTION
    ========================================================== --}}

    <div
        id="selectModal"
        class="modal-overlay"
        role="dialog"
        aria-modal="true"
        aria-labelledby="selectModalTitle"
    >

        <div class="modal select-modal">


            {{-- LOGO OPTIMASCHOOL --}}

            <div class="select-modal-logo-wrapper">

                <img
                    src="{{ asset('images/optimaschool-logo.png') }}"
                    alt="OPTIMASCHOOL"
                    class="select-modal-logo"
                >

            </div>


            <div class="modal-header">

                <div class="modal-icon select-modal-icon">

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
                            d="M9 12.75 11.25 15 15 9.75"
                        />

                        <circle
                            cx="12"
                            cy="12"
                            r="9"
                        />

                    </svg>

                </div>


                <div>

                    <h2
                        id="selectModalTitle"
                        class="modal-title"
                    >
                        Sélectionner cet établissement
                    </h2>

                    <p class="modal-text">
                        Vous allez accéder au tableau de bord de cet établissement.
                    </p>

                </div>

            </div>


            <div class="select-modal-establishment">

                <div
                    id="selectEstablishmentName"
                    class="select-modal-establishment-name"
                ></div>

                <div
                    id="selectEstablishmentType"
                    class="select-modal-establishment-type"
                ></div>

            </div>


            <div class="modal-actions">

                <button
                    type="button"
                    class="modal-button cancel-button"
                    onclick="closeSelectModal()"
                >
                    Annuler
                </button>

                <a
                    id="confirmSelectButton"
                    href="#"
                    class="modal-button confirm-select-button"
                >
                    Continuer
                </a>

            </div>

        </div>

    </div>


    {{-- =========================================================
         MODAL EDIT
    ========================================================== --}}

    <div
        id="editModal"
        class="modal-overlay"
        role="dialog"
        aria-modal="true"
        aria-labelledby="editModalTitle"
    >

        <div class="modal">

            <div class="modal-header">

                <div class="modal-icon edit-modal-icon">

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
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652l-9.193 9.193a4.5 4.5 0 0 1-1.897 1.13l-3.27.981.981-3.27a4.5 4.5 0 0 1 1.13-1.897l9.193-9.193Z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="m18 7.5 3 3"
                        />

                    </svg>

                </div>


                <div>

                    <h2
                        id="editModalTitle"
                        class="modal-title"
                    >
                        Modifier l'établissement
                    </h2>

                    <p class="modal-text">
                        Modifiez les informations de cet établissement.
                    </p>

                </div>

            </div>


            <form
                id="editForm"
                method="POST"
                action=""
                onsubmit="return validateEditForm()"
            >

                @csrf

                @method('PUT')


                <div class="form-container">

                    <div class="form-grid">


                        <div class="form-group full-width">

                            <label
                                for="edit_school_type_id"
                                class="form-label"
                            >
                                Type d'établissement
                            </label>

                            <select
                                id="edit_school_type_id"
                                name="school_type_id"
                                class="form-select"
                            >

                                <option value="">
                                    Sélectionner un type
                                </option>

                                @foreach($schoolTypes as $schoolType)

                                    <option
                                        value="{{ $schoolType->id }}"
                                    >
                                        {{ $schoolType->name }}
                                    </option>

                                @endforeach

                            </select>

                            <div
                                id="edit_school_type_id_error"
                                class="form-error"
                            ></div>

                        </div>


                        <div class="form-group full-width">

                            <label
                                for="edit_nom"
                                class="form-label"
                            >
                                Nom de l'établissement
                            </label>

                            <input
                                type="text"
                                id="edit_nom"
                                name="nom"
                                class="form-input"
                                maxlength="255"
                            >

                            <div
                                id="edit_nom_error"
                                class="form-error"
                            ></div>

                        </div>


                        <div class="form-group full-width">

                            <label
                                for="edit_adresse"
                                class="form-label"
                            >
                                Adresse
                            </label>

                            <input
                                type="text"
                                id="edit_adresse"
                                name="adresse"
                                class="form-input"
                                maxlength="255"
                            >

                            <div
                                id="edit_adresse_error"
                                class="form-error"
                            ></div>

                        </div>


                        <div class="form-group">

                            <label
                                for="edit_telephone"
                                class="form-label"
                            >
                                Téléphone
                            </label>

                            <input
                                type="text"
                                id="edit_telephone"
                                name="telephone"
                                class="form-input"
                                maxlength="30"
                            >

                            <div
                                id="edit_telephone_error"
                                class="form-error"
                            ></div>

                        </div>


                        <div class="form-group">

                            <label
                                for="edit_email"
                                class="form-label"
                            >
                                Email
                            </label>

                            <input
                                type="email"
                                id="edit_email"
                                name="email"
                                class="form-input"
                                placeholder="test@gmail.com"
                                maxlength="255"
                            >

                            <div
                                id="edit_email_error"
                                class="form-error"
                            ></div>

                        </div>

                    </div>

                </div>


                <div class="modal-actions">

                    <button
                        type="button"
                        class="modal-button cancel-button"
                        onclick="closeEditModal()"
                    >
                        Annuler
                    </button>

                    <button
                        type="submit"
                        class="modal-button confirm-edit-button"
                    >
                        Enregistrer
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         MODAL DELETE
    ========================================================== --}}

    <div
        id="deleteModal"
        class="modal-overlay"
        role="dialog"
        aria-modal="true"
        aria-labelledby="deleteModalTitle"
    >

        <div class="modal">

            <div class="modal-header">

                <div class="modal-icon delete-modal-icon">

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
                            d="M12 9v3.75"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 16.5h.01"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"
                        />

                    </svg>

                </div>


                <div>

                    <h2
                        id="deleteModalTitle"
                        class="modal-title"
                    >
                        Supprimer l'établissement
                    </h2>

                    <p class="modal-text">
                        Cette action est définitive.
                    </p>

                </div>

            </div>


            <div class="delete-warning">

                Êtes-vous sûr de vouloir supprimer cet établissement ?
                Toutes les informations associées pourront être affectées.

            </div>


            <form
                id="deleteForm"
                method="POST"
                action=""
            >

                @csrf

                @method('DELETE')


                <div class="modal-actions">

                    <button
                        type="button"
                        class="modal-button cancel-button"
                        onclick="closeDeleteModal()"
                    >
                        Annuler
                    </button>

                    <button
                        type="submit"
                        class="modal-button confirm-delete-button"
                    >
                        Supprimer
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         JAVASCRIPT
    ========================================================== --}}

    <script>


        /* =========================================================
           SELECT MODAL
        ========================================================= */

        const selectModal =
            document.getElementById(
                'selectModal'
            );

        const selectEstablishmentName =
            document.getElementById(
                'selectEstablishmentName'
            );

        const selectEstablishmentType =
            document.getElementById(
                'selectEstablishmentType'
            );

        const confirmSelectButton =
            document.getElementById(
                'confirmSelectButton'
            );


        function openSelectModal(
            url,
            name,
            schoolType
        ) {

            selectEstablishmentName.textContent =
                name ||
                'Établissement';

            selectEstablishmentType.textContent =
                schoolType ||
                'Établissement scolaire';

            confirmSelectButton.href =
                url;

            selectModal.classList.add(
                'show'
            );

            document.body.style.overflow =
                'hidden';

            setTimeout(
                function () {

                    confirmSelectButton.focus();

                },
                100
            );

        }


        function closeSelectModal() {

            selectModal.classList.remove(
                'show'
            );

            document.body.style.overflow =
                '';

        }


        selectModal.addEventListener(
            'click',
            function (event) {

                if (
                    event.target === selectModal
                ) {

                    closeSelectModal();

                }

            }
        );


        /* =========================================================
           EDIT MODAL
        ========================================================== */

        const editModal =
            document.getElementById(
                'editModal'
            );

        const editForm =
            document.getElementById(
                'editForm'
            );

        const editSchoolType =
            document.getElementById(
                'edit_school_type_id'
            );

        const editNom =
            document.getElementById(
                'edit_nom'
            );

        const editAdresse =
            document.getElementById(
                'edit_adresse'
            );

        const editTelephone =
            document.getElementById(
                'edit_telephone'
            );

        const editEmail =
            document.getElementById(
                'edit_email'
            );


        function openEditModal(
            url,
            schoolTypeId,
            nom,
            adresse,
            telephone,
            email
        ) {

            editForm.action =
                url;

            editSchoolType.value =
                schoolTypeId || '';

            editNom.value =
                nom || '';

            editAdresse.value =
                adresse || '';

            editTelephone.value =
                telephone || '';

            editEmail.value =
                email || '';

            clearEditErrors();

            editModal.classList.add(
                'show'
            );

            document.body.style.overflow =
                'hidden';

            setTimeout(
                function () {

                    editNom.focus();

                },
                100
            );

        }


        function closeEditModal() {

            editModal.classList.remove(
                'show'
            );

            document.body.style.overflow =
                '';

            clearEditErrors();

        }


        function clearEditErrors() {

            const fields = [

                'edit_school_type_id',
                'edit_nom',
                'edit_adresse',
                'edit_telephone',
                'edit_email'

            ];

            fields.forEach(
                function (field) {

                    const element =
                        document.getElementById(
                            field
                        );

                    const error =
                        document.getElementById(
                            field + '_error'
                        );

                    if(element) {

                        element.classList.remove(
                            'input-error'
                        );

                    }

                    if(error) {

                        error.textContent =
                            '';

                    }

                }
            );

        }


        function setEditError(
            field,
            message
        ) {

            const element =
                document.getElementById(
                    field
                );

            const error =
                document.getElementById(
                    field + '_error'
                );

            if(element) {

                element.classList.add(
                    'input-error'
                );

            }

            if(error) {

                error.textContent =
                    message;

            }

        }


        function validateEditForm() {

            clearEditErrors();

            let valid = true;


            /* TYPE */

            if(
                !editSchoolType.value
            ) {

                setEditError(
                    'edit_school_type_id',
                    'Veuillez sélectionner le type d’établissement.'
                );

                valid = false;

            }


            /* NOM */

            const nom =
                editNom.value.trim();

            if(
                !nom
            ) {

                setEditError(
                    'edit_nom',
                    'Le nom de l’établissement est obligatoire.'
                );

                valid = false;

            }
            else if(
                nom.length < 2
            ) {

                setEditError(
                    'edit_nom',
                    'Le nom doit contenir au moins 2 caractères.'
                );

                valid = false;

            }


            /* ADRESSE */

            const adresse =
                editAdresse.value.trim();

            if(
                !adresse
            ) {

                setEditError(
                    'edit_adresse',
                    'L’adresse est obligatoire.'
                );

                valid = false;

            }
            else if(
                adresse.length < 3
            ) {

                setEditError(
                    'edit_adresse',
                    'L’adresse doit contenir au moins 3 caractères.'
                );

                valid = false;

            }


            /* TELEPHONE */

            const telephone =
                editTelephone.value.trim();

            if(
                telephone
            ) {

                const telephonePattern =
                    /^[0-9+\s().-]{6,30}$/;

                if(
                    !telephonePattern.test(
                        telephone
                    )
                ) {

                    setEditError(
                        'edit_telephone',
                        'Veuillez saisir un numéro de téléphone valide.'
                    );

                    valid = false;

                }

            }


            /* EMAIL */

            const email =
                editEmail.value.trim();


            /*
             * Gmail uniquement
             */

            const emailPattern =
                /^[^\s@]+@gmail\.com$/i;


            if(
                email &&
                !emailPattern.test(
                    email
                )
            ) {

                setEditError(
                    'edit_email',
                    'Veuillez saisir une adresse Gmail valide.'
                );

                valid = false;

            }


            return valid;

        }


        /* =========================================================
           LIVE VALIDATION
        ========================================================== */

        editEmail.addEventListener(
            'input',
            function () {

                const email =
                    editEmail.value.trim();

                const emailPattern =
                    /^[^\s@]+@gmail\.com$/i;

                editEmail.classList.remove(
                    'input-error'
                );

                document.getElementById(
                    'edit_email_error'
                ).textContent = '';


                if(
                    email &&
                    !emailPattern.test(
                        email
                    )
                ) {

                    setEditError(
                        'edit_email',
                        'Veuillez saisir une adresse Gmail valide.'
                    );

                }

            }
        );


        editNom.addEventListener(
            'input',
            function () {

                editNom.classList.remove(
                    'input-error'
                );

                document.getElementById(
                    'edit_nom_error'
                ).textContent = '';

            }
        );


        editAdresse.addEventListener(
            'input',
            function () {

                editAdresse.classList.remove(
                    'input-error'
                );

                document.getElementById(
                    'edit_adresse_error'
                ).textContent = '';

            }
        );


        editTelephone.addEventListener(
            'input',
            function () {

                editTelephone.classList.remove(
                    'input-error'
                );

                document.getElementById(
                    'edit_telephone_error'
                ).textContent = '';

            }
        );


        editSchoolType.addEventListener(
            'change',
            function () {

                editSchoolType.classList.remove(
                    'input-error'
                );

                document.getElementById(
                    'edit_school_type_id_error'
                ).textContent = '';

            }
        );


        editModal.addEventListener(
            'click',
            function (event) {

                if (
                    event.target === editModal
                ) {

                    closeEditModal();

                }

            }
        );


        /* =========================================================
           DELETE MODAL
        ========================================================== */

        const deleteModal =
            document.getElementById(
                'deleteModal'
            );

        const deleteForm =
            document.getElementById(
                'deleteForm'
            );


        function openDeleteModal(
            url
        ) {

            deleteForm.action =
                url;

            deleteModal.classList.add(
                'show'
            );

            document.body.style.overflow =
                'hidden';

        }


        function closeDeleteModal() {

            deleteModal.classList.remove(
                'show'
            );

            document.body.style.overflow =
                '';

        }


        deleteModal.addEventListener(
            'click',
            function (event) {

                if (
                    event.target === deleteModal
                ) {

                    closeDeleteModal();

                }

            }
        );


        /* =========================================================
           ESCAPE
        ========================================================== */

        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key !== 'Escape'
                ) {

                    return;

                }


                if (
                    selectModal.classList.contains(
                        'show'
                    )
                ) {

                    closeSelectModal();

                    return;

                }


                if (
                    editModal.classList.contains(
                        'show'
                    )
                ) {

                    closeEditModal();

                    return;

                }


                if (
                    deleteModal.classList.contains(
                        'show'
                    )
                ) {

                    closeDeleteModal();

                }

            }
        );

    </script>

</body>

</html>
