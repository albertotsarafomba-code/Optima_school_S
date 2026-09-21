@extends('layouts.gestion-ecole')

@section('title', 'Détails étudiant')

@section('page-title', 'Détails de l’étudiant')

@section('page-description', 'Consultez les informations personnelles et scolaires de cet étudiant.')

@section('content')

{{-- ========================================================= --}}
{{-- BOUTON RETOUR --}}
{{-- ========================================================= --}}

<div class="mb-6">
    <a
        href="{{ route('students.index') }}"
        class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="18"
            height="18"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <path d="m12 19-7-7 7-7"/>
            <path d="M19 12H5"/>
        </svg>

        Retour aux étudiants
    </a>
</div>


{{-- ========================================================= --}}
{{-- PROFIL PRINCIPAL --}}
{{-- ========================================================= --}}

<div class="mb-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

    {{-- HEADER PROFIL --}}
    <div class="relative overflow-hidden bg-blue-950 px-6 py-8 lg:px-8">

        <div class="absolute right-0 top-0 h-full w-1 bg-green-600"></div>

        <div class="relative flex flex-col gap-6 md:flex-row md:items-center md:justify-between">

            {{-- INFORMATIONS ÉTUDIANT --}}
            <div class="flex items-center gap-5">

                {{-- AVATAR --}}
                <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-2xl border-4 border-white/30 bg-white text-3xl font-bold text-blue-700 shadow-lg">
                    {{ strtoupper(substr($student->prenom ?? $student->nom ?? 'E', 0, 1)) }}
                </div>

                <div class="min-w-0 text-white">

                    <div class="mb-1 flex flex-wrap items-center gap-2">

                        <h2 class="text-2xl font-bold lg:text-3xl">
                            {{ $student->nom }} {{ $student->prenom }}
                        </h2>

                        @if($student->sexe)
                            <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-semibold">
                                {{ $student->sexe }}
                            </span>
                        @endif

                    </div>

                    <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-blue-100">

                        {{-- Matricule --}}
                        <span class="inline-flex items-center gap-2">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="17"
                                height="17"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M19 8v6"/>
                                <path d="M22 11h-6"/>
                            </svg>

                            Matricule :

                            <strong>
                                {{ $student->matricule ?: 'Non renseigné' }}
                            </strong>

                        </span>


                        {{-- Classe --}}
                        <span class="inline-flex items-center gap-2">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="17"
                                height="17"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M22 10v6M2 10l10-5 10 5-10 5L2 10Z"/>
                                <path d="M6 12v5c3 2 9 2 12 0v-5"/>
                            </svg>

                            Classe :

                            <strong>
                                {{ $student->classe ?: 'Non affectée' }}
                            </strong>

                        </span>

                    </div>

                </div>

            </div>


            {{-- BOUTONS --}}
            <div class="flex flex-wrap gap-3">

                {{-- Modifier --}}
                <a
                    href="{{ route('students.edit', $student) }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-blue-700 shadow-sm transition hover:bg-blue-50"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                    </svg>

                    Modifier

                </a>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- RÉSUMÉ --}}
    {{-- ========================================================= --}}

    <div class="grid grid-cols-1 border-t border-gray-100 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Classe --}}
        <div class="flex items-center gap-4 border-b border-gray-100 p-5 lg:border-b-0 lg:border-r">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="21"
                    height="21"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M22 10v6M2 10l10-5 10 5-10 5L2 10Z"/>
                    <path d="M6 12v5c3 2 9 2 12 0v-5"/>
                </svg>

            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Classe
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ $student->classe ?: 'Non affectée' }}
                </p>
            </div>

        </div>


        {{-- Sexe --}}
        <div class="flex items-center gap-4 border-b border-gray-100 p-5 lg:border-b-0 lg:border-r">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="21"
                    height="21"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <circle cx="12" cy="8" r="5"/>
                    <path d="M20 21a8 8 0 0 0-16 0"/>
                </svg>

            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Sexe
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ $student->sexe ?: 'Non renseigné' }}
                </p>
            </div>

        </div>


        {{-- Date naissance --}}
        <div class="flex items-center gap-4 border-b border-gray-100 p-5 sm:border-b-0 lg:border-r">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-violet-50 text-violet-600">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="21"
                    height="21"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <rect width="18" height="18" x="3" y="4" rx="2"/>
                    <line x1="16" x2="16" y1="2" y2="6"/>
                    <line x1="8" x2="8" y1="2" y2="6"/>
                    <line x1="3" x2="21" y1="10" y2="10"/>
                </svg>

            </div>

            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Date de naissance
                </p>

                <p class="mt-1 font-semibold text-gray-900">

                    @if($student->date_naissance)

                        {{ \Carbon\Carbon::parse($student->date_naissance)->format('d/m/Y') }}

                    @else

                        Non renseignée

                    @endif

                </p>

            </div>

        </div>


        {{-- Matricule --}}
        <div class="flex items-center gap-4 p-5">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="21"
                    height="21"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                    <path d="M7 7h10"/>
                    <path d="M7 11h10"/>
                    <path d="M7 15h6"/>
                </svg>

            </div>

            <div>

                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Matricule
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ $student->matricule ?: 'Non renseigné' }}
                </p>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- INFORMATIONS --}}
{{-- ========================================================= --}}

<div class="grid grid-cols-1 gap-6 lg:grid-cols-2">


    {{-- ===================================================== --}}
    {{-- INFORMATIONS PERSONNELLES --}}
    {{-- ===================================================== --}}

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

        {{-- Header --}}
        <div class="flex items-center gap-3 border-b border-gray-100 px-6 py-5">

            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>

            </div>

            <div>

                <h3 class="font-bold text-gray-900">
                    Informations personnelles
                </h3>

                <p class="text-xs text-gray-500">
                    Données personnelles de l’étudiant
                </p>

            </div>

        </div>


        {{-- Contenu --}}
        <div class="divide-y divide-gray-100">

            {{-- Nom --}}
            <div class="flex items-center justify-between gap-4 px-6 py-4">

                <div class="flex items-center gap-3">

                    <div class="text-gray-400">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="19"
                            height="19"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M20 21a8 8 0 0 0-16 0"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>

                    </div>

                    <span class="text-sm text-gray-500">
                        Nom complet
                    </span>

                </div>

                <span class="text-right text-sm font-semibold text-gray-900">
                    {{ $student->nom }} {{ $student->prenom }}
                </span>

            </div>


            {{-- Date naissance --}}
            <div class="flex items-center justify-between gap-4 px-6 py-4">

                <div class="flex items-center gap-3">

                    <div class="text-gray-400">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="19"
                            height="19"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <rect width="18" height="18" x="3" y="4" rx="2"/>
                            <line x1="16" x2="16" y1="2" y2="6"/>
                            <line x1="8" x2="8" y1="2" y2="6"/>
                            <line x1="3" x2="21" y1="10" y2="10"/>
                        </svg>

                    </div>

                    <span class="text-sm text-gray-500">
                        Date de naissance
                    </span>

                </div>

                <span class="text-right text-sm font-semibold text-gray-900">

                    @if($student->date_naissance)
                        {{ \Carbon\Carbon::parse($student->date_naissance)->format('d/m/Y') }}
                    @else
                        Non renseignée
                    @endif

                </span>

            </div>


            {{-- Lieu naissance --}}
            <div class="flex items-center justify-between gap-4 px-6 py-4">

                <div class="flex items-center gap-3">

                    <div class="text-gray-400">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="19"
                            height="19"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>

                    </div>

                    <span class="text-sm text-gray-500">
                        Lieu de naissance
                    </span>

                </div>

                <span class="text-right text-sm font-semibold text-gray-900">
                    {{ $student->lieu_naissance ?: 'Non renseigné' }}
                </span>

            </div>


            {{-- Sexe --}}
            <div class="flex items-center justify-between gap-4 px-6 py-4">

                <div class="flex items-center gap-3">

                    <div class="text-gray-400">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="19"
                            height="19"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="12" cy="8" r="5"/>
                            <path d="M20 21a8 8 0 0 0-16 0"/>
                        </svg>

                    </div>

                    <span class="text-sm text-gray-500">
                        Sexe
                    </span>

                </div>

                <span class="text-right text-sm font-semibold text-gray-900">
                    {{ $student->sexe ?: 'Non renseigné' }}
                </span>

            </div>


            {{-- Matricule --}}
            <div class="flex items-center justify-between gap-4 px-6 py-4">

                <div class="flex items-center gap-3">

                    <div class="text-gray-400">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="19"
                            height="19"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <rect width="18" height="18" x="3" y="3" rx="2"/>
                            <path d="M7 7h10"/>
                            <path d="M7 11h10"/>
                            <path d="M7 15h6"/>
                        </svg>

                    </div>

                    <span class="text-sm text-gray-500">
                        Matricule
                    </span>

                </div>

                <span class="text-right text-sm font-semibold text-gray-900">
                    {{ $student->matricule ?: 'Non renseigné' }}
                </span>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- INFORMATIONS SCOLAIRES --}}
    {{-- ===================================================== --}}

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

        {{-- Header --}}
        <div class="flex items-center gap-3 border-b border-gray-100 px-6 py-5">

            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M22 10v6M2 10l10-5 10 5-10 5L2 10Z"/>
                    <path d="M6 12v5c3 2 9 2 12 0v-5"/>
                </svg>

            </div>

            <div>

                <h3 class="font-bold text-gray-900">
                    Informations scolaires
                </h3>

                <p class="text-xs text-gray-500">
                    Situation scolaire actuelle
                </p>

            </div>

        </div>


        {{-- Contenu --}}
        <div class="divide-y divide-gray-100">

            {{-- Classe --}}
            <div class="flex items-center justify-between gap-4 px-6 py-5">

                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M22 10v6M2 10l10-5 10 5-10 5L2 10Z"/>
                            <path d="M6 12v5c3 2 9 2 12 0v-5"/>
                        </svg>

                    </div>

                    <div>

                        <p class="text-xs text-gray-500">
                            Classe
                        </p>

                        <p class="font-semibold text-gray-900">
                            {{ $student->classe ?: 'Non affectée' }}
                        </p>

                    </div>

                </div>

                @if($student->classe)

                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                        Affecté
                    </span>

                @else

                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                        À affecter
                    </span>

                @endif

            </div>


            {{-- Établissement --}}
            <div class="flex items-center justify-between gap-4 px-6 py-5">

                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M3 21h18"/>
                            <path d="M5 21V7l8-4 6 4v14"/>
                            <path d="M9 21v-4h6v4"/>
                            <path d="M9 9h.01"/>
                            <path d="M13 9h.01"/>
                            <path d="M17 9h.01"/>
                            <path d="M9 13h.01"/>
                            <path d="M13 13h.01"/>
                            <path d="M17 13h.01"/>
                        </svg>

                    </div>

                    <div>

                        <p class="text-xs text-gray-500">
                            Établissement
                        </p>

                        <p class="font-semibold text-gray-900">
                            Établissement sélectionné
                        </p>

                    </div>

                </div>

                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                    Actif
                </span>

            </div>


            {{-- ID étudiant --}}
            <div class="flex items-center justify-between gap-4 px-6 py-5">

                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-50 text-violet-600">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 16v-4"/>
                            <path d="M12 8h.01"/>
                        </svg>

                    </div>

                    <div>

                        <p class="text-xs text-gray-500">
                            Identifiant système
                        </p>

                        <p class="font-semibold text-gray-900">
                            Étudiant #{{ $student->id }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- CONTACT --}}
    {{-- ===================================================== --}}

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

        <div class="flex items-center gap-3 border-b border-gray-100 px-6 py-5">

            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-50 text-cyan-600">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 1 1 18 0Z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>

            </div>

            <div>

                <h3 class="font-bold text-gray-900">
                    Coordonnées
                </h3>

                <p class="text-xs text-gray-500">
                    Informations de contact
                </p>

            </div>

        </div>


        <div class="divide-y divide-gray-100">

            {{-- Téléphone --}}
            <div class="flex items-center gap-4 px-6 py-5">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 text-green-600">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2
                                 19.79 19.79 0 0 1-8.63-3.07
                                 19.5 19.5 0 0 1-6-6
                                 19.79 19.79 0 0 1-3.07-8.67
                                 A2 2 0 0 1 4.11 2h3
                                 a2 2 0 0 1 2 1.72
                                 12.84 12.84 0 0 0 .7 2.81
                                 2 2 0 0 1-.45 2.11L8.09 9.91
                                 a16 16 0 0 0 6 6l1.27-1.27
                                 a2 2 0 0 1 2.11-.45
                                 12.84 12.84 0 0 0 2.81.7
                                 A2 2 0 0 1 22 16.92z"/>
                    </svg>

                </div>

                <div class="min-w-0">

                    <p class="text-xs text-gray-500">
                        Téléphone
                    </p>

                    @if($student->telephone)

                        <a
                            href="tel:{{ $student->telephone }}"
                            class="break-all font-semibold text-gray-900 hover:text-blue-600"
                        >
                            {{ $student->telephone }}
                        </a>

                    @else

                        <p class="font-semibold text-gray-400">
                            Non renseigné
                        </p>

                    @endif

                </div>

            </div>


            {{-- Email --}}
            <div class="flex items-center gap-4 px-6 py-5">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <rect width="20" height="16" x="2" y="4" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>

                </div>

                <div class="min-w-0">

                    <p class="text-xs text-gray-500">
                        Adresse e-mail
                    </p>

                    @if($student->email)

                        <a
                            href="mailto:{{ $student->email }}"
                            class="break-all font-semibold text-gray-900 hover:text-blue-600"
                        >
                            {{ $student->email }}
                        </a>

                    @else

                        <p class="font-semibold text-gray-400">
                            Non renseignée
                        </p>

                    @endif

                </div>

            </div>


            {{-- Adresse --}}
            <div class="flex items-start gap-4 px-6 py-5">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>

                </div>

                <div class="min-w-0">

                    <p class="text-xs text-gray-500">
                        Adresse
                    </p>

                    <p class="break-words font-semibold text-gray-900">
                        {{ $student->adresse ?: 'Non renseignée' }}
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- ===================================================== --}}
    {{-- ACTIONS --}}
    {{-- ===================================================== --}}

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">

        <div class="border-b border-gray-100 px-6 py-5">

            <h3 class="font-bold text-gray-900">
                Actions
            </h3>

            <p class="mt-1 text-xs text-gray-500">
                Gestion de la fiche étudiant
            </p>

        </div>


        <div class="space-y-3 p-6">

            {{-- Modifier --}}
            <a
                href="{{ route('students.edit', $student) }}"
                class="flex w-full items-center justify-between rounded-xl border border-blue-100 bg-blue-50 px-4 py-4 text-blue-700 transition hover:border-blue-200 hover:bg-blue-100"
            >

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600 text-white">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="19"
                            height="19"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M12 20h9"/>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                        </svg>

                    </div>

                    <div class="text-left">

                        <p class="font-semibold">
                            Modifier l'étudiant
                        </p>

                        <p class="text-xs text-blue-600">
                            Modifier les informations
                        </p>

                    </div>

                </div>

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="19"
                    height="19"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="m9 18 6-6-6-6"/>
                </svg>

            </a>


            {{-- Retour --}}
            <a
                href="{{ route('students.index') }}"
                class="flex w-full items-center justify-between rounded-xl border border-gray-200 bg-gray-50 px-4 py-4 text-gray-700 transition hover:border-gray-300 hover:bg-gray-100"
            >

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-200 text-gray-600">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="19"
                            height="19"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="m12 19-7-7 7-7"/>
                            <path d="M19 12H5"/>
                        </svg>

                    </div>

                    <div class="text-left">

                        <p class="font-semibold">
                            Retour à la liste
                        </p>

                        <p class="text-xs text-gray-500">
                            Voir tous les étudiants
                        </p>

                    </div>

                </div>

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="19"
                    height="19"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="m9 18 6-6-6-6"/>
                </svg>

            </a>


            {{-- Supprimer --}}
            <form
                action="{{ route('students.destroy', $student) }}"
                method="POST"
                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ? Cette action est irréversible.');"
            >

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="flex w-full items-center justify-between rounded-xl border border-red-100 bg-red-50 px-4 py-4 text-red-700 transition hover:border-red-200 hover:bg-red-100"
                >

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-600 text-white">

                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="19"
                                height="19"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M3 6h18"/>
                                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                                <path d="M10 11v6"/>
                                <path d="M14 11v6"/>
                            </svg>

                        </div>

                        <div class="text-left">

                            <p class="font-semibold">
                                Supprimer l'étudiant
                            </p>

                            <p class="text-xs text-red-600">
                                Cette action est irréversible
                            </p>

                        </div>

                    </div>

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="19"
                        height="19"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="m9 18 6-6-6-6"/>
                    </svg>

                </button>

            </form>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- INFORMATIONS SYSTÈME --}}
{{-- ========================================================= --}}

<div class="mt-6 rounded-2xl border border-blue-100 bg-blue-50 p-5">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-3">

            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 16v-4"/>
                    <path d="M12 8h.01"/>
                </svg>

            </div>

            <div>

                <p class="text-sm font-semibold text-gray-800">
                    Fiche étudiant
                </p>

                <p class="text-xs text-gray-500">
                    Dernière mise à jour :
                    {{ $student->updated_at ? $student->updated_at->format('d/m/Y à H:i') : 'Non disponible' }}
                </p>

            </div>

        </div>


        <div class="text-xs text-gray-500">

            ID :
            <span class="font-semibold text-gray-700">
                #{{ $student->id }}
            </span>

        </div>

    </div>

</div>

@endsection