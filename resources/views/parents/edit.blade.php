@extends('layouts.gestion-ecole')

@section('title', 'Modifier le parent - OPTIMA SCHOOL')
@section('page-title', 'Modifier le parent')

@section('content')

    @php
        $selectedStudentIds = old(
            'student_ids',
            $parent->students->pluck('id')->all()
        );
    @endphp

    <div class="mx-auto max-w-5xl space-y-6">

        {{-- =========================================================
             EN-TÊTE
        ========================================================== --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <div class="flex items-center gap-3">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"
                            />
                        </svg>

                    </div>

                    <div>

                        <h1 class="text-2xl font-bold text-blue-950">
                            Modifier le parent
                        </h1>

                        <p class="mt-1 text-sm text-slate-500">
                            Modifier les informations et les élèves rattachés
                        </p>

                    </div>

                </div>

            </div>

            <div class="flex flex-wrap items-center gap-3">

                <a
                    href="{{ route('parents.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl
                           border border-slate-300 bg-white px-5 py-3
                           text-sm font-bold text-slate-700 shadow-sm
                           transition hover:border-blue-400 hover:bg-slate-50"
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>

                    Retour

                </a>

            </div>

        </div>

        {{-- =========================================================
             ÉTABLISSEMENT
        ========================================================== --}}
        <div class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 shadow-sm">

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-700 text-white">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 21h18M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16M9 7h2M9 11h2M13 7h2M13 11h2M9 15h2M13 15h2"
                            />
                        </svg>

                    </div>

                    <div>

                        <p class="text-xs font-bold uppercase tracking-wide text-blue-600">
                            Établissement
                        </p>

                        <p class="text-base font-bold text-blue-950">
                            {{ $establishment->nom }}
                        </p>

                    </div>

                </div>

                <span class="inline-flex w-fit items-center rounded-full bg-green-100 px-3 py-1.5 text-xs font-bold text-green-700">
                    Modification sécurisée
                </span>

            </div>

        </div>

        {{-- =========================================================
             ERREURS DE VALIDATION
        ========================================================== --}}
        @if($errors->any())

            <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 shadow-sm">

                <div class="flex items-start gap-3">

                    <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-700">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v4M12 17h.01M10.29 3.86l-8.18 14A2 2 0 0 0 3.82 21h16.36a2 2 0 0 0 1.72-3.14l-8.18-14a2 2 0 0 0-3.43 0Z"
                            />
                        </svg>

                    </div>

                    <div>

                        <p class="font-bold text-red-800">
                            Certaines informations doivent être corrigées.
                        </p>

                        <ul class="mt-2 space-y-1 text-sm text-red-700">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                </div>

            </div>

        @endif

        {{-- =========================================================
             FORMULAIRE
        ========================================================== --}}
        <form
            method="POST"
            action="{{ route('parents.update', $parent) }}"
            class="space-y-6"
        >

            @csrf
            @method('PUT')

            {{-- =====================================================
                 INFORMATIONS PERSONNELLES
            ====================================================== --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 19a6 6 0 0 0-12 0M9 13a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM19 8v6M22 11h-6"
                                />
                            </svg>

                        </div>

                        <div>

                            <h2 class="text-lg font-bold text-blue-950">
                                Informations personnelles
                            </h2>

                            <p class="text-sm text-slate-500">
                                Coordonnées du parent
                            </p>

                        </div>

                    </div>

                </div>

                <div class="grid gap-5 px-6 py-6 md:grid-cols-2">

                    {{-- Prénom --}}
                    <div>

                        <label
                            for="prenom"
                            class="mb-2 block text-sm font-bold text-slate-700"
                        >
                            Prénom
                        </label>

                        <input
                            id="prenom"
                            type="text"
                            name="prenom"
                            value="{{ old('prenom', $parent->prenom) }}"
                            maxlength="255"
                            autocomplete="given-name"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3
                                   text-sm text-slate-900 outline-none transition
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                            placeholder="Prénom du parent"
                        >

                        @error('prenom')
                            <p class="mt-2 text-sm font-semibold text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Nom --}}
                    <div>

                        <label
                            for="nom"
                            class="mb-2 block text-sm font-bold text-slate-700"
                        >
                            Nom <span class="text-red-600">*</span>
                        </label>

                        <input
                            id="nom"
                            type="text"
                            name="nom"
                            value="{{ old('nom', $parent->nom) }}"
                            maxlength="255"
                            autocomplete="family-name"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3
                                   text-sm text-slate-900 outline-none transition
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                            placeholder="Nom du parent"
                        >

                        @error('nom')
                            <p class="mt-2 text-sm font-semibold text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Téléphone --}}
                    <div>

                        <label
                            for="telephone"
                            class="mb-2 block text-sm font-bold text-slate-700"
                        >
                            Téléphone
                        </label>

                        <input
                            id="telephone"
                            type="text"
                            name="telephone"
                            value="{{ old('telephone', $parent->telephone) }}"
                            maxlength="30"
                            autocomplete="tel"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3
                                   text-sm text-slate-900 outline-none transition
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                            placeholder="Ex. 034 00 000 00"
                        >

                        @error('telephone')
                            <p class="mt-2 text-sm font-semibold text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Adresse --}}
                    <div>

                        <label
                            for="adresse"
                            class="mb-2 block text-sm font-bold text-slate-700"
                        >
                            Adresse
                        </label>

                        <input
                            id="adresse"
                            type="text"
                            name="adresse"
                            value="{{ old('adresse', $parent->adresse) }}"
                            maxlength="255"
                            autocomplete="street-address"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3
                                   text-sm text-slate-900 outline-none transition
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                            placeholder="Adresse du parent"
                        >

                        @error('adresse')
                            <p class="mt-2 text-sm font-semibold text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>

            </div>

            {{-- =====================================================
                 COMPTE UTILISATEUR
            ====================================================== --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-700">

                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM5 21a7 7 0 0 1 14 0M19 11h2a2 2 0 0 1 2 2v7H17"
                                />
                            </svg>

                        </div>

                        <div>

                            <h2 class="text-lg font-bold text-blue-950">
                                Compte utilisateur
                            </h2>

                            <p class="text-sm text-slate-500">
                                Identifiants du compte parent
                            </p>

                        </div>

                    </div>

                </div>

                <div class="grid gap-5 px-6 py-6 md:grid-cols-2">

                    {{-- E-mail --}}
                    <div class="md:col-span-2">

                        <label
                            for="email"
                            class="mb-2 block text-sm font-bold text-slate-700"
                        >
                            Adresse e-mail <span class="text-red-600">*</span>
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', $parent->email ?? $parent->user?->email) }}"
                            maxlength="255"
                            autocomplete="email"
                            required
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3
                                   text-sm text-slate-900 outline-none transition
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                            placeholder="parent@exemple.com"
                        >

                        <p class="mt-2 text-xs text-slate-500">
                            Cette adresse sert également à la connexion du compte parent.
                        </p>

                        @error('email')
                            <p class="mt-2 text-sm font-semibold text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Nouveau mot de passe --}}
                    <div>

                        <label
                            for="password"
                            class="mb-2 block text-sm font-bold text-slate-700"
                        >
                            Nouveau mot de passe
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            minlength="8"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3
                                   text-sm text-slate-900 outline-none transition
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                            placeholder="Laisser vide pour conserver"
                        >

                        <p class="mt-2 text-xs text-slate-500">
                            Minimum 8 caractères. Laisser vide pour conserver le mot de passe actuel.
                        </p>

                        @error('password')
                            <p class="mt-2 text-sm font-semibold text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    {{-- Confirmation --}}
                    <div>

                        <label
                            for="password_confirmation"
                            class="mb-2 block text-sm font-bold text-slate-700"
                        >
                            Confirmation du mot de passe
                        </label>

                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            minlength="8"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3
                                   text-sm text-slate-900 outline-none transition
                                   placeholder:text-slate-400
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                            placeholder="Confirmer le nouveau mot de passe"
                        >

                    </div>

                </div>

            </div>

            {{-- =====================================================
                 ÉLÈVES RATTACHÉS
            ====================================================== --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M17 20h5v-2a4 4 0 0 0-4-4h-1M9 20H4v-2a4 4 0 0 1 4-4h1M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM16 11a4 4 0 1 0 0-7.75M20 20v-2a4 4 0 0 0-3-3.87M4 20v-2a4 4 0 0 1 3-3.87"
                                    />
                                </svg>

                            </div>

                            <div>

                                <h2 class="text-lg font-bold text-blue-950">
                                    Élèves rattachés
                                </h2>

                                <p class="text-sm text-slate-500">
                                    Sélectionnez les élèves associés à ce parent
                                </p>

                            </div>

                        </div>

                        <span class="inline-flex w-fit items-center rounded-full bg-green-100 px-3 py-1.5 text-sm font-bold text-green-700">
                            {{ count($selectedStudentIds) }}
                            {{ count($selectedStudentIds) > 1 ? 'élèves sélectionnés' : 'élève sélectionné' }}
                        </span>

                    </div>

                </div>

                <div class="px-6 py-6">

                    @if($students->isNotEmpty())

                        <div class="grid gap-4 md:grid-cols-2">

                            @foreach($students as $student)

                                <label
                                    for="student-{{ $student->id }}"
                                    class="group flex cursor-pointer items-start gap-4 rounded-2xl
                                           border border-slate-200 bg-white p-4
                                           transition hover:border-blue-300 hover:bg-blue-50/50"
                                >

                                    <input
                                        id="student-{{ $student->id }}"
                                        type="checkbox"
                                        name="student_ids[]"
                                        value="{{ $student->id }}"
                                        @checked(in_array($student->id, $selectedStudentIds))
                                        class="mt-1 h-5 w-5 rounded border-slate-300
                                               text-blue-700 focus:ring-2 focus:ring-blue-200"
                                    >

                                    <span class="min-w-0 flex-1">

                                        <span class="block text-sm font-bold text-slate-900">
                                            {{ trim($student->prenom . ' ' . $student->nom) }}
                                        </span>

                                        <span class="mt-1 block text-xs text-slate-500">
                                            Matricule :
                                            <span class="font-semibold text-slate-700">
                                                {{ $student->matricule ?: 'Non renseigné' }}
                                            </span>
                                        </span>

                                        @if($student->classe)

                                            <span class="mt-1 block text-xs text-slate-500">
                                                Classe :
                                                <span class="font-semibold text-blue-700">
                                                    {{ $student->classe }}
                                                </span>
                                            </span>

                                        @endif

                                    </span>

                                </label>

                            @endforeach

                        </div>

                        <div class="mt-5 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3">

                            <p class="text-sm text-blue-800">
                                <span class="font-bold">Information :</span>
                                les élèves proposés appartiennent uniquement à l'établissement
                                <span class="font-bold">{{ $establishment->nom }}</span>.
                            </p>

                        </div>

                    @else

                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center">

                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white text-slate-500 shadow-sm">

                                <svg
                                    class="h-7 w-7"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M17 20h5v-2a4 4 0 0 0-4-4h-1M9 20H4v-2a4 4 0 0 1 4-4h1M12 12a4 4 0 1 0-0-8 4 4 0 0 0 0 8"
                                    />
                                </svg>

                            </div>

                            <h3 class="mt-4 text-base font-bold text-slate-900">
                                Aucun élève disponible
                            </h3>

                            <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                                Aucun élève n'est actuellement enregistré dans cet établissement.
                            </p>

                        </div>

                    @endif

                    @error('student_ids')
                        <p class="mt-3 text-sm font-semibold text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    @error('student_ids.*')
                        <p class="mt-3 text-sm font-semibold text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>

            {{-- =====================================================
                 ACTIONS
            ====================================================== --}}
            <div class="flex flex-col gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:items-center sm:justify-between">

                <a
                    href="{{ route('parents.show', $parent) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl
                           border border-slate-300 bg-white px-6 py-3
                           text-sm font-bold text-slate-700 shadow-sm
                           transition hover:border-blue-400 hover:bg-slate-50"
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>

                    Annuler

                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl
                           bg-green-600 px-6 py-3 text-sm font-bold text-white
                           shadow-md transition hover:bg-green-700 hover:shadow-lg"
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                    Enregistrer les modifications

                </button>

            </div>

        </form>

    </div>

@endsection
