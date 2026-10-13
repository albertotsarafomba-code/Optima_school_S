@extends('layouts.gestion-ecole')

@section('title', 'Enregistrer un droit d’inscription')
@section('page-title', 'Droit d’inscription')
@section('page-description', 'Enregistrement d’un paiement en espèces')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-extrabold text-blue-950">
                Enregistrer un droit d'inscription
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                {{ $establishment->nom }} · Année scolaire {{ $schoolYear->name }}
            </p>
        </div>

        <a href="{{ route('paiements.droits.index', ['school_year_id' => $schoolYear->id]) }}"
           class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            Retour à la liste
        </a>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <p class="font-bold">Veuillez corriger les erreurs suivantes :</p>
            <ul class="mt-2 list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
        <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">
            Mode de paiement
        </p>
        <p class="mt-1 text-xl font-extrabold text-blue-950">Espèces</p>
        <p class="mt-2 text-sm text-blue-800">
            Le droit d'inscription est enregistré séparément des écolages mensuels.
        </p>
    </div>

    <form method="POST" action="{{ route('paiements.droits.store') }}"
          class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        @csrf

        <input type="hidden" name="school_year_id" value="{{ $schoolYear->id }}">

        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <h2 class="text-lg font-bold text-gray-900">Informations du paiement</h2>
            <p class="mt-1 text-sm text-gray-500">
                Un seul droit d'inscription peut être enregistré par élève et par année scolaire.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

            <div class="md:col-span-2">
                <label for="student_id" class="mb-2 block text-sm font-semibold text-gray-700">
                    Élève <span class="text-red-600">*</span>
                </label>

                <select id="student_id" name="student_id" required
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3">
                    <option value="">-- Sélectionner un élève inscrit --</option>
                    @foreach($inscriptions as $inscription)
                        @if($inscription->student)
                            <option value="{{ $inscription->student->id }}"
                                @selected((string) old('student_id') === (string) $inscription->student->id)>
                                {{ $inscription->student->matricule ? $inscription->student->matricule . ' — ' : '' }}
                                {{ $inscription->student->nom }} {{ $inscription->student->prenom }}
                            </option>
                        @endif
                    @endforeach
                </select>

                @if($inscriptions->isEmpty())
                    <p class="mt-2 text-sm font-medium text-amber-700">
                        Aucun élève éligible n'est disponible. Vérifiez les inscriptions de cette année scolaire
                        ou consultez la liste des droits déjà enregistrés.
                    </p>
                @endif
            </div>

            <div>
                <label for="montant" class="mb-2 block text-sm font-semibold text-gray-700">
                    Montant versé (Ar) <span class="text-red-600">*</span>
                </label>
                <input id="montant" name="montant" type="number"
                       min="1" step="0.01" required
                       value="{{ old('montant') }}"
                       placeholder="Ex. : 20000"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3">
            </div>

            <div>
                <label for="date_paiement" class="mb-2 block text-sm font-semibold text-gray-700">
                    Date du paiement <span class="text-red-600">*</span>
                </label>
                <input id="date_paiement" name="date_paiement" type="date" required
                       value="{{ old('date_paiement', now()->toDateString()) }}"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3">
            </div>

            <div class="md:col-span-2">
                <label for="reference" class="mb-2 block text-sm font-semibold text-gray-700">
                    Référence (facultatif)
                </label>
                <input id="reference" name="reference" type="text"
                       maxlength="255" value="{{ old('reference') }}"
                       class="w-full rounded-xl border border-gray-300 px-4 py-3">
            </div>

            <div class="md:col-span-2">
                <label for="description" class="mb-2 block text-sm font-semibold text-gray-700">
                    Observation (facultatif)
                </label>
                <textarea id="description" name="description" rows="3"
                          class="w-full rounded-xl border border-gray-300 px-4 py-3">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="flex flex-col-reverse justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4 sm:flex-row">
            <a href="{{ route('paiements.droits.index', ['school_year_id' => $schoolYear->id]) }}"
               class="rounded-xl border border-gray-300 bg-white px-5 py-3 text-center text-sm font-semibold text-gray-700 hover:bg-gray-100">
                Annuler
            </a>
            <button type="submit"
                    @disabled($inscriptions->isEmpty())
                    class="rounded-xl bg-green-600 px-6 py-3 text-sm font-bold text-white hover:bg-green-700 disabled:cursor-not-allowed disabled:opacity-50">
                Enregistrer et générer le reçu
            </button>
        </div>
    </form>
</div>
@endsection
