<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <title>
        Reçu {{ $paiement->numero_recu }}
    </title>

    @php
        /*
        |--------------------------------------------------------------------------
        | ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        $etablissement = $paiement->establishment;

        /*
        |--------------------------------------------------------------------------
        | LOGO
        |--------------------------------------------------------------------------
        |
        | Le logo est récupéré depuis storage/app/public.
        | L'utilisation d'une Data URI permet à DomPDF
        | d'intégrer correctement l'image dans le PDF.
        |
        */

        $logoDataUri = null;

        if ($etablissement?->logo) {

            $logoPath = storage_path(
                'app/public/' . $etablissement->logo
            );

            if (is_file($logoPath) && is_readable($logoPath)) {

                $mimeType = mime_content_type($logoPath)
                    ?: 'image/png';

                $logoContent = file_get_contents($logoPath);

                if ($logoContent !== false) {

                    $logoDataUri =
                        'data:' .
                        $mimeType .
                        ';base64,' .
                        base64_encode($logoContent);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | NUMÉRO DE REÇU
        |--------------------------------------------------------------------------
        */

        $numeroRecu = $paiement->numero_recu
            ?: 'PAI-' . str_pad(
                (string) $paiement->id,
                6,
                '0',
                STR_PAD_LEFT
            );

        /*
        |--------------------------------------------------------------------------
        | DATE
        |--------------------------------------------------------------------------
        */

        $datePaiement = $paiement->date_paiement
            ? $paiement->date_paiement->format('d/m/Y')
            : '—';

        /*
        |--------------------------------------------------------------------------
        | MOIS
        |--------------------------------------------------------------------------
        */

        $moisConcerne = null;

        if ($paiement->mois) {
            $moisConcerne =
                \Carbon\Carbon::create()
                    ->month($paiement->mois)
                    ->translatedFormat('F')
                . ' '
                . $paiement->annee;
        }
    @endphp


    <style>

        /*
        |--------------------------------------------------------------------------
        | PAGE
        |--------------------------------------------------------------------------
        */

        @page {
            margin: 28px 30px 30px 30px;
        }


        /*
        |--------------------------------------------------------------------------
        | GLOBAL
        |--------------------------------------------------------------------------
        */

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;

            font-family: DejaVu Sans, sans-serif;

            font-size: 11px;

            color: #1f2937;

            background: #ffffff;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .header {
            width: 100%;

            border-bottom: 3px solid #1e3a8a;

            padding-bottom: 14px;

            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;

            border-collapse: collapse;
        }


        /*
        |--------------------------------------------------------------------------
        | LOGO
        |--------------------------------------------------------------------------
        */

        .logo-cell {
            width: 92px;

            vertical-align: middle;

            padding-right: 12px;
        }

        .logo {
            width: 76px;
            height: 76px;

            object-fit: contain;
        }


        /*
        |--------------------------------------------------------------------------
        | INFORMATIONS ÉTABLISSEMENT
        |--------------------------------------------------------------------------
        */

        .establishment-cell {
            vertical-align: middle;
        }

        .establishment-name {
            font-size: 19px;

            font-weight: bold;

            color: #1e3a8a;

            margin-bottom: 5px;
        }

        .establishment-detail {
            font-size: 9.5px;

            color: #4b5563;

            line-height: 1.55;
        }

        .detail-label {
            font-weight: bold;

            color: #374151;
        }


        /*
        |--------------------------------------------------------------------------
        | REÇU
        |--------------------------------------------------------------------------
        */

        .receipt-heading {
            width: 100%;

            margin: 0 0 18px 0;

            text-align: center;
        }

        .receipt-heading h1 {
            margin: 0;

            font-size: 21px;

            font-weight: bold;

            color: #1e3a8a;

            letter-spacing: 0.5px;
        }

        .receipt-heading-line {
            width: 70px;

            height: 2px;

            background: #16a34a;

            margin: 7px auto 6px auto;
        }

        .receipt-number {
            font-size: 10px;

            color: #6b7280;
        }


        /*
        |--------------------------------------------------------------------------
        | META
        |--------------------------------------------------------------------------
        */

        .meta-table {
            width: 100%;

            border-collapse: collapse;

            margin-bottom: 16px;
        }

        .meta-cell {
            width: 50%;

            padding: 7px 9px;

            border: 1px solid #d1d5db;

            vertical-align: top;
        }

        .meta-label {
            display: block;

            font-size: 8.5px;

            color: #6b7280;

            text-transform: uppercase;

            margin-bottom: 3px;
        }

        .meta-value {
            font-size: 10px;

            font-weight: bold;

            color: #1f2937;
        }


        /*
        |--------------------------------------------------------------------------
        | SECTION
        |--------------------------------------------------------------------------
        */

        .section-title {
            margin: 17px 0 8px 0;

            padding: 7px 9px;

            background: #f1f5f9;

            border-left: 4px solid #1e3a8a;

            color: #1e3a8a;

            font-size: 10px;

            font-weight: bold;

            text-transform: uppercase;
        }


        /*
        |--------------------------------------------------------------------------
        | INFORMATIONS PAIEMENT
        |--------------------------------------------------------------------------
        */

        .info-table {
            width: 100%;

            border-collapse: collapse;

            margin-bottom: 16px;
        }

        .info-table td {
            border: 1px solid #d1d5db;

            padding: 8px 9px;

            vertical-align: top;
        }

        .info-label {
            width: 31%;

            background: #f8fafc;

            color: #475569;

            font-weight: bold;

            font-size: 9.5px;
        }

        .info-value {
            color: #1f2937;

            font-size: 10px;
        }


        /*
        |--------------------------------------------------------------------------
        | MONTANT
        |--------------------------------------------------------------------------
        */

        .amount-box {
            width: 100%;

            margin: 20px 0;

            border: 2px solid #1e3a8a;

            background: #f8fafc;

            text-align: center;

            padding: 13px 10px;
        }

        .amount-label {
            font-size: 9px;

            color: #64748b;

            text-transform: uppercase;

            letter-spacing: 0.4px;
        }

        .amount {
            margin-top: 5px;

            font-size: 22px;

            font-weight: bold;

            color: #1e3a8a;
        }


        /*
        |--------------------------------------------------------------------------
        | MONTANT EN LETTRES
        |--------------------------------------------------------------------------
        */

        .payment-note {
            margin-top: -7px;

            margin-bottom: 18px;

            text-align: center;

            font-size: 9px;

            color: #64748b;
        }


        /*
        |--------------------------------------------------------------------------
        | SIGNATURES
        |--------------------------------------------------------------------------
        */

        .signature-table {
            width: 100%;

            border-collapse: collapse;

            margin-top: 32px;
        }

        .signature-table td {
            width: 50%;

            text-align: center;

            vertical-align: top;

            padding: 8px 18px;
        }

        .signature-title {
            font-size: 10px;

            font-weight: bold;

            color: #374151;
        }

        .signature-line {
            margin-top: 45px;

            border-top: 1px solid #9ca3af;

            padding-top: 5px;

            font-size: 8.5px;

            color: #6b7280;
        }


        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        .footer {
            margin-top: 27px;

            padding-top: 9px;

            border-top: 1px solid #d1d5db;

            text-align: center;

            font-size: 8px;

            color: #6b7280;
        }

    </style>
</head>


<body>


    <!-- =========================================================
         HEADER ÉTABLISSEMENT
         ========================================================= -->

    <div class="header">

        <table class="header-table">

            <tr>

                @if($logoDataUri)

                    <td class="logo-cell">

                        <img
                            src="{{ $logoDataUri }}"
                            alt="Logo de l'établissement"
                            class="logo"
                        >

                    </td>

                @endif


                <td class="establishment-cell">

                    <div class="establishment-name">
                        {{ $etablissement?->nom ?? 'Établissement' }}
                    </div>


                    @if($etablissement?->adresse)

                        <div class="establishment-detail">

                            <span class="detail-label">
                                Adresse :
                            </span>

                            {{ $etablissement->adresse }}

                        </div>

                    @endif


                    @if($etablissement?->telephone)

                        <div class="establishment-detail">

                            <span class="detail-label">
                                Téléphone :
                            </span>

                            {{ $etablissement->telephone }}

                        </div>

                    @endif


                    @if($etablissement?->email)

                        <div class="establishment-detail">

                            <span class="detail-label">
                                Email :
                            </span>

                            {{ $etablissement->email }}

                        </div>

                    @endif

                </td>

            </tr>

        </table>

    </div>



    <!-- =========================================================
         TITRE
         ========================================================= -->

    <div class="receipt-heading">

        <h1>
            REÇU DE PAIEMENT
        </h1>

        <div class="receipt-heading-line"></div>

        <div class="receipt-number">

            N° {{ $numeroRecu }}

        </div>

    </div>



    <!-- =========================================================
         INFORMATIONS GÉNÉRALES
         ========================================================= -->

    <table class="meta-table">

        <tr>

            <td class="meta-cell">

                <span class="meta-label">
                    Établissement
                </span>

                <span class="meta-value">
                    {{ $etablissement?->nom ?? '—' }}
                </span>

            </td>


            <td class="meta-cell">

                <span class="meta-label">
                    Année scolaire
                </span>

                <span class="meta-value">
                    {{ $paiement->schoolYear?->name ?? '—' }}
                </span>

            </td>

        </tr>


        <tr>

            <td class="meta-cell">

                <span class="meta-label">
                    Date de paiement
                </span>

                <span class="meta-value">
                    {{ $datePaiement }}
                </span>

            </td>


            <td class="meta-cell">

                <span class="meta-label">
                    Mode de paiement
                </span>

                <span class="meta-value">
                    {{ $paiement->mode_paiement ?: '—' }}
                </span>

            </td>

        </tr>

    </table>



    <!-- =========================================================
         BÉNÉFICIAIRE
         ========================================================= -->

    <div class="section-title">
        Bénéficiaire
    </div>


    <table class="info-table">

        @if($paiement->student)

            <tr>

                <td class="info-label">
                    Élève / Étudiant
                </td>

                <td class="info-value">

                    {{ $paiement->student->nom ?? '' }}

                    {{ $paiement->student->prenom ?? '' }}

                </td>

            </tr>


            @if($paiement->student->matricule)

                <tr>

                    <td class="info-label">
                        Matricule
                    </td>

                    <td class="info-value">

                        {{ $paiement->student->matricule }}

                    </td>

                </tr>

            @endif

        @elseif($paiement->teacher)

            <tr>

                <td class="info-label">
                    Enseignant
                </td>

                <td class="info-value">

                    {{ $paiement->teacher->nom ?? '' }}

                    {{ $paiement->teacher->prenom ?? '' }}

                </td>

            </tr>

        @endif

    </table>



    <!-- =========================================================
         DÉTAILS DU PAIEMENT
         ========================================================= -->

    <div class="section-title">
        Détails du paiement
    </div>


    <table class="info-table">

        <tr>

            <td class="info-label">
                Type de paiement
            </td>

            <td class="info-value">

                {{ $paiement->type_paiement ?: '—' }}

            </td>

        </tr>


        @if($moisConcerne)

            <tr>

                <td class="info-label">
                    Mois concerné
                </td>

                <td class="info-value">

                    {{ $moisConcerne }}

                </td>

            </tr>

        @endif


        @if($paiement->reference)

            <tr>

                <td class="info-label">
                    Référence
                </td>

                <td class="info-value">

                    {{ $paiement->reference }}

                </td>

            </tr>

        @endif


        @if($paiement->description)

            <tr>

                <td class="info-label">
                    Description
                </td>

                <td class="info-value">

                    {{ $paiement->description }}

                </td>

            </tr>

        @endif

    </table>



    <!-- =========================================================
         MONTANT
         ========================================================= -->

    <div class="amount-box">

        <div class="amount-label">
            Montant payé
        </div>


        <div class="amount">

            {{ number_format(
                (float) $paiement->montant,
                2,
                ',',
                ' '
            ) }}

            Ar

        </div>

    </div>


    <div class="payment-note">

        Paiement enregistré avec succès.

    </div>



    <!-- =========================================================
         SIGNATURES
         ========================================================= -->

    <table class="signature-table">

        <tr>

            <td>

                <div class="signature-title">
                    Le bénéficiaire
                </div>

                <div class="signature-line">
                    Signature
                </div>

            </td>


            <td>

                <div class="signature-title">
                    Administration
                </div>

                <div class="signature-line">
                    Cachet et signature
                </div>

            </td>

        </tr>

    </table>



    <!-- =========================================================
         FOOTER
         ========================================================= -->

    <div class="footer">

        Reçu officiel de paiement

        @if($etablissement?->nom)
            — {{ $etablissement->nom }}
        @endif

    </div>


</body>
</html>
