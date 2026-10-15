@extends('layouts.gestion-ecole')

@section('content')
<div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">

    <div class="rounded-2xl bg-blue-900 p-6 text-white shadow-lg">
        <p class="text-sm font-semibold uppercase tracking-wide text-blue-200">
            OPTIMASCHOOL
        </p>

        <h1 class="mt-2 text-2xl font-bold">
            Mon abonnement logiciel
        </h1>

        <p class="mt-2 text-sm text-blue-100">
            Établissement :
            <span class="font-semibold">
                {{ $establishment->nom }}
            </span>
        </p>
    </div>

    @if(session('success'))
        <div role="status"
             class="rounded-xl border border-green-300 bg-green-50 p-4 text-sm font-medium text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div role="alert"
             class="rounded-xl border border-red-300 bg-red-50 p-4 text-sm font-medium text-red-800">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div role="alert"
             class="rounded-xl border border-red-300 bg-red-50 p-4 text-sm text-red-800">
            <p class="mb-2 font-bold">
                Veuillez corriger les erreurs suivantes :
            </p>

            <ul class="list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900">
                État de l'abonnement
            </h2>

            <div class="mt-5">
                <p class="text-sm text-gray-500">
                    Statut
                </p>

                @if($abonnement->statut === 'actif')
                    <span class="mt-2 inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-bold text-green-800">
                        Actif
                    </span>
                @elseif($abonnement->statut === 'inactif')
                    <span class="mt-2 inline-flex rounded-full bg-gray-100 px-3 py-1 text-sm font-bold text-gray-700">
                        Inactif
                    </span>
                @else
                    <span class="mt-2 inline-flex rounded-full bg-amber-100 px-3 py-1 text-sm font-bold text-amber-800">
                        {{ ucfirst($abonnement->statut) }}
                    </span>
                @endif
            </div>

            <div class="mt-5">
                <p class="text-sm text-gray-500">
                    Tarif mensuel
                </p>

                <p class="mt-1 text-2xl font-extrabold text-blue-900">
                    @if($abonnement->montant_mensuel !== null)
                        {{ number_format((float) $abonnement->montant_mensuel, 0, ',', ' ') }}
                        {{ $abonnement->devise }}
                    @else
                        Tarif non défini
                    @endif
                </p>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="rounded-xl bg-gray-50 p-4">
                    <p class="text-sm text-gray-500">
                        Date de début
                    </p>
                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $abonnement->date_debut
                            ? \Illuminate\Support\Carbon::parse($abonnement->date_debut)->format('d/m/Y')
                            : 'Non définie' }}
                    </p>
                </div>

                <div class="rounded-xl bg-gray-50 p-4">
                    <p class="text-sm text-gray-500">
                        Date d'expiration
                    </p>
                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $abonnement->date_expiration
                            ? \Illuminate\Support\Carbon::parse($abonnement->date_expiration)->format('d/m/Y')
                            : 'Non définie' }}
                    </p>
                </div>
            </div>

            <div class="mt-5 rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm leading-6 text-blue-900">
                Le logiciel reste accessible même si l'abonnement arrive à expiration.
                Le renouvellement est effectué après vérification du paiement par le
                Super Administrateur.
            </div>
        </section>

        <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900">
                Renouveler mon abonnement
            </h2>

            @if($abonnement->montant_mensuel === null
                || (float) $abonnement->montant_mensuel <= 0)

                <div class="mt-5 rounded-xl border border-amber-300 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
                    Le tarif mensuel n'a pas encore été configuré.
                    Veuillez contacter le Super Administrateur.
                    Vous ne pouvez pas soumettre de demande de paiement
                    tant que le tarif n'est pas défini.
                </div>

            @elseif($paiementEnAttente)

                <div class="mt-5 rounded-xl border border-amber-300 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
                    Une demande de paiement est déjà en attente de vérification.
                    Vous pourrez envoyer une nouvelle demande après son traitement.
                </div>

            @else

                <div class="mt-4 rounded-xl bg-green-50 p-4">
                    <p class="text-sm text-green-800">
                        Montant à payer
                    </p>

                    <p class="mt-1 text-2xl font-extrabold text-green-800">
                        {{ number_format((float) $abonnement->montant_mensuel, 0, ',', ' ') }}
                        {{ $abonnement->devise }}
                    </p>
                </div>

                <form
                    action="{{ route('admin.abonnement.paiements.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="mt-5 space-y-5"
                >
                    @csrf

                    <div>
                        <label for="mode_paiement"
                               class="mb-2 block text-sm font-semibold text-gray-700">
                            Mode de paiement
                        </label>

                        <select
                            id="mode_paiement"
                            name="mode_paiement"
                            required
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200"
                        >
                            <option value="">Choisir un mode de paiement</option>

                            @foreach([
                                'MVola',
                                'Orange Money',
                                'Airtel Money',
                                'Virement bancaire'
                            ] as $mode)
                                <option
                                    value="{{ $mode }}"
                                    @selected(old('mode_paiement') === $mode)
                                >
                                    {{ $mode }}
                                </option>
                            @endforeach
                        </select>

                        @error('mode_paiement')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="reference_transaction"
                               class="mb-2 block text-sm font-semibold text-gray-700">
                            Référence de transaction
                        </label>

                        <input
                            id="reference_transaction"
                            name="reference_transaction"
                            type="text"
                            maxlength="150"
                            value="{{ old('reference_transaction') }}"
                            required
                            autocomplete="off"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200"
                            placeholder="Ex. : référence de votre transaction"
                        >

                        @error('reference_transaction')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="preuve_paiement"
                               class="mb-2 block text-sm font-semibold text-gray-700">
                            Justificatif de paiement
                        </label>

                        <input
                            id="preuve_paiement"
                            name="preuve_paiement"
                            type="file"
                            accept=".jpg,.jpeg,.png,.pdf"
                            required
                            class="block w-full rounded-xl border border-gray-300 p-3 text-sm"
                        >

                        <p class="mt-2 text-xs leading-5 text-gray-500">
                            Formats acceptés : JPG, PNG ou PDF.
                            Taille maximale : 5 Mo.
                        </p>

                        @error('preuve_paiement')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-blue-900 px-5 py-3 font-bold text-white transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2"
                    >
                        Envoyer la demande de paiement
                    </button>

                    <p class="text-xs leading-5 text-gray-500">
                        L'envoi du justificatif ne confirme pas le paiement.
                        Le Super Administrateur doit vérifier la transaction réelle
                        avant d'activer ou de renouveler l'abonnement.
                    </p>
                </form>

            @endif
        </section>
    </div>

    <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-6 py-5">
            <h2 class="text-lg font-bold text-gray-900">
                Historique de mes demandes
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Consultez le statut des demandes de paiement de votre établissement.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600">
                            Montant
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600">
                            Mode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600">
                            Référence
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600">
                            Observation
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($paiements as $paiement)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                {{ $paiement->date_soumission
                                    ? $paiement->date_soumission->format('d/m/Y H:i')
                                    : $paiement->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-gray-900">
                                {{ number_format((float) $paiement->montant, 0, ',', ' ') }}
                                {{ $abonnement->devise }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                {{ $paiement->mode_paiement }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $paiement->reference_transaction ?: '—' }}
                            </td>

                            <td class="whitespace-nowrap px-6 py-4">
                                @if($paiement->statut === 'valide')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-800">
                                        Validé
                                    </span>
                                @elseif($paiement->statut === 'rejete')
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-800">
                                        Rejeté
                                    </span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">
                                        En attente
                                    </span>
                                @endif
                            </td>

                            <td class="max-w-xs px-6 py-4 text-sm text-gray-700">
                                {{ $paiement->note_verification ?: '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="px-6 py-10 text-center text-sm text-gray-500">
                                Aucune demande de paiement pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($paiements->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $paiements->links() }}
            </div>
        @endif
    </section>

</div>
@endsection
