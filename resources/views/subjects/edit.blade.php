@extends('layouts.gestion-ecole')

@section('title', 'Modifier une matière')

@section('content')

<div class="min-h-full bg-slate-50 px-4 py-5 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-5xl">

        {{-- =====================================================
             EN-TÊTE
        ====================================================== --}}
        <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div class="min-w-0">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 20h9"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 13.5-13.5Z"/>
                        </svg>

                    </div>

                    <div class="min-w-0">

                        <h1 class="truncate text-xl font-bold text-slate-800 sm:text-2xl">
                            Modifier une matière
                        </h1>

                        <p class="mt-0.5 text-sm text-slate-500">
                            Mettez à jour les informations de cette matière.
                        </p>

                    </div>

                </div>

            </div>


            {{-- RETOUR --}}
            <a href="{{ route('subjects.index') }}"
               class="inline-flex shrink-0 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="m15 18-6-6 6-6"/>
                </svg>

                Retour aux matières

            </a>

        </div>


        {{-- =====================================================
             INFORMATIONS DE LA MATIÈRE
        ====================================================== --}}
        <div class="mb-5 rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="grid grid-cols-1 divide-y divide-slate-100 md:grid-cols-3 md:divide-x md:divide-y-0">

                {{-- MATIÈRE --}}
                <div class="flex items-center gap-3 px-5 py-4">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                        </svg>

                    </div>

                    <div class="min-w-0">

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Matière
                        </p>

                        <p class="truncate text-sm font-semibold text-slate-800">
                            {{ $subject->nom }}
                        </p>

                    </div>

                </div>


                {{-- CODE --}}
                <div class="flex items-center gap-3 px-5 py-4">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-green-50 text-green-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="m8 9-3 3 3 3"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="m16 9 3 3-3 3"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="m14 5-4 14"/>
                        </svg>

                    </div>

                    <div class="min-w-0">

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Code
                        </p>

                        <p class="truncate text-sm font-semibold text-slate-800">
                            {{ $subject->code ?: 'Non défini' }}
                        </p>

                    </div>

                </div>


                {{-- ANNÉE SCOLAIRE --}}
                <div class="flex items-center gap-3 px-5 py-4">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <rect x="3"
                                  y="4"
                                  width="18"
                                  height="17"
                                  rx="2"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>

                    </div>

                    <div class="min-w-0">

                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Année scolaire
                        </p>

                        @if($subject->schoolYear)

                            <p class="truncate text-sm font-semibold text-slate-800">
                                {{ $subject->schoolYear->name }}
                            </p>

                        @else

                            <p class="text-sm font-medium text-red-600">
                                Non définie
                            </p>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             FORMULAIRE
        ====================================================== --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- HEADER FORMULAIRE --}}
            <div class="border-b border-slate-100 px-5 py-4 sm:px-6">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M11 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5Z"/>
                        </svg>

                    </div>

                    <div>

                        <h2 class="text-base font-semibold text-slate-800 sm:text-lg">
                            Informations de la matière
                        </h2>

                        <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
                            Modifiez les informations puis enregistrez les changements.
                        </p>

                    </div>

                </div>

            </div>


            {{-- FORM CONTENT --}}
            <div class="p-5 sm:p-6">

                {{-- =================================================
                     ERREURS
                ================================================== --}}
                @if($errors->any())

                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3">

                        <div class="flex gap-3">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="mt-0.5 h-5 w-5 shrink-0 text-red-600"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <circle cx="12"
                                        cy="12"
                                        r="9"/>
                                <path stroke-linecap="round"
                                      d="M12 8v4"/>
                                <path stroke-linecap="round"
                                      d="M12 16h.01"/>
                            </svg>

                            <div class="min-w-0">

                                <p class="text-sm font-semibold text-red-700">
                                    Vérifiez les informations saisies.
                                </p>

                                <ul class="mt-1 list-disc space-y-0.5 pl-5 text-xs text-red-600 sm:text-sm">

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


                {{-- =================================================
                     FORMULAIRE
                ================================================== --}}
                <form action="{{ route('subjects.update', $subject) }}"
                      method="POST">

                    @csrf

                    @method('PUT')


                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                        {{-- =========================================
                             ANNÉE SCOLAIRE
                        ========================================== --}}
                        <div>

                            <label for="school_year_id"
                                   class="mb-1.5 block text-sm font-medium text-slate-700">

                                Année scolaire

                                <span class="text-red-500">
                                    *
                                </span>

                            </label>

                            <select id="school_year_id"
                                    name="school_year_id"
                                    required
                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                                <option value="">
                                    Sélectionner une année scolaire
                                </option>

                                @foreach($schoolYears as $year)

                                    <option value="{{ $year->id }}"
                                        @selected(
                                            old(
                                                'school_year_id',
                                                $subject->school_year_id
                                            ) == $year->id
                                        )>

                                        {{ $year->name }}

                                        @if($year->is_active)
                                            — Active
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('school_year_id')

                                <p class="mt-1.5 text-xs text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =========================================
                             CODE
                        ========================================== --}}
                        <div>

                            <label for="code"
                                   class="mb-1.5 block text-sm font-medium text-slate-700">

                                Code

                                <span class="text-xs font-normal text-slate-400">
                                    (optionnel)
                                </span>

                            </label>

                            <input type="text"
                                   id="code"
                                   name="code"
                                   value="{{ old('code', $subject->code) }}"
                                   maxlength="50"
                                   autocomplete="off"
                                   placeholder="Ex. MATH, FR, INFO"
                                   class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 placeholder-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                            @error('code')

                                <p class="mt-1.5 text-xs text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =========================================
                             NOM
                        ========================================== --}}
                        <div class="md:col-span-2">

                            <label for="nom"
                                   class="mb-1.5 block text-sm font-medium text-slate-700">

                                Nom de la matière

                                <span class="text-red-500">
                                    *
                                </span>

                            </label>

                            <input type="text"
                                   id="nom"
                                   name="nom"
                                   value="{{ old('nom', $subject->nom) }}"
                                   required
                                   maxlength="255"
                                   autocomplete="off"
                                   placeholder="Ex. Mathématiques"
                                   class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 placeholder-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                            @error('nom')

                                <p class="mt-1.5 text-xs text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>


                        {{-- =========================================
                             DESCRIPTION
                        ========================================== --}}
                        <div class="md:col-span-2">

                            <label for="description"
                                   class="mb-1.5 block text-sm font-medium text-slate-700">

                                Description

                                <span class="text-xs font-normal text-slate-400">
                                    (optionnelle)
                                </span>

                            </label>

                            <textarea id="description"
                                      name="description"
                                      rows="4"
                                      maxlength="1000"
                                      placeholder="Description ou remarque concernant cette matière..."
                                      class="w-full resize-none rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 placeholder-slate-400 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">{{ old('description', $subject->description) }}</textarea>

                            @error('description')

                                <p class="mt-1.5 text-xs text-red-600">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                    </div>


                    {{-- =================================================
                         ACTIONS
                    ================================================== --}}
                    <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">

                        {{-- ANNULER --}}
                        <a href="{{ route('subjects.index') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="m15 18-6-6 6-6"/>
                            </svg>

                            Annuler

                        </a>


                        {{-- ENREGISTRER --}}
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-200">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.8">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="m5 13 4 4L19 7"/>
                            </svg>

                            Enregistrer les modifications

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection
