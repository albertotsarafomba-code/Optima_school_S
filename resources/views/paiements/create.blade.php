@extends('layouts.gestion-ecole')

@section('title', 'Ajouter un paiement')

@section('page-title', 'Gestion des paiements')

@section('page-description', 'Enregistrer un nouveau paiement')

@section('content')

<div class="mx-auto max-w-6xl space-y-6">

    {{-- ========================================================= --}}
    {{-- EN-TÊTE --}}
    {{-- ========================================================= --}}

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">

                <a
                    href="{{ route('paiements.index') }}"
                    class="transition hover:text-blue-700"
                >
                    Paiements
                </a>

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 18l6-6-6-6"
                    />
                </svg>

                <span class="font-medium text-blue-900">
                    Ajouter
                </span>

            </div>

            <h2 class="text-2xl font-extrabold tracking-tight text-blue-950">
                Ajouter un paiement
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Enregistrez un paiement d'élève ou une cotisation d'enseignant.
            </p>

        </div>


        <a
            href="{{ route('paiements.index') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-800"
        >

            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M19 12H5"
                />

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 19l-7-7 7-7"
                />
            </svg>

            Liste des paiements

        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- ERREURS --}}
    {{-- ========================================================= --}}

    @if ($errors->any())

        <div class="rounded-2xl border border-red-200 bg-red-50 p-5 shadow-sm">

            <div class="flex items-start gap-4">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-500 text-white">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle
                            cx="12"
                            cy="12"
                            r="9"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 8v4"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 16h.01"
                        />
                    </svg>

                </div>

                <div>

                    <h3 class="font-bold text-red-800">
                        Vérifiez les informations saisies
                    </h3>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- ANNÉE SCOLAIRE --}}
    {{-- ========================================================= --}}

    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 shadow-sm">

        <div class="flex items-start gap-4">

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <rect
                        x="3"
                        y="4"
                        width="18"
                        height="17"
                        rx="2"
                    />

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16 2v4M8 2v4M3 10h18"
                    />
                </svg>

            </div>

            <div>

                <p class="text-xs font-bold uppercase tracking-wider text-blue-700">
                    Année scolaire sélectionnée
                </p>

                <p class="mt-1 text-lg font-extrabold text-blue-950">

                    {{ $schoolYear?->name ?? session('school_year_name', 'Aucune année sélectionnée') }}

                </p>

                @if (!$schoolYear)

                    <p class="mt-1 text-sm text-red-600">
                        Aucune année scolaire n'est actuellement sélectionnée.
                    </p>

                @endif

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FORMULAIRE --}}
    {{-- ========================================================= --}}

    <form
        action="{{ route('paiements.store') }}"
        method="POST"
        class="space-y-6"
    >

        @csrf


        {{-- ===================================================== --}}
        {{-- BÉNÉFICIAIRE --}}
        {{-- ===================================================== --}}

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-100 bg-gray-50 px-6 py-5">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"
                            />

                            <circle
                                cx="9"
                                cy="7"
                                r="4"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M22 21v-2a4 4 0 00-3-3.87"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16 3.13a4 4 0 010 7.75"
                            />

                        </svg>

                    </div>

                    <div>

                        <h3 class="font-bold text-blue-950">
                            Bénéficiaire du paiement
                        </h3>

                        <p class="text-sm text-gray-500">
                            Choisissez si le paiement concerne un élève ou un enseignant.
                        </p>

                    </div>

                </div>

            </div>


            <div class="p-6">

                {{-- Type bénéficiaire --}}

                <div class="mb-6">

                    <label
                        for="beneficiaire_type"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >

                        Bénéficiaire
                        <span class="text-red-500">*</span>

                    </label>


                    <select
                        name="beneficiaire_type"
                        id="beneficiaire_type"
                        required
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >

                        <option
                            value="student"
                            {{ old('beneficiaire_type', $prefill['beneficiaire_type'] ?? 'student') === 'student' ? 'selected' : '' }}
                        >
                            Élève
                        </option>

                        <option
                            value="teacher"
                            {{ old('beneficiaire_type', $prefill['beneficiaire_type'] ?? 'student') === 'teacher' ? 'selected' : '' }}
                        >
                            Enseignant
                        </option>

                    </select>


                    @error('beneficiaire_type')

                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- ÉLÈVE --}}
                {{-- ================================================= --}}

                <div id="student_section">

                    <label
                        for="student_search"
                        class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 text-blue-600"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <circle
                                cx="11"
                                cy="11"
                                r="7"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M20 20l-3.5-3.5"
                            />
                        </svg>

                        Rechercher un élève

                    </label>


                    <div class="relative mb-4">

                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 text-gray-400"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <circle
                                    cx="11"
                                    cy="11"
                                    r="8"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m21 21-4.3-4.3"
                                />
                            </svg>

                        </div>


                        <input
                            type="text"
                            id="student_search"
                            placeholder="Rechercher par matricule, nom ou prénom..."
                            autocomplete="off"
                            class="w-full rounded-xl border border-gray-300 bg-white py-3 pl-11 pr-12 text-sm text-gray-800 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >


                        <button
                            type="button"
                            id="clear_student_search"
                            class="absolute inset-y-0 right-0 hidden items-center pr-4 text-gray-400 transition hover:text-red-500"
                            title="Effacer la recherche"
                        >

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M6 6l12 12M18 6L6 18"
                                />
                            </svg>

                        </button>

                    </div>


                    <label
                        for="student_id"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >

                        Élève
                        <span class="text-red-500">*</span>

                    </label>


                    <select
                        name="student_id"
                        id="student_id"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >

                        <option value="">
                            -- Sélectionner un élève --
                        </option>


                        @foreach($students as $student)

                            <option
                                value="{{ $student->id }}"
                                data-search="{{ strtolower($student->matricule . ' ' . $student->nom . ' ' . $student->prenom) }}"
                                {{ old('student_id', $prefill['student_id'] ?? '') == $student->id ? 'selected' : '' }}
                            >

                                {{ $student->matricule }} -
                                {{ $student->nom }}
                                {{ $student->prenom }}

                            </option>

                        @endforeach

                    </select>


                    <p
                        id="student_search_result"
                        class="mt-3 text-xs font-medium text-gray-500"
                    >
                        {{ $students->count() }} élève(s) disponible(s)
                    </p>


                    @error('student_id')

                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- ENSEIGNANT --}}
                {{-- ================================================= --}}

                <div id="teacher_section" class="hidden">

                    <label
                        for="teacher_id"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >

                        Enseignant
                        <span class="text-red-500">*</span>

                    </label>


                    <select
                        name="teacher_id"
                        id="teacher_id"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 shadow-sm outline-none transition focus:border-green-500 focus:ring-4 focus:ring-green-100"
                    >

                        <option value="">
                            -- Sélectionner un enseignant --
                        </option>


                        @foreach($teachers as $teacher)

                            <option
                                value="{{ $teacher->id }}"
                                {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}
                            >

                                {{ $teacher->nom }}
                                {{ $teacher->prenom }}

                                @if($teacher->specialite)
                                    — {{ $teacher->specialite }}
                                @endif

                            </option>

                        @endforeach

                    </select>


                    <p class="mt-3 text-xs text-gray-500">
                        {{ $teachers->count() }} enseignant(s) disponible(s)
                    </p>


                    @error('teacher_id')

                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- INFORMATIONS DU PAIEMENT --}}
        {{-- ========================================================= --}}

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-100 bg-gray-50 px-6 py-5">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100 text-green-700">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 1v22"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"
                            />

                        </svg>

                    </div>

                    <div>

                        <h3 class="font-bold text-blue-950">
                            Informations du paiement
                        </h3>

                        <p class="text-sm text-gray-500">
                            Montant, type, période et mode de règlement.
                        </p>

                    </div>

                </div>

            </div>


            <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">


                {{-- ================================================= --}}
                {{-- MONTANT --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="montant"
                        class="mb-2 flex items-center gap-2 text-sm font-semibold text-gray-700"
                    >

                        <span>Montant (Ar)</span>

                        <span class="text-red-500">*</span>

                    </label>


                    <div class="relative">

                        <input
                            type="number"
                            id="montant"
                            name="montant"
                            value="{{ old('montant') }}"
                            min="1"
                            step="0.01"
                            required
                            placeholder="Ex : 150000"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 pr-14 text-sm text-gray-800 shadow-sm outline-none transition focus:border-green-500 focus:ring-4 focus:ring-green-100"
                        >


                        <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-sm font-bold text-gray-400">
                            Ar
                        </span>

                    </div>


                    @error('montant')

                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- TYPE --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="type_paiement"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >

                        Type de paiement
                        <span class="text-red-500">*</span>

                    </label>


                    <select
                        id="type_paiement"
                        name="type_paiement"
                        required
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >

                        @foreach($typesPaiement as $type)

                            <option
                                value="{{ $type }}"
                                {{ old('type_paiement', $prefill['type_paiement'] ?? 'Droit d\'inscription') === $type ? 'selected' : '' }}
                            >
                                {{ $type }}
                            </option>

                        @endforeach

                    </select>


                    @error('type_paiement')

                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- MOIS --}}
                {{-- ================================================= --}}

                <div id="mois_section">

                    <label
                        for="mois"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >

                        Mois de l'écolage

                        <span
                            id="mois_required"
                            class="text-red-500"
                        >
                            *
                        </span>

                    </label>


                    <select
                        id="mois"
                        name="mois"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >

                        <option value="">
                            -- Sélectionner le mois --
                        </option>


                        @foreach($moisDisponibles as $numero => $nom)

                            <option
                                value="{{ $numero }}"
                                {{ old('mois', $prefill['mois'] ?? '') == $numero ? 'selected' : '' }}
                            >
                                {{ $nom }}
                            </option>

                        @endforeach

                    </select>


                    <p class="mt-2 text-xs text-gray-500">
                        Obligatoire uniquement pour « Écolage mensuel ».
                    </p>


                    @error('mois')

                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- ANNÉE --}}
                {{-- ================================================= --}}

                <div id="annee_section">

                    <label
                        for="annee"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >

                        Année du mois payé

                        <span
                            id="annee_required"
                            class="text-red-500"
                        >
                            *
                        </span>

                    </label>


                    <input
                        type="number"
                        id="annee"
                        name="annee"
                        value="{{ old('annee', $prefill['annee'] ?? date('Y')) }}"
                        min="2000"
                        max="2100"
                        placeholder="Ex : 2026"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >


                    <p class="mt-2 text-xs text-gray-500">
                        Exemple : septembre 2026 → mois = Septembre, année = 2026.
                    </p>


                    @error('annee')

                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- MODE --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="mode_paiement"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >

                        Mode de paiement
                        <span class="text-red-500">*</span>

                    </label>


                    <select
                        id="mode_paiement"
                        name="mode_paiement"
                        required
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 shadow-sm outline-none transition focus:border-green-500 focus:ring-4 focus:ring-green-100"
                    >

                        @foreach($modesPaiement as $mode)

                            <option
                                value="{{ $mode }}"
                                {{ old('mode_paiement', 'Espèces') === $mode ? 'selected' : '' }}
                            >
                                {{ $mode }}
                            </option>

                        @endforeach

                    </select>


                    @error('mode_paiement')

                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- DATE --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="date_paiement"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >

                        Date du paiement
                        <span class="text-red-500">*</span>

                    </label>


                    <input
                        type="date"
                        id="date_paiement"
                        name="date_paiement"
                        value="{{ old('date_paiement', date('Y-m-d')) }}"
                        required
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 shadow-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >


                    @error('date_paiement')

                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- RÉFÉRENCE --}}
                {{-- ================================================= --}}

                <div>

                    <label
                        for="reference"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >
                        Référence
                    </label>


                    <input
                        type="text"
                        id="reference"
                        name="reference"
                        value="{{ old('reference') }}"
                        placeholder="Ex : MV-2026-001"
                        class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >


                    @error('reference')

                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                {{-- ================================================= --}}
                {{-- DESCRIPTION --}}
                {{-- ================================================= --}}

                <div class="md:col-span-2">

                    <label
                        for="description"
                        class="mb-2 block text-sm font-semibold text-gray-700"
                    >
                        Description
                    </label>


                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="Informations complémentaires sur le paiement..."
                        class="w-full resize-y rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >{{ old('description') }}</textarea>


                    @error('description')

                        <p class="mt-2 text-xs font-medium text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="rounded-2xl border border-green-200 bg-green-50 p-5">

            <div class="flex items-start gap-4">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-600 text-white">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle
                            cx="12"
                            cy="12"
                            r="9"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 10v6"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 7h.01"
                        />

                    </svg>

                </div>

                <div>

                    <h3 class="font-bold text-green-950">
                        Suivi des paiements
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-green-900">

                        Pour un écolage mensuel, le mois et l'année permettent
                        de suivre séparément chaque période. Plusieurs paiements
                        peuvent être enregistrés pour un même mois afin de gérer
                        les paiements partiels.

                    </p>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- BOUTONS --}}
        {{-- ========================================================= --}}

        <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">

            <a
                href="{{ route('paiements.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-6 py-3 text-sm font-bold text-gray-700 shadow-sm transition hover:border-gray-400 hover:bg-gray-50"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M18 6L6 18M6 6l12 12"
                    />
                </svg>

                Annuler

            </a>


            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-600 px-7 py-3 text-sm font-bold text-white shadow-lg shadow-green-600/20 transition hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-200"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M5 12l4 4L19 6"
                    />
                </svg>

                Enregistrer le paiement

            </button>

        </div>

    </form>

</div>


{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const beneficiaireType =
        document.getElementById('beneficiaire_type');

    const studentSection =
        document.getElementById('student_section');

    const teacherSection =
        document.getElementById('teacher_section');

    const studentSelect =
        document.getElementById('student_id');

    const teacherSelect =
        document.getElementById('teacher_id');

    const studentSearch =
        document.getElementById('student_search');

    const clearStudentSearch =
        document.getElementById('clear_student_search');

    const studentSearchResult =
        document.getElementById('student_search_result');

    const typePaiement =
        document.getElementById('type_paiement');

    const mois =
        document.getElementById('mois');

    const annee =
        document.getElementById('annee');


    if (
        !beneficiaireType ||
        !studentSection ||
        !teacherSection
    ) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Bénéficiaire
    |--------------------------------------------------------------------------
    */

    function updateBeneficiaire() {

        const type =
            beneficiaireType.value;


        if (type === 'teacher') {

            studentSection.classList.add('hidden');

            teacherSection.classList.remove('hidden');

            studentSelect.required = false;

            teacherSelect.required = true;

            studentSelect.value = '';

        } else {

            studentSection.classList.remove('hidden');

            teacherSection.classList.add('hidden');

            studentSelect.required = true;

            teacherSelect.required = false;

            teacherSelect.value = '';

        }

    }


    beneficiaireType.addEventListener(
        'change',
        updateBeneficiaire
    );


    /*
    |--------------------------------------------------------------------------
    | Type de paiement
    |--------------------------------------------------------------------------
    */

    function updateTypePaiement() {

        const type =
            typePaiement?.value || '';


        const normalizedType =
            type.trim().toLowerCase();


        const isEcolage =
            normalizedType === 'écolage mensuel'
            ||
            normalizedType === 'ecolage mensuel';


        if (mois) {
            mois.required = isEcolage;
        }

        if (annee) {
            annee.required = isEcolage;
        }


        const moisRequired =
            document.getElementById('mois_required');

        const anneeRequired =
            document.getElementById('annee_required');


        if (moisRequired) {

            moisRequired.classList.toggle(
                'hidden',
                !isEcolage
            );

        }


        if (anneeRequired) {

            anneeRequired.classList.toggle(
                'hidden',
                !isEcolage
            );

        }


        /*
         * Important :
         * On ne vide pas automatiquement le mois et l'année
         * lorsqu'on arrive depuis le suivi des écolages.
         *
         * Cela permet de conserver le mois prérempli.
         */

    }


    if (typePaiement) {

        typePaiement.addEventListener(
            'change',
            updateTypePaiement
        );

        updateTypePaiement();

    }


    /*
    |--------------------------------------------------------------------------
    | Recherche élève
    |--------------------------------------------------------------------------
    */

    if (
        studentSearch &&
        studentSelect &&
        clearStudentSearch &&
        studentSearchResult
    ) {

        const options =
            Array.from(
                studentSelect.querySelectorAll('option')
            ).filter(function (option) {

                return option.value !== '';

            });


        const initialCount =
            options.length;


        function filterStudents() {

            const search =
                studentSearch.value
                    .trim()
                    .toLowerCase();


            let visibleCount = 0;


            options.forEach(function (option) {

                const text =
                    (
                        option.dataset.search
                        ||
                        option.textContent
                    ).toLowerCase();


                const matches =
                    search === ''
                    ||
                    text.includes(search);


                option.hidden =
                    !matches;


                if (matches) {
                    visibleCount++;
                }

            });


            if (search === '') {

                studentSearchResult.textContent =
                    initialCount +
                    ' élève(s) disponible(s)';


                clearStudentSearch.classList.add(
                    'hidden'
                );

                clearStudentSearch.classList.remove(
                    'flex'
                );

            } else {

                studentSearchResult.textContent =
                    visibleCount +
                    ' élève(s) trouvé(s)';


                clearStudentSearch.classList.remove(
                    'hidden'
                );

                clearStudentSearch.classList.add(
                    'flex'
                );

            }


            const selected =
                studentSelect.options[
                    studentSelect.selectedIndex
                ];


            if (
                selected &&
                selected.value !== '' &&
                selected.hidden
            ) {

                studentSelect.value = '';

            }

        }


        studentSearch.addEventListener(
            'input',
            filterStudents
        );


        clearStudentSearch.addEventListener(
            'click',
            function () {

                studentSearch.value = '';

                filterStudents();

                studentSearch.focus();

            }
        );


        studentSearch.addEventListener(
            'keydown',
            function (event) {

                if (event.key === 'Escape') {

                    studentSearch.value = '';

                    filterStudents();

                    studentSearch.blur();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Initialisation
    |--------------------------------------------------------------------------
    */

    updateBeneficiaire();

    updateTypePaiement();

});

</script>

@endpush

@endsection