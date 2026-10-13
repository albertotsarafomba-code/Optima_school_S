@extends('layouts.gestion-ecole')

@section('title', 'Droits d’inscription')
@section('page-title', 'Droits d’inscription')
@section('page-description', 'Suivi des paiements de droits d’inscription')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-blue-950">
                Droits d'inscription
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                {{ $establishment->nom }} · Année scolaire :
                <strong>{{ $schoolYear->name }}</strong>
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('paiements.droits.create', ['school_year_id' => $schoolYear->id]) }}"
               class="inline-flex items-center justify-center rounded-xl bg-green-600 px-5 py-3 text-sm font-bold text-white hover:bg-green-700">
                + Enregistrer un droit
            </a>

            <a href="{{ route('paiements.index', ['school_year_id' => $schoolYear->id]) }}"
               class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Retour aux écolages
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm font-medium text-amber-900">
            {{ session('warning') }}
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <p class="font-bold">Une erreur est survenue :</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-gray-500">Total encaissé</p>
            <p class="mt-2 text-2xl font-extrabold text-blue-950">
                {{ number_format((float) $totalCollected, 0, ',', ' ') }} Ar
            </p>
        </div>

        <div class="rounded-2xl border border-green-100 bg-white p-5 shadow-sm">
            <p class="text-sm font-semibold text-gray-500">Nombre de paiements</p>
            <p class="mt-2 text-2xl font-extrabold text-green-700">
                {{ number_format($paymentCount, 0, ',', ' ') }}
            </p>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
        <form method="GET"
              action="{{ route('paiements.droits.index') }}"
              class="grid grid-cols-1 gap-4 md:grid-cols-3">

            <div>
                <label for="school_year_id"
                       class="mb-2 block text-sm font-semibold text-gray-700">
                    Année scolaire
                </label>
                <select id="school_year_id" name="school_year_id"
                        class="w-full rounded-xl border border-gray-300 bg-white px-3 py-3">
                    @foreach($schoolYears as $yearItem)
                        <option value="{{ $yearItem->id }}"
                            @selected((int) $schoolYear->id === (int) $yearItem->id)>
                            {{ $yearItem->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="q"
                       class="mb-2 block text-sm font-semibold text-gray-700">
                    Rechercher un élève
                </label>
                <input id="q" name="q" type="search"
                       value="{{ request('q') }}"
                       placeholder="Nom, prénom ou matricule"
                       class="w-full rounded-xl border border-gray-300 px-3 py-3">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                        class="rounded-xl bg-blue-900 px-5 py-3 text-sm font-bold text-white hover:bg-blue-800">
                    Rechercher
                </button>
                <a href="{{ route('paiements.droits.index', ['school_year_id' => $schoolYear->id]) }}"
                   class="rounded-xl border border-gray-300 px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-5 py-4">
            <h2 class="font-bold text-gray-900">Historique des droits d'inscription</h2>
            <p class="mt-1 text-sm text-gray-500">
                Ces paiements sont séparés de l'écolage mensuel.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-600">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-600">Reçu</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-600">Élève</th>
                        <th class="px-4 py-3 text-right text-xs font-bold uppercase text-gray-600">Montant</th>
                        <th class="px-4 py-3 text-left text-xs font-bold uppercase text-gray-600">Mode</th>
                        <th class="px-4 py-3 text-right text-xs font-bold uppercase text-gray-600">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($paiements as $paiement)
                        <tr class="hover:bg-blue-50/40">
                            <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-700">
                                {{ $paiement->date_paiement?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-sm font-semibold text-blue-900">
                                {{ $paiement->numero_recu ?: '—' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-900">
                                <div class="font-semibold">
                                    {{ $paiement->student?->nom }} {{ $paiement->student?->prenom }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $paiement->student?->matricule ?: 'Sans matricule' }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-right text-sm font-bold text-gray-900">
                                {{ number_format((float) $paiement->montant, 0, ',', ' ') }} Ar
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-700">
                                {{ $paiement->mode_paiement }}
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-right">
                                <div class="inline-flex flex-wrap justify-end gap-2">
                                    <a href="{{ route('paiements.droits.show', ['paiement' => $paiement->id, 'school_year_id' => $schoolYear->id]) }}"
                                       class="rounded-lg border border-blue-200 px-3 py-2 text-xs font-bold text-blue-800 hover:bg-blue-50">
                                        Détail
                                    </a>
                                    <a href="{{ route('paiements.droits.recu', ['paiement' => $paiement->id, 'school_year_id' => $schoolYear->id]) }}"
                                       class="rounded-lg bg-green-600 px-3 py-2 text-xs font-bold text-white hover:bg-green-700">
                                        Reçu PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500">
                                Aucun droit d'inscription enregistré pour cette année scolaire.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($paiements->hasPages())
            <div class="border-t border-gray-200 p-4">
                {{ $paiements->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
