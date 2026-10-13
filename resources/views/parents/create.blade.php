@extends('layouts.gestion-ecole')

@section('title', 'Nouveau parent - OPTIMA SCHOOL')
@section('page-title', 'Nouveau parent')

@section('content')

    <div class="mx-auto max-w-5xl space-y-6">

        {{-- =========================================================
             EN-TÊTE
        ========================================================== --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center
                    sm:justify-between">

            <div>

                <div class="flex items-center gap-3">

                    <div class="flex h-12 w-12 items-center justify-center
                                rounded-xl bg-blue-100 text-blue-700">

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

                        <h2 class="text-2xl font-black text-slate-800">
                            Nouveau parent
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Créer un compte parent et le rattacher à un ou plusieurs élèves.
                        </p>

                    </div>

                </div>

                @if($establishment)

                    <div class="mt-4 inline-flex items-center gap-2 rounded-lg
                                border border-blue-200 bg-blue-50 px-3 py-2
                                text-sm font-semibold text-blue-800">

                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 21h18M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16M9 21v-4h6v4M8 7h2M14 7h2M8 11h2M14 11h2"
                            />
                        </svg>

                        <span>
                            {{ $establishment->nom }}
                        </span>

                    </div>

                @endif

            </div>

            <a
                href="{{ route('parents.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl
                       border border-slate-300 bg-white px-5 py-3
                       text-sm font-bold text-slate-700 shadow-sm
                       transition hover:bg-slate-50"
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
                        d="M10 19l-7-7 7-7M3 12h18"
                    />
                </svg>

                <span>Retour aux parents</span>

            </a>

        </div>


        {{-- =========================================================
             FORMULAIRE
        ========================================================== --}}
        <form
            method="POST"
            action="{{ route('parents.store') }}"
            class="space-y-6"
        >

            @csrf


            {{-- =====================================================
                 INFORMATIONS DU PARENT
            ====================================================== --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-6 py-5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center
                                    rounded-xl bg-blue-100 text-blue-700">

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
                                    d="M20 21a8 8 0 0 0-16 0M12 13a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z"
                                />
                            </svg>

                        </div>

                        <div>

                            <h3 class="text-lg font-black text-slate-800">
                                Informations personnelles
                            </h3>

                            <p class="text-sm text-slate-500">
                                Renseignez les informations du parent.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

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
                            name="prenom"
                            type="text"
                            value="{{ old('prenom') }}"
                            placeholder="Ex. Jean"
                            autocomplete="given-name"
                            class="w-full rounded-xl border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-800
                                   outline-none transition
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                   @error('prenom') border-red-400 @enderror"
                        >

                        @error('prenom')
                            <p class="mt-1 text-sm font-medium text-red-600">
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
                            name="nom"
                            type="text"
                            value="{{ old('nom') }}"
                            placeholder="Ex. RAKOTO"
                            autocomplete="family-name"
                            required
                            class="w-full rounded-xl border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-800
                                   outline-none transition
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                   @error('nom') border-red-400 @enderror"
                        >

                        @error('nom')
                            <p class="mt-1 text-sm font-medium text-red-600">
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
                            name="telephone"
                            type="text"
                            value="{{ old('telephone') }}"
                            placeholder="Ex. 0321234567"
                            autocomplete="tel"
                            class="w-full rounded-xl border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-800
                                   outline-none transition
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                   @error('telephone') border-red-400 @enderror"
                        >

                        @error('telephone')
                            <p class="mt-1 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Email --}}
                    <div>

                        <label
                            for="email"
                            class="mb-2 block text-sm font-bold text-slate-700"
                        >
                            Email <span class="text-red-600">*</span>
                        </label>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            placeholder="parent@example.com"
                            autocomplete="email"
                            required
                            class="w-full rounded-xl border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-800
                                   outline-none transition
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                   @error('email') border-red-400 @enderror"
                        >

                        @error('email')
                            <p class="mt-1 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Adresse --}}
                    <div class="md:col-span-2">

                        <label
                            for="adresse"
                            class="mb-2 block text-sm font-bold text-slate-700"
                        >
                            Adresse
                        </label>

                        <textarea
                            id="adresse"
                            name="adresse"
                            rows="3"
                            placeholder="Adresse du parent..."
                            autocomplete="street-address"
                            class="w-full resize-y rounded-xl border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-800
                                   outline-none transition
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                   @error('adresse') border-red-400 @enderror"
                        >{{ old('adresse') }}</textarea>

                        @error('adresse')
                            <p class="mt-1 text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 COMPTE UTILISATEUR
            ====================================================== --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-6 py-5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center
                                    rounded-xl bg-green-100 text-green-700">

                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                            >
                                <rect
                                    x="3"
                                    y="11"
                                    width="18"
                                    height="10"
                                    rx="2"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M7 11V7a5 5 0 0 1 10 0v4"
                                />
                            </svg>

                        </div>

                        <div>

                            <h3 class="text-lg font-black text-slate-800">
                                Accès au compte parent
                            </h3>

                            <p class="text-sm text-slate-500">
                                Ces informations permettront au parent de se connecter.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                    {{-- Mot de passe --}}
                    <div>

                        <label
                            for="password"
                            class="mb-2 block text-sm font-bold text-slate-700"
                        >
                            Mot de passe <span class="text-red-600">*</span>
                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="new-password"
                            required
                            placeholder="Minimum 8 caractères"
                            class="w-full rounded-xl border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-800
                                   outline-none transition
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                   @error('password') border-red-400 @enderror"
                        >

                        @error('password')
                            <p class="mt-1 text-sm font-medium text-red-600">
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
                            Confirmation du mot de passe <span class="text-red-600">*</span>
                        </label>

                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            required
                            placeholder="Retapez le mot de passe"
                            class="w-full rounded-xl border border-slate-300
                                   bg-white px-4 py-3 text-sm text-slate-800
                                   outline-none transition
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                        >

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 ÉLÈVES RATTACHÉS
            ====================================================== --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-6 py-5">

                    <div class="flex items-start gap-3">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center
                                    rounded-xl bg-blue-100 text-blue-700">

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
                                    d="M17 20v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 10a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM23 20v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"
                                />
                            </svg>

                        </div>

                        <div>

                            <h3 class="text-lg font-black text-slate-800">
                                Élèves rattachés
                            </h3>

                            <p class="text-sm text-slate-500">
                                Sélectionnez le ou les élèves de ce parent.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="p-6">

                    @if($students->isNotEmpty())

                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">

                            @foreach($students as $student)

                                <label
                                    class="flex cursor-pointer items-start gap-3 rounded-xl
                                           border border-slate-200 bg-slate-50 p-4
                                           transition hover:border-blue-300 hover:bg-blue-50"
                                >

                                    <input
                                        type="checkbox"
                                        name="student_ids[]"
                                        value="{{ $student->id }}"
                                        @checked(in_array($student->id, old('student_ids', [])))
                                        class="mt-1 h-5 w-5 rounded border-slate-300
                                               text-blue-700 focus:ring-blue-500"
                                    >

                                    <span class="min-w-0">

                                        <span class="block font-bold text-slate-800">
                                            {{ trim($student->prenom . ' ' . $student->nom) }}
                                        </span>

                                        <span class="mt-1 block text-xs text-slate-500">
                                            Matricule :
                                            {{ $student->matricule ?: 'Non renseigné' }}
                                        </span>

                                    </span>

                                </label>

                            @endforeach

                        </div>

                    @else

                        <div class="rounded-xl border border-amber-200
                                    bg-amber-50 px-4 py-4">

                            <div class="flex items-start gap-3">

                                <svg
                                    class="mt-0.5 h-5 w-5 shrink-0 text-amber-600"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 9v4M12 17h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"
                                    />
                                </svg>

                                <div>

                                    <p class="text-sm font-bold text-amber-800">
                                        Aucun élève disponible
                                    </p>

                                    <p class="mt-1 text-sm text-amber-700">
                                        Aucun élève n'est actuellement enregistré
                                        dans cet établissement.
                                    </p>

                                </div>

                            </div>

                        </div>

                    @endif

                    @error('student_ids')
                        <p class="mt-3 text-sm font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    @error('student_ids.*')
                        <p class="mt-3 text-sm font-medium text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>


            {{-- =====================================================
                 ACTIONS
            ====================================================== --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('parents.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl
                           border border-slate-300 bg-white px-6 py-3
                           text-sm font-bold text-slate-700 shadow-sm
                           transition hover:bg-slate-50"
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
                            d="M6 6l12 12M18 6 6 18"
                        />
                    </svg>

                    <span>Annuler</span>

                </a>


                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl
                           bg-blue-700 px-6 py-3 text-sm font-bold text-white
                           shadow-md transition hover:bg-blue-800 hover:shadow-lg"
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
                            d="M12 5v14M5 12h14"
                        />
                    </svg>

                    <span>Créer le parent</span>

                </button>

            </div>

        </form>

    </div>

@endsection
