@extends('layouts.gestion-ecole')

@section('title', 'Enregistrer un écolage')
@section('page-title', 'Gestion des écolages')
@section('page-description', 'Enregistrer un paiement mensuel en espèces')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <div class="mb-2 text-sm text-gray-500">
                Paiements / Nouvel écolage
            </div>

            <h2 class="text-2xl font-extrabold tracking-tight text-blue-950">
                Enregistrer un écolage
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Saisissez les informations du paiement de l'élève.
            </p>
        </div>

        <a href="{{ route('paiements.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-blue-50">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19 12H5m7 7-7-7 7-7"/>
            </svg>
            Retour aux paiements
        </a>
    </div>

    @if ($errors->any())
        @php
            $tarifNonConfigure = collect($errors->all())->contains(
                fn ($error) => str_contains(
                    mb_strtolower($error),
                    'aucun tarif mensuel'
                )
            );
        @endphp

        @if ($tarifNonConfigure)
            <div class="rounded-xl border-2 border-amber-300 bg-amber-50 p-5 text-amber-950"
                 role="alert">
                <div class="flex items-start gap-3">
                    <svg class="mt-0.5 h-7 w-7 shrink-0 text-amber-700"
                         xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         aria-hidden="true">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 9v4m0 4h.01M10.3 3.86 1.82 18.5A2 2 0 0 0 3.55 21h16.9a2 2 0 0 0 1.73-2.5L13.7 3.86a2 2 0 0 0-3.4 0Z"/>
                    </svg>

                    <div>
                        <h3 class="text-lg font-bold">
                            Tarif mensuel non configuré
                        </h3>

                        <p class="mt-2 text-sm leading-6">
                            Aucun tarif mensuel n'est encore configuré pour
                            la classe de cet élève. Le paiement ne peut pas
                            être enregistré pour le moment.
                        </p>

                        <p class="mt-2 text-sm font-semibold">
                            Veuillez contacter l'administration afin que le
                            tarif mensuel soit enregistré avant de saisir
                            le paiement.
                        </p>
                    </div>
                </div>
            </div>

            @if ($errors->count() > 1)
                <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                    <h4 class="font-bold">Autres erreurs :</h4>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            @if (!str_contains(mb_strtolower($error), 'aucun tarif mensuel'))
                                <li>{{ $error }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif
        @else
            <div class="rounded-xl border border-red-200 bg-red-50 p-5 text-red-800"
                 role="alert">
                <h3 class="font-bold">
                    Veuillez corriger les erreurs suivantes :
                </h3>
                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif

    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
        <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">
            Année scolaire
        </p>
        <p class="mt-1 text-xl font-extrabold text-blue-950">
            {{ $schoolYear?->name ?? session('school_year_name', 'Année non définie') }}
        </p>
        <p class="mt-2 text-sm text-blue-800">
            Mode de paiement : <strong>Espèces</strong>
        </p>
    </div>

    <form action="{{ route('paiements.store') }}"
          method="POST"
          class="space-y-6">
        @csrf

        <input type="hidden"
               name="school_year_id"
               value="{{ $schoolYear->id }}">

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h3 class="text-lg font-bold text-gray-900">Informations du paiement</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Les élèves proposés sont inscrits pour l'année scolaire sélectionnée.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                <div class="md:col-span-2">
                    <label for="student_id"
                           class="mb-2 block text-sm font-semibold text-gray-700">
                        Élève <span class="text-red-600">*</span>
                    </label>

                    <select id="student_id"
                            name="student_id"
                            required
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-gray-900 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="">-- Sélectionner un élève --</option>

                        @foreach ($students as $student)
                            <option value="{{ $student->id }}"
                                {{ (string) old('student_id', $prefill['student_id'] ?? '') === (string) $student->id ? 'selected' : '' }}>
                                {{ $student->matricule ? $student->matricule . ' - ' : '' }}
                                {{ $student->nom }} {{ $student->prenom }}
                                @if ($student->inscription_class_room_name)
                                    — {{ $student->inscription_class_room_name }}
                                @endif
                            </option>
                        @endforeach
                    </select>

                    @if ($students->isEmpty())
                        <p class="mt-2 text-sm text-red-600">
                            Aucun élève inscrit n'est disponible pour cette année scolaire.
                        </p>
                    @endif
                </div>

                <div>
                    <label for="mois"
                           class="mb-2 block text-sm font-semibold text-gray-700">
                        Mois d'écolage <span class="text-red-600">*</span>
                    </label>

                    <select id="mois"
                            name="mois"
                            required
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        @foreach ($mois as $numero => $nomMois)
                            <option value="{{ $numero }}"
                                {{ (int) old('mois', $selectedMonth) === (int) $numero ? 'selected' : '' }}>
                                {{ $nomMois }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="annee"
                           class="mb-2 block text-sm font-semibold text-gray-700">
                        Année du paiement <span class="text-red-600">*</span>
                    </label>

                    <input id="annee"
                           name="annee"
                           type="number"
                           min="2000"
                           max="2100"
                           required
                           value="{{ old('annee', $selectedYear) }}"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label for="montant"
                           class="mb-2 block text-sm font-semibold text-gray-700">
                        Montant versé (Ar) <span class="text-red-600">*</span>
                    </label>

                    <input id="montant"
                           name="montant"
                           type="number"
                           min="0.01"
                           step="0.01"
                           required
                           value="{{ old('montant') }}"
                           placeholder="Ex. : 100000"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">

                    <p class="mt-2 text-xs text-gray-500">
                        Le montant ne doit pas dépasser le reste à payer pour le mois choisi.
                    </p>
                </div>

                <div>
                    <label for="date_paiement"
                           class="mb-2 block text-sm font-semibold text-gray-700">
                        Date du paiement <span class="text-red-600">*</span>
                    </label>

                    <input id="date_paiement"
                           name="date_paiement"
                           type="date"
                           required
                           value="{{ old('date_paiement', now()->format('Y-m-d')) }}"
                           class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                </div>

                <div class="md:col-span-2">
                    <label for="description"
                           class="mb-2 block text-sm font-semibold text-gray-700">
                        Description (facultative)
                    </label>

                    <textarea id="description"
                              name="description"
                              rows="3"
                              maxlength="1000"
                              placeholder="Remarque éventuelle sur le paiement..."
                              class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('description') }}</textarea>
                </div>

            </div>
        </section>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('paiements.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 font-semibold text-gray-700 transition hover:bg-gray-50">
                Annuler
            </a>

            <button type="submit"
                    {{ $students->isEmpty() ? 'disabled' : '' }}
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-900 px-6 py-3 font-bold text-white shadow-sm transition hover:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-50">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M5 12l4 4L19 6"/>
                </svg>
                Enregistrer et afficher le reçu
            </button>
        </div>
    </form>
</div>
@endsection
