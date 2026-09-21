@extends('layouts.gestion-ecole')

@section('title', 'Modifier étudiant')

@section('page-title', 'Modifier un étudiant')

@section('page-description', 'Modifiez les informations personnelles et scolaires de l’étudiant.')

@section('content')

<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

    <div class="flex items-center gap-3">

        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg"
                 width="24"
                 height="24"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">
                <path d="M12 20h9"/>
                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                <path d="m15 5 3 3"/>
            </svg>
        </div>

        <div>
            <h2 class="text-xl font-bold text-gray-900">
                Modifier l'étudiant
            </h2>

            <p class="mt-0.5 text-sm text-gray-500">
                {{ $student->nom }} {{ $student->prenom }}

                @if($student->matricule)
                    <span class="text-gray-400">—</span>
                    {{ $student->matricule }}
                @endif
            </p>
        </div>

    </div>

    <a href="{{ route('students.show', $student) }}"
       class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700">

        <svg xmlns="http://www.w3.org/2000/svg"
             width="18"
             height="18"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round">
            <path d="m12 19-7-7 7-7"/>
            <path d="M19 12H5"/>
        </svg>

        Retour au profil

    </a>

</div>


@if ($errors->any())

<div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4">

    <div class="flex items-start gap-3">

        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600">

            <svg xmlns="http://www.w3.org/2000/svg"
                 width="19"
                 height="19"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" x2="12" y1="8" y2="12"/>
                <line x1="12" x2="12.01" y1="16" y2="16"/>
            </svg>

        </div>

        <div>

            <p class="font-semibold text-red-800">
                Veuillez corriger les erreurs suivantes :
            </p>

            <ul class="mt-1.5 space-y-1 text-sm text-red-700">

                @foreach ($errors->all() as $error)

                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-red-500"></span>
                        <span>{{ $error }}</span>
                    </li>

                @endforeach

            </ul>

        </div>

    </div>

</div>

@endif


<form action="{{ route('students.update', $student) }}" method="POST">

    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">

        {{-- ================================================= --}}
        {{-- COLONNE PRINCIPALE --}}
        {{-- ================================================= --}}

        <div class="space-y-5 xl:col-span-2">

            {{-- INFORMATIONS PERSONNELLES --}}

            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">

                <div class="border-b border-gray-100 px-5 py-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="20"
                                 height="20"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>

                        </div>

                        <div>
                            <h3 class="font-bold text-gray-900">
                                Informations personnelles
                            </h3>

                            <p class="text-xs text-gray-500">
                                Identité et informations personnelles
                            </p>
                        </div>

                    </div>

                </div>


                <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">

                    {{-- NOM --}}
                    <div>

                        <label for="nom"
                               class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Nom <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="17"
                                     height="17"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="M20 21a8 8 0 0 0-16 0"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>

                            </div>

                            <input
                                id="nom"
                                type="text"
                                name="nom"
                                value="{{ old('nom', $student->nom) }}"
                                required
                                maxlength="255"
                                placeholder="Nom de famille"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                            >

                        </div>

                    </div>


                    {{-- PRÉNOM --}}
                    <div>

                        <label for="prenom"
                               class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Prénom <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="17"
                                     height="17"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="M20 21a8 8 0 0 0-16 0"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>

                            </div>

                            <input
                                id="prenom"
                                type="text"
                                name="prenom"
                                value="{{ old('prenom', $student->prenom) }}"
                                required
                                maxlength="255"
                                placeholder="Prénom"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                            >

                        </div>

                    </div>


                    {{-- MATRICULE --}}
                    <div>

                        <label for="matricule"
                               class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Matricule
                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="17"
                                     height="17"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <rect width="18" height="18" x="3" y="3" rx="2"/>
                                    <path d="M7 7h10"/>
                                    <path d="M7 11h10"/>
                                    <path d="M7 15h6"/>
                                </svg>

                            </div>

                            <input
                                id="matricule"
                                type="text"
                                name="matricule"
                                value="{{ old('matricule', $student->matricule) }}"
                                maxlength="100"
                                placeholder="Ex : STU-2026-001"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                            >

                        </div>

                    </div>


                    {{-- SEXE --}}
                    <div>

                        <label for="sexe"
                               class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Sexe <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0 z-10 flex items-center pl-3 text-gray-400">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="17"
                                     height="17"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="5"/>
                                    <path d="M20 21a8 8 0 0 0-16 0"/>
                                </svg>

                            </div>

                            <select
                                id="sexe"
                                name="sexe"
                                required
                                class="w-full appearance-none rounded-lg border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                            >

                                <option value="">
                                    Sélectionner le sexe
                                </option>

                                <option value="Masculin"
                                    {{ old('sexe', $student->sexe) === 'Masculin' ? 'selected' : '' }}>
                                    Masculin
                                </option>

                                <option value="Féminin"
                                    {{ old('sexe', $student->sexe) === 'Féminin' ? 'selected' : '' }}>
                                    Féminin
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- DATE DE NAISSANCE --}}
                    <div>

                        <label for="date_naissance"
                               class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Date de naissance <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="17"
                                     height="17"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <rect width="18" height="18" x="3" y="4" rx="2"/>
                                    <line x1="16" x2="16" y1="2" y2="6"/>
                                    <line x1="8" x2="8" y1="2" y2="6"/>
                                    <line x1="3" x2="21" y1="10" y2="10"/>
                                </svg>

                            </div>

                            <input
                                id="date_naissance"
                                type="date"
                                name="date_naissance"
                                value="{{ old('date_naissance', $student->date_naissance ? \Carbon\Carbon::parse($student->date_naissance)->format('Y-m-d') : '') }}"
                                required
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                            >

                        </div>

                    </div>


                    {{-- LIEU DE NAISSANCE --}}
                    <div>

                        <label for="lieu_naissance"
                               class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Lieu de naissance
                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="17"
                                     height="17"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>

                            </div>

                            <input
                                id="lieu_naissance"
                                type="text"
                                name="lieu_naissance"
                                value="{{ old('lieu_naissance', $student->lieu_naissance) }}"
                                maxlength="255"
                                placeholder="Ex : Mahajanga"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                            >

                        </div>

                    </div>

                </div>

            </div>


            {{-- COORDONNÉES --}}

            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">

                <div class="border-b border-gray-100 px-5 py-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="20"
                                 height="20"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
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

                </div>


                <div class="grid grid-cols-1 gap-4 p-5 md:grid-cols-2">

                    {{-- TÉLÉPHONE --}}
                    <div>

                        <label for="telephone"
                               class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Téléphone
                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="17"
                                     height="17"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
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

                            <input
                                id="telephone"
                                type="text"
                                name="telephone"
                                value="{{ old('telephone', $student->telephone) }}"
                                maxlength="30"
                                placeholder="Ex : 034 12 345 67"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                            >

                        </div>

                    </div>


                    {{-- EMAIL --}}
                    <div>

                        <label for="email"
                               class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Adresse e-mail
                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="17"
                                     height="17"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <rect width="20" height="16" x="2" y="4" rx="2"/>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                </svg>

                            </div>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $student->email) }}"
                                maxlength="255"
                                placeholder="Ex : etudiant@email.com"
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                            >

                        </div>

                    </div>


                    {{-- ADRESSE --}}
                    <div class="md:col-span-2">

                        <label for="adresse"
                               class="mb-1.5 block text-sm font-semibold text-gray-700">
                            Adresse
                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute left-0 top-0 flex items-center pl-3 pt-3 text-gray-400">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="17"
                                     height="17"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>

                            </div>

                            <textarea
                                id="adresse"
                                name="adresse"
                                rows="2"
                                maxlength="255"
                                placeholder="Adresse complète..."
                                class="w-full resize-none rounded-lg border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                            >{{ old('adresse', $student->adresse) }}</textarea>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- COLONNE DROITE --}}
        {{-- ================================================= --}}

        <div class="space-y-5">

            {{-- SCOLARITÉ --}}

            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">

                <div class="border-b border-gray-100 px-5 py-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="20"
                                 height="20"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path d="M22 10v6"/>
                                <path d="m2 10 10-5 10 5-10 5L2 10Z"/>
                                <path d="M6 12v5c3 2 9 2 12 0v-5"/>
                            </svg>

                        </div>

                        <div>
                            <h3 class="font-bold text-gray-900">
                                Scolarité
                            </h3>

                            <p class="text-xs text-gray-500">
                                Affectation de l'étudiant
                            </p>
                        </div>

                    </div>

                </div>


                <div class="p-5">

                    <label for="classe"
                           class="mb-1.5 block text-sm font-semibold text-gray-700">
                        Classe
                    </label>

                    <div class="relative">

                        <div class="pointer-events-none absolute inset-y-0 left-0 z-10 flex items-center pl-3 text-gray-400">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="17"
                                 height="17"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path d="M22 10v6"/>
                                <path d="m2 10 10-5 10 5-10 5L2 10Z"/>
                                <path d="M6 12v5c3 2 9 2 12 0v-5"/>
                            </svg>

                        </div>

                        <select
                            id="classe"
                            name="classe"
                            class="w-full appearance-none rounded-lg border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                        >

                            <option value="">
                                -- Aucune classe --
                            </option>

                            @foreach($classes as $class)

                                <option
                                    value="{{ $class->nom_classe }}"
                                    {{ old('classe', $student->classe) === $class->nom_classe ? 'selected' : '' }}
                                >
                                    {{ $class->nom_classe }}

                                    @if($class->niveau)
                                        — {{ $class->niveau }}
                                    @endif
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="mt-4 rounded-lg border border-emerald-100 bg-emerald-50 p-3">

                        <div class="flex items-start gap-2.5">

                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white text-emerald-600">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="17"
                                     height="17"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M12 16v-4"/>
                                    <path d="M12 8h.01"/>
                                </svg>

                            </div>

                            <div>
                                <p class="text-sm font-semibold text-emerald-800">
                                    Classe actuelle
                                </p>

                                <p class="mt-0.5 text-xs text-emerald-700">
                                    {{ $student->classe ?: 'Aucune classe affectée' }}
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- APERÇU --}}

            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-100">

                <div class="border-b border-gray-100 px-5 py-4">

                    <h3 class="font-bold text-gray-900">
                        Aperçu
                    </h3>

                    <p class="mt-0.5 text-xs text-gray-500">
                        Profil de l'étudiant
                    </p>

                </div>


                <div class="p-5">

                    <div class="flex items-center gap-3">

                        {{-- AVATAR SANS GRADIENT --}}
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-xl font-bold text-white shadow-sm">

                            {{ strtoupper(substr($student->prenom ?? $student->nom ?? 'E', 0, 1)) }}

                        </div>


                        <div class="min-w-0">

                            <h4 class="truncate font-bold text-gray-900">
                                {{ $student->nom }} {{ $student->prenom }}
                            </h4>

                            <p class="mt-0.5 truncate text-xs text-gray-500">
                                {{ $student->matricule ?: 'Matricule non renseigné' }}
                            </p>

                        </div>

                    </div>


                    <div class="mt-4 space-y-2.5">

                        {{-- SEXE --}}
                        <div class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2.5">

                            <div class="flex items-center gap-2 text-gray-500">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="16"
                                     height="16"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="5"/>
                                    <path d="M20 21a8 8 0 0 0-16 0"/>
                                </svg>

                                <span class="text-xs">
                                    Sexe
                                </span>

                            </div>

                            <span class="text-sm font-semibold text-gray-800">
                                {{ $student->sexe ?: '—' }}
                            </span>

                        </div>


                        {{-- CLASSE --}}
                        <div class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2.5">

                            <div class="flex items-center gap-2 text-gray-500">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="16"
                                     height="16"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <path d="M22 10v6"/>
                                    <path d="m2 10 10-5 10 5-10 5L2 10Z"/>
                                    <path d="M6 12v5c3 2 9 2 12 0v-5"/>
                                </svg>

                                <span class="text-xs">
                                    Classe
                                </span>

                            </div>

                            <span class="max-w-[130px] truncate text-sm font-semibold text-gray-800">
                                {{ $student->classe ?: '—' }}
                            </span>

                        </div>


                        {{-- IDENTIFIANT --}}
                        <div class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-2.5">

                            <div class="flex items-center gap-2 text-gray-500">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="16"
                                     height="16"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M12 16v-4"/>
                                    <path d="M12 8h.01"/>
                                </svg>

                                <span class="text-xs">
                                    Identifiant
                                </span>

                            </div>

                            <span class="text-sm font-semibold text-gray-800">
                                #{{ $student->id }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ACTIONS --}}

            <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">

                <div class="mb-4">

                    <h3 class="font-bold text-gray-900">
                        Actions
                    </h3>

                    <p class="mt-0.5 text-xs text-gray-500">
                        Enregistrer ou annuler les modifications
                    </p>

                </div>


                <button
                    type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/20"
                >

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="18"
                         height="18"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>

                    Enregistrer

                </button>


                <a
                    href="{{ route('students.show', $student) }}"
                    class="mt-2.5 flex w-full items-center justify-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-100"
                >

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="18"
                         height="18"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>

                    Annuler

                </a>

            </div>

        </div>

    </div>

</form>


{{-- INFORMATION --}}

<div class="mt-5 rounded-xl border border-blue-100 bg-blue-50 p-4">

    <div class="flex items-start gap-3">

        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white text-blue-600">

            <svg xmlns="http://www.w3.org/2000/svg"
                 width="19"
                 height="19"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 16v-4"/>
                <path d="M12 8h.01"/>
            </svg>

        </div>

        <div>

            <p class="text-sm font-semibold text-gray-800">
                Modification sécurisée
            </p>

            <p class="mt-0.5 text-xs leading-5 text-gray-600">
                Vérifiez toutes les informations avant de cliquer sur
                <strong>Enregistrer</strong>.
                Les champs marqués d'un
                <span class="font-bold text-red-500">*</span>
                sont obligatoires.
            </p>

        </div>

    </div>

</div>

@endsection