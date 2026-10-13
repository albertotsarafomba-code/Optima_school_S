@extends('layouts.gestion-ecole')

@section('title', 'Détail du droit d’inscription')
@section('page-title', 'Détail du paiement')
@section('page-description', 'Droit d’inscription enregistré')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-blue-950">
                Droit d'inscription
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                {{ $establishment->nom }} · {{ $schoolYear->name }}
            </p>
        </div>

        <a href="{{ route('paiements.droits.index', ['school_year_id' => $schoolYear->id]) }}"
           class="rounded-xl border border-gray-300 bg-white px-4 py-3 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">
            Retour à la liste
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-gray-200 bg-blue-950 px-6 py-5 text-white sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-blue-100">Numéro du reçu</p>
                <p class="mt-1 text-lg font-extrabold">
                    {{ $paiement->numero_recu ?: '—' }}
                </p>
            </div>
            <span class="inline-flex w-fit rounded-full bg-green-100 px-3 py-1.5 text-sm font-bold text-green-800">
                Enregistré
            </span>
        </div>

        <div class="space-y-5 p-6">
            <div>
                <p class="text-sm text-gray-500">Élève</p>
                <p class="mt-1 text-lg font-bold text-gray-900">
                    {{ $paiement->student?->nom }} {{ $paiement->student?->prenom }}
                </p>
                <p class="text-sm text-gray-500">
                    Matricule : {{ $paiement->student?->matricule ?: '—' }}
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 border-t border-gray-100 pt-5 sm:grid-cols-2">
                <div>
                    <p class="text-sm text-gray-500">Type de paiement</p>
                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $paiement->type_paiement }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Année scolaire</p>
                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $schoolYear->name }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Date du paiement</p>
                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $paiement->date_paiement?->format('d/m/Y') ?? '—' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Mode de paiement</p>
                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $paiement->mode_paiement }}
                    </p>
                </div>
            </div>

            <div class="rounded-xl border border-green-200 bg-green-50 p-5">
                <p class="text-sm font-semibold text-green-800">Montant reçu</p>
                <p class="mt-1 text-3xl font-extrabold text-green-900">
                    {{ number_format((float) $paiement->montant, 0, ',', ' ') }} Ar
                </p>
            </div>

            @if($paiement->reference)
                <div>
                    <p class="text-sm text-gray-500">Référence</p>
                    <p class="mt-1 font-medium text-gray-900">{{ $paiement->reference }}</p>
                </div>
            @endif

            @if($paiement->description)
                <div>
                    <p class="text-sm text-gray-500">Observation</p>
                    <p class="mt-1 text-gray-900">{{ $paiement->description }}</p>
                </div>
            @endif
        </div>

        <div class="flex flex-col gap-3 border-t border-gray-200 bg-gray-50 p-5 sm:flex-row sm:justify-end">
            <a href="{{ route('paiements.droits.recu', ['paiement' => $paiement->id, 'school_year_id' => $schoolYear->id]) }}"
               class="inline-flex items-center justify-center rounded-xl bg-green-600 px-5 py-3 text-sm font-bold text-white hover:bg-green-700">
                Télécharger le reçu PDF
            </a>
            <a href="{{ route('paiements.droits.create', ['school_year_id' => $schoolYear->id]) }}"
               class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-100">
                Nouveau droit d'inscription
            </a>
        </div>
    </div>
</div>
@endsection
