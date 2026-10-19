<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Vérification du paiement - OPTIMASCHOOL</title>

    <style>
        :root {
            --blue: #1e3a8a;
            --blue-dark: #142654;
            --blue-light: #eff6ff;
            --green: #16a34a;
            --green-light: #f0fdf4;
            --red: #dc2626;
            --red-light: #fef2f2;
            --ink: #172033;
            --muted: #64748b;
            --border: #e2e8f0;
            --surface: #ffffff;
            --background: #f4f7fc;
            --shadow: 0 12px 35px rgba(15, 23, 42, .07);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            color: var(--ink);
            background: var(--background);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system,
                         BlinkMacSystemFont, "Segoe UI", sans-serif;
            line-height: 1.55;
        }

        button, input, textarea { font: inherit; }

        a { color: inherit; }

        .topbar {
            background: var(--blue-dark);
            color: #fff;
            padding: 18px 24px;
        }

        .topbar-inner {
            max-width: 1450px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 850;
            letter-spacing: .3px;
        }

        .brand-mark {
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #fff;
            color: var(--blue);
            font-size: 20px;
            font-weight: 900;
        }

        .brand small {
            display: block;
            color: #dbeafe;
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0;
        }

        .top-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #fff;
            border: 1px solid rgba(255,255,255,.35);
            border-radius: 10px;
            padding: 10px 14px;
            text-decoration: none;
            font-weight: 700;
        }

        .container {
            max-width: 1450px;
            padding: 30px 24px 50px;
            margin: auto;
        }

        .eyebrow {
            color: var(--blue);
            font-size: 12px;
            font-weight: 850;
            letter-spacing: 1.1px;
            text-transform: uppercase;
        }

        .page-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }

        h1 {
            margin: 5px 0 8px;
            font-size: clamp(25px, 3vw, 34px);
            line-height: 1.2;
            letter-spacing: -.7px;
        }

        h2 {
            margin: 0;
            font-size: 19px;
            letter-spacing: -.2px;
        }

        .muted { color: var(--muted); }

        .page-description {
            margin: 0;
            color: var(--muted);
        }

        .grid {
            display: grid;
            grid-template-columns: minmax(0, 1.5fr) minmax(320px, .85fr);
            gap: 22px;
            align-items: start;
        }

        .stack {
            display: grid;
            gap: 22px;
            min-width: 0;
        }

        .card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 17px;
            background: var(--surface);
            box-shadow: var(--shadow);
        }

        .card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 20px 22px;
            border-bottom: 1px solid var(--border);
        }

        .card-body { padding: 22px; }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 11px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 850;
            white-space: nowrap;
        }

        .status-pending {
            background: #fff7ed;
            color: #9a3412;
        }

        .status-valid {
            background: var(--green-light);
            color: #166534;
        }

        .status-rejected {
            background: var(--red-light);
            color: #991b1b;
        }

        .amount-box {
            background: var(--blue-light);
            border: 1px solid #dbeafe;
            border-radius: 14px;
            padding: 19px;
            margin-bottom: 20px;
        }

        .amount-label {
            color: var(--blue);
            font-size: 13px;
            font-weight: 750;
        }

        .amount {
            margin-top: 4px;
            color: var(--blue);
            font-size: clamp(27px, 4vw, 36px);
            font-weight: 900;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px 16px;
        }

        .detail {
            min-width: 0;
            padding-bottom: 14px;
            border-bottom: 1px solid #edf1f7;
        }

        .detail-label {
            margin-bottom: 5px;
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 800;
            overflow-wrap: anywhere;
        }

        .preview-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .preview-area {
            min-height: 430px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
            background: #eef2f7;
        }

        .proof-image {
            display: block;
            max-width: 100%;
            max-height: 75vh;
            object-fit: contain;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 5px 20px rgba(15, 23, 42, .12);
            cursor: zoom-in;
            transition: transform .2s ease;
            transform-origin: center center;
        }

        .proof-image.zoomed {
            max-width: none;
            max-height: none;
            width: 140%;
            cursor: zoom-out;
        }

        .proof-pdf {
            width: 100%;
            height: 72vh;
            min-height: 500px;
            border: 0;
            background: #fff;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            border: 1px solid transparent;
            border-radius: 10px;
            padding: 10px 15px;
            text-decoration: none;
            text-align: center;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            transition: filter .15s ease, transform .15s ease;
        }

        .button:hover { filter: brightness(.95); }
        .button:active { transform: translateY(1px); }

        .button-blue {
            background: var(--blue);
            color: #fff;
        }

        .button-green {
            background: var(--green);
            color: #fff;
        }

        .button-red {
            background: var(--red);
            color: #fff;
        }

        .button-light {
            color: var(--blue);
            background: #fff;
            border-color: #cbd5e1;
        }

        .actions {
            display: grid;
            gap: 12px;
        }

        .actions form { margin: 0; }

        .field-label {
            display: block;
            margin: 17px 0 7px;
            font-size: 13px;
            font-weight: 800;
        }

        textarea {
            display: block;
            width: 100%;
            min-height: 115px;
            resize: vertical;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
            background: #fff;
        }

        textarea:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(30, 58, 138, .1);
        }

        .hint {
            margin-top: 7px;
            color: var(--muted);
            font-size: 12px;
        }

        .notice {
            padding: 13px 15px;
            border-radius: 11px;
            font-size: 13px;
            overflow-wrap: anywhere;
        }

        .notice-green {
            color: #166534;
            background: var(--green-light);
            border: 1px solid #bbf7d0;
        }

        .notice-red {
            color: #991b1b;
            background: var(--red-light);
            border: 1px solid #fecaca;
        }

        .notice-blue {
            color: var(--blue);
            background: var(--blue-light);
            border: 1px solid #dbeafe;
        }

        .notice + .notice { margin-top: 12px; }

        .field-error {
            margin-top: 6px;
            color: #b91c1c;
            font-size: 12px;
            font-weight: 700;
        }

        .security-note {
            margin-top: 16px;
            color: var(--muted);
            font-size: 12px;
        }

        @media (max-width: 950px) {
            .grid { grid-template-columns: 1fr; }
            .preview-area { min-height: 320px; }
        }

        @media (max-width: 600px) {
            .topbar { padding: 14px 16px; }
            .topbar-inner { align-items: flex-start; }
            .brand { font-size: 13px; }
            .brand-mark { width: 36px; height: 36px; }
            .top-link { padding: 9px 10px; font-size: 12px; }
            .container { padding: 22px 14px 35px; }
            .page-heading { flex-direction: column; }
            .card-head, .card-body { padding: 16px; }
            .details { grid-template-columns: 1fr; gap: 14px; }
            .preview-area { padding: 10px; min-height: 250px; }
            .proof-pdf { height: 65vh; min-height: 400px; }
            .button { width: 100%; }
            .preview-toolbar { align-items: stretch; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                transition: none !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body>
    @php
        $statuts = [
            'en_attente' => ['En attente de vérification', 'status-pending'],
            'valide' => ['Paiement validé', 'status-valid'],
            'rejete' => ['Paiement refusé', 'status-rejected'],
        ];

        [$statutTexte, $statutClasse] = $statuts[$paiement->statut]
            ?? ['Statut inconnu', 'status-pending'];
    @endphp

    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">
                <div class="brand-mark" aria-hidden="true">O</div>
                <div>
                    OPTIMASCHOOL
                    <small>Administration des abonnements</small>
                </div>
            </div>

            <a class="top-link"
               href="{{ route('super-admin.abonnements.show', $establishment) }}">
                <span aria-hidden="true">←</span>
                Retour
            </a>
        </div>
    </header>

    <main class="container">
        <div class="page-heading">
            <div>
                <div class="eyebrow">Contrôle de paiement</div>
                <h1>Vérification du justificatif</h1>
                <p class="page-description">
                    Consultez les informations et le document avant de prendre une décision.
                </p>
            </div>

            <span class="status {{ $statutClasse }}">
                {{ $statutTexte }}
            </span>
        </div>

        @if (session('success'))
            <div class="notice notice-green" style="margin-bottom:18px">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="notice notice-red" style="margin-bottom:18px">
                <strong>Vérifiez les informations saisies :</strong>
                <ul style="margin-bottom:0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid">
            <div class="stack">
                <section class="card">
                    <div class="card-head">
                        <h2>Justificatif de paiement</h2>
                        <a class="button button-blue"
                           href="{{ route('super-admin.abonnements.paiements.justificatif.telecharger', $paiement) }}">
                            <svg width="17" height="17" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round"
                                 aria-hidden="true">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <path d="m7 10 5 5 5-5"/>
                                <path d="M12 15V3"/>
                            </svg>
                            Télécharger
                        </a>
                    </div>

                    <div class="preview-toolbar" style="padding:14px 20px 0">
                        <span class="muted" style="font-size:12px">
                            @if ($mime === 'application/pdf')
                                Document PDF
                            @else
                                Image du justificatif
                            @endif
                        </span>

                        @if ($mime !== 'application/pdf')
                            <button type="button"
                                    id="zoom-toggle"
                                    class="button button-light">
                                Agrandir l’image
                            </button>
                        @endif
                    </div>

                    <div class="preview-area">
                        @if ($mime === 'application/pdf')
                            <iframe
                                class="proof-pdf"
                                src="{{ route('super-admin.abonnements.paiements.justificatif', $paiement) }}"
                                title="Justificatif PDF du paiement"
                            ></iframe>
                        @else
                            <img
                                id="proof-image"
                                class="proof-image"
                                src="{{ route('super-admin.abonnements.paiements.justificatif', $paiement) }}"
                                alt="Justificatif de paiement soumis par l’établissement"
                                loading="eager"
                            >
                        @endif
                    </div>
                </section>

                @if ($paiement->note_verification)
                    <section class="card">
                        <div class="card-head">
                            <h2>Note de vérification</h2>
                        </div>
                        <div class="card-body">
                            <div class="{{ $paiement->statut === 'rejete' ? 'notice notice-red' : 'notice notice-blue' }}">
                                {{ $paiement->note_verification }}
                            </div>
                        </div>
                    </section>
                @endif
            </div>

            <div class="stack">
                <section class="card">
                    <div class="card-head">
                        <h2>Informations du paiement</h2>
                    </div>

                    <div class="card-body">
                        <div class="amount-box">
                            <div class="amount-label">Montant déclaré</div>
                            <div class="amount">
                                {{ number_format((float) $paiement->montant, 2, ',', ' ') }}
                                {{ $paiement->abonnement?->devise ?? 'MGA' }}
                            </div>
                        </div>

                        <div class="details">
                            <div class="detail">
                                <div class="detail-label">N° du paiement</div>
                                <div class="detail-value">#{{ $paiement->id }}</div>
                            </div>

                            <div class="detail">
                                <div class="detail-label">Établissement</div>
                                <div class="detail-value">{{ $establishment->nom }}</div>
                            </div>

                            <div class="detail">
                                <div class="detail-label">Mode de paiement</div>
                                <div class="detail-value">{{ $paiement->mode_paiement ?: 'Non renseigné' }}</div>
                            </div>

                            <div class="detail">
                                <div class="detail-label">Référence de transaction</div>
                                <div class="detail-value">{{ $paiement->reference_transaction ?: 'Non renseignée' }}</div>
                            </div>

                            <div class="detail">
                                <div class="detail-label">Date de soumission</div>
                                <div class="detail-value">
                                    {{ $paiement->date_soumission?->format('d/m/Y à H:i') ?? 'Non renseignée' }}
                                </div>
                            </div>

                            <div class="detail">
                                <div class="detail-label">Date du paiement</div>
                                <div class="detail-value">
                                    {{ $paiement->date_paiement?->format('d/m/Y à H:i') ?? 'Non renseignée' }}
                                </div>
                            </div>

                            <div class="detail">
                                <div class="detail-label">Statut actuel</div>
                                <div class="detail-value">{{ $statutTexte }}</div>
                            </div>

                            <div class="detail">
                                <div class="detail-label">Administrateur</div>
                                <div class="detail-value">
                                    {{ $establishment->user?->name ?? 'Non renseigné' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="card">
                    <div class="card-head">
                        <h2>Décision de vérification</h2>
                    </div>

                    <div class="card-body">
                        @if ($paiement->statut === 'en_attente')
                            <div class="notice notice-blue">
                                Vérifiez la transaction auprès du service de paiement avant de confirmer.
                            </div>

                            <div class="actions" style="margin-top:16px">
                                <form method="POST"
                                      action="{{ route('super-admin.abonnements.paiements.valider', $paiement) }}"
                                      onsubmit="return confirm('Confirmez-vous avoir vérifié la transaction réelle et vouloir valider ce paiement ?');">
                                    @csrf
                                    <button class="button button-green" type="submit">
                                        <svg width="18" height="18" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2.5"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             aria-hidden="true">
                                            <path d="m5 12 4 4L19 6"/>
                                        </svg>
                                        Valider le paiement
                                    </button>
                                </form>

                                <form method="POST"
                                      action="{{ route('super-admin.abonnements.paiements.rejeter', $paiement) }}"
                                      onsubmit="return confirm('Confirmez-vous le refus de ce paiement ?');">
                                    @csrf

                                    <label class="field-label" for="note_verification">
                                        Motif du refus <span aria-hidden="true">*</span>
                                    </label>

                                    <textarea
                                        id="note_verification"
                                        name="note_verification"
                                        maxlength="2000"
                                        required
                                        placeholder="Expliquez clairement pourquoi le paiement est refusé..."
                                    >{{ old('note_verification') }}</textarea>

                                    @error('note_verification')
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror

                                    <div class="hint">
                                        Maximum 2 000 caractères. Le motif sera conservé dans l’historique.
                                    </div>

                                    <button class="button button-red"
                                            type="submit"
                                            style="margin-top:12px">
                                        Refuser le paiement
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="notice {{ $paiement->statut === 'valide' ? 'notice-green' : 'notice-red' }}">
                                Ce paiement a déjà été traité.
                                @if ($paiement->date_verification)
                                    <br>
                                    Date de vérification :
                                    {{ $paiement->date_verification->format('d/m/Y à H:i') }}
                                @endif
                            </div>
                            <p class="security-note">
                                Une nouvelle validation ou un nouveau refus n’est pas autorisé pour ce paiement.
                            </p>
                        @endif

                        <p class="security-note">
                            Le justificatif est servi depuis le stockage privé.
                            Son adresse n’est pas un lien public permanent.
                        </p>
                    </div>
                </section>
            </div>
        </div>
    </main>

    @if ($mime !== 'application/pdf')
        <script>
            (() => {
                const image = document.getElementById('proof-image');
                const button = document.getElementById('zoom-toggle');

                if (!image || !button) return;

                button.addEventListener('click', () => {
                    const zoomed = image.classList.toggle('zoomed');
                    button.textContent = zoomed
                        ? 'Réduire l’image'
                        : 'Agrandir l’image';
                });

                image.addEventListener('click', () => {
                    const zoomed = image.classList.toggle('zoomed');
                    button.textContent = zoomed
                        ? 'Réduire l’image'
                        : 'Agrandir l’image';
                });
            })();
        </script>
    @endif
</body>
</html>
