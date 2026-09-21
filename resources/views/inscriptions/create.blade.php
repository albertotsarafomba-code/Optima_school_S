@extends('layouts.gestion-ecole')

@section('title', 'Nouvelle inscription')

@section('content')

<div class="min-h-screen bg-slate-50 py-8">

    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- En-tête --}}
        <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-700 text-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3M15 6.5A3.5 3.5 0 1 1 8 6.5a3.5 3.5 0 0 1 7 0Z"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M3 21a6 6 0 0 1 12 0"/>
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">
                            Nouvelle inscription
                        </h1>

                        <p class="mt-1 text-sm text-slate-500">
                            Enregistrer un étudiant dans l'établissement sélectionné.
                        </p>
                    </div>
                </div>
            </div>

            <a href="{{ route('inscriptions.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M10.5 19.5 3 12l7.5-7.5"/>
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M3 12h18"/>
                </svg>

                Retour aux inscriptions
            </a>

        </div>

        {{-- Messages d'erreur --}}
        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

                <div class="flex gap-3">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="mt-0.5 h-5 w-5 flex-shrink-0 text-red-600"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <circle cx="12"
                                cy="12"
                                r="9"/>
                        <path stroke-linecap="round"
                              d="M12 8v4"/>
                        <path stroke-linecap="round"
                              d="M12 16h.01"/>
                    </svg>

                    <div>
                        <h3 class="font-semibold text-red-800">
                            Vérifiez les informations saisies.
                        </h3>

                        <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        @endif

        {{-- Établissement --}}
        <div class="mb-6 rounded-xl border border-blue-100 bg-white shadow-sm">

            <div class="border-b border-slate-200 bg-blue-50 px-6 py-4">
                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-700 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M3 21h18"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M9 7h1M14 7h1M9 11h1M14 11h1M9 15h1M14 15h1"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="font-bold text-slate-800">
                            Établissement
                        </h2>

                        <p class="text-xs text-slate-500">
                            Établissement actuellement sélectionné
                        </p>
                    </div>

                </div>
            </div>

            <div class="grid gap-5 px-6 py-5 md:grid-cols-2">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Établissement
                    </label>

                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800">
                        {{ $establishment->nom }}
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Type d'établissement
                    </label>

                    <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800">
                        {{ $schoolTypeName }}
                    </div>
                </div>

            </div>
        </div>

        <form action="{{ route('inscriptions.store') }}"
              method="POST"
              class="space-y-6">

            @csrf

            {{-- Identité étudiant --}}
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-600 text-white">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <circle cx="12"
                                        cy="8"
                                        r="3"/>
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M5 21a7 7 0 0 1 14 0"/>
                            </svg>

                        </div>

                        <div>
                            <h2 class="font-bold text-slate-800">
                                Identité de l'étudiant
                            </h2>

                            <p class="text-xs text-slate-500">
                                Informations personnelles de l'étudiant
                            </p>
                        </div>

                    </div>
                </div>

                <div class="grid gap-5 px-6 py-6 md:grid-cols-2">

                    {{-- Nom --}}
                    <div>
                        <label for="nom"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Nom <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                               id="nom"
                               name="nom"
                               value="{{ old('nom') }}"
                               required
                               autofocus
                               placeholder="Ex. RAKOTO"
                               class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                        @error('nom')
                            <p class="mt-1.5 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Prénom --}}
                    <div>
                        <label for="prenom"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Prénom <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                               id="prenom"
                               name="prenom"
                               value="{{ old('prenom') }}"
                               required
                               placeholder="Ex. Jean"
                               class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                        @error('prenom')
                            <p class="mt-1.5 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Date naissance --}}
                    <div>
                        <label for="date_naissance"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Date de naissance <span class="text-red-500">*</span>
                        </label>

                        <input type="date"
                               id="date_naissance"
                               name="date_naissance"
                               value="{{ old('date_naissance') }}"
                               required
                               class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                        @error('date_naissance')
                            <p class="mt-1.5 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Lieu naissance --}}
                    <div>
                        <label for="lieu_naissance"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Lieu de naissance
                        </label>

                        <input type="text"
                               id="lieu_naissance"
                               name="lieu_naissance"
                               value="{{ old('lieu_naissance') }}"
                               placeholder="Ex. Mahajanga"
                               class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                        @error('lieu_naissance')
                            <p class="mt-1.5 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Sexe --}}
                    <div>
                        <label for="sexe"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Sexe <span class="text-red-500">*</span>
                        </label>

                        <select id="sexe"
                                name="sexe"
                                required
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                            <option value="">
                                Sélectionner le sexe
                            </option>

                            <option value="Masculin"
                                @selected(old('sexe') === 'Masculin')>
                                Masculin
                            </option>

                            <option value="Féminin"
                                @selected(old('sexe') === 'Féminin')>
                                Féminin
                            </option>

                        </select>

                        @error('sexe')
                            <p class="mt-1.5 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Téléphone --}}
                    <div>
                        <label for="telephone"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Téléphone
                        </label>

                        <input type="text"
                               id="telephone"
                               name="telephone"
                               value="{{ old('telephone') }}"
                               placeholder="Ex. 034 00 000 00"
                               class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                        @error('telephone')
                            <p class="mt-1.5 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Adresse e-mail
                        </label>

                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="exemple@email.com"
                               class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                        @error('email')
                            <p class="mt-1.5 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Adresse --}}
                    <div>
                        <label for="adresse"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Adresse
                        </label>

                        <input type="text"
                               id="adresse"
                               name="adresse"
                               value="{{ old('adresse') }}"
                               placeholder="Adresse de résidence"
                               class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                        @error('adresse')
                            <p class="mt-1.5 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Informations scolaires --}}
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-700 text-white">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M3 5.5A2.5 2.5 0 0 1 5.5 3H18a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H5.5A2.5 2.5 0 0 1 3 18.5v-13Z"/>

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M7 8h10M7 12h10M7 16h6"/>
                            </svg>

                        </div>

                        <div>
                            <h2 class="font-bold text-slate-800">
                                Informations scolaires
                            </h2>

                            <p class="text-xs text-slate-500">
                                Année scolaire, classe et situation de l'étudiant
                            </p>
                        </div>

                    </div>
                </div>

                <div class="grid gap-5 px-6 py-6 md:grid-cols-2">

                    {{-- Année scolaire --}}
                    <div>
                        <label for="school_year_id"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Année scolaire <span class="text-red-500">*</span>
                        </label>

                        <select id="school_year_id"
                                name="school_year_id"
                                required
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                            <option value="">
                                Sélectionner une année scolaire
                            </option>

                            @foreach ($schoolYears as $schoolYear)
                                <option value="{{ $schoolYear->id }}"
                                    @selected(
                                        (string) old(
                                            'school_year_id',
                                            $selectedSchoolYearId
                                        ) === (string) $schoolYear->id
                                    )>
                                    {{ $schoolYear->name }}
                                    @if ($schoolYear->is_active)
                                        — Active
                                    @endif
                                </option>
                            @endforeach

                        </select>

                        @error('school_year_id')
                            <p class="mt-1.5 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Classe --}}
                    <div>
                        <label for="class_room_id"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Classe <span class="text-red-500">*</span>
                        </label>

                        <select id="class_room_id"
                                name="class_room_id"
                                required
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                            <option value="">
                                Sélectionner une classe
                            </option>

                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}"
                                    @selected(
                                        (string) old('class_room_id') ===
                                        (string) $class->id
                                    )>
                                    {{ $class->nom_classe }}

                                    @if ($class->niveau)
                                        — {{ $class->niveau }}
                                    @endif
                                </option>
                            @endforeach

                        </select>

                        @error('class_room_id')
                            <p class="mt-1.5 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Situation scolaire --}}
                    <div>
                        <label for="situation_scolaire"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Situation scolaire <span class="text-red-500">*</span>
                        </label>

                        <select id="situation_scolaire"
                                name="situation_scolaire"
                                required
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                            <option value="">
                                Sélectionner une situation
                            </option>

                            <option value="Nouveau"
                                @selected(old('situation_scolaire') === 'Nouveau')>
                                Nouveau
                            </option>

                            <option value="Réinscription"
                                @selected(old('situation_scolaire') === 'Réinscription')>
                                Réinscription
                            </option>

                            <option value="Transfert"
                                @selected(old('situation_scolaire') === 'Transfert')>
                                Transfert
                            </option>

                        </select>

                        @error('situation_scolaire')
                            <p class="mt-1.5 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Date inscription --}}
                    <div>
                        <label for="date_inscription"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Date d'inscription <span class="text-red-500">*</span>
                        </label>

                        <input type="date"
                               id="date_inscription"
                               name="date_inscription"
                               value="{{ old('date_inscription', now()->format('Y-m-d')) }}"
                               required
                               class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                        @error('date_inscription')
                            <p class="mt-1.5 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Statut --}}
                    <div class="md:col-span-2">
                        <label for="statut"
                               class="mb-2 block text-sm font-semibold text-slate-700">
                            Statut <span class="text-red-500">*</span>
                        </label>

                        <select id="statut"
                                name="statut"
                                required
                                class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                            <option value="">
                                Sélectionner un statut
                            </option>

                            <option value="Actif"
                                @selected(old('statut', 'Actif') === 'Actif')>
                                Actif
                            </option>

                            <option value="En attente"
                                @selected(old('statut') === 'En attente')>
                                En attente
                            </option>

                            <option value="Suspendu"
                                @selected(old('statut') === 'Suspendu')>
                                Suspendu
                            </option>

                            <option value="Terminé"
                                @selected(old('statut') === 'Terminé')>
                                Terminé
                            </option>

                        </select>

                        @error('statut')
                            <p class="mt-1.5 text-xs font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a href="{{ route('inscriptions.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M6 18 18 6M6 6l12 12"/>
                    </svg>

                    Annuler
                </a>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-700 px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 5v14"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 12h14"/>
                    </svg>

                    Enregistrer l'inscription
                </button>

            </div>

        </form>

    </div>

</div>

@endsection