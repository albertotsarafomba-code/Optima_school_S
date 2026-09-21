@extends('layouts.gestion-ecole')

@section('title', 'Ajouter une matière')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-5 sm:px-6 lg:px-8">

    {{-- En-tête --}}
    <div class="mx-auto max-w-5xl">

        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <path d="M12 20h9"/>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-xl font-bold text-slate-800">
                            Ajouter une matière
                        </h1>
                        <p class="text-sm text-slate-500">
                            Créer une matière pour votre établissement
                        </p>
                    </div>
                </div>
            </div>

            <a href="{{ route('subjects.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300
                      bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm
                      transition hover:bg-slate-50">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-4 w-4"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">
                    <path d="m15 18-6-6 6-6"/>
                </svg>

                Retour aux matières
            </a>
        </div>

        {{-- Informations établissement --}}
        <div class="mb-5 rounded-xl border border-blue-100 bg-white p-4 shadow-sm">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <path d="M3 21h18"/>
                            <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"/>
                            <path d="M9 7h1"/>
                            <path d="M14 7h1"/>
                            <path d="M9 11h1"/>
                            <path d="M14 11h1"/>
                            <path d="M9 15h1"/>
                            <path d="M14 15h1"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Établissement
                        </p>
                        <p class="text-sm font-semibold text-slate-800">
                            {{ $establishment->nom }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-50 text-green-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Année scolaire
                        </p>

                        @if($activeSchoolYear)
                            <p class="text-sm font-semibold text-slate-800">
                                {{ $activeSchoolYear->name }}
                            </p>
                        @else
                            <p class="text-sm font-medium text-red-600">
                                Aucune année scolaire active
                            </p>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- Formulaire --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 px-5 py-4">
                <h2 class="text-base font-semibold text-slate-800">
                    Informations de la matière
                </h2>
                <p class="mt-1 text-xs text-slate-500">
                    Les matières sont saisies manuellement par l'administrateur.
                </p>
            </div>

            <form action="{{ route('subjects.store') }}"
                  method="POST"
                  class="p-5">

                @csrf

                @if($errors->any())
                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3">
                        <div class="flex gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="mt-0.5 h-5 w-5 shrink-0 text-red-600"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>

                            <div>
                                <p class="text-sm font-semibold text-red-700">
                                    Vérifiez les informations saisies.
                                </p>

                                <ul class="mt-1 list-disc pl-5 text-xs text-red-600">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Année scolaire --}}
                    <div>
                        <label for="school_year_id"
                               class="mb-1.5 block text-sm font-medium text-slate-700">
                            Année scolaire
                            <span class="text-red-500">*</span>
                        </label>

                        <select id="school_year_id"
                                name="school_year_id"
                                required
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5
                                       text-sm text-slate-700 outline-none transition
                                       focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                            <option value="">Sélectionner une année scolaire</option>

                            @foreach($schoolYears as $year)
                                <option value="{{ $year->id }}"
                                    @selected(
                                        old('school_year_id',
                                            $activeSchoolYear?->id
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
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Code --}}
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
                               value="{{ old('code') }}"
                               maxlength="50"
                               placeholder="Ex. MATH, FR, INFO"
                               class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5
                                      text-sm text-slate-700 placeholder-slate-400 outline-none transition
                                      focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                        @error('code')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Nom --}}
                    <div class="md:col-span-2">
                        <label for="nom"
                               class="mb-1.5 block text-sm font-medium text-slate-700">
                            Nom de la matière
                            <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                               id="nom"
                               name="nom"
                               value="{{ old('nom') }}"
                               required
                               maxlength="255"
                               placeholder="Ex. Mathématiques"
                               class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5
                                      text-sm text-slate-700 placeholder-slate-400 outline-none transition
                                      focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                        @error('nom')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Description --}}
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
                                  rows="3"
                                  placeholder="Description ou remarque concernant cette matière..."
                                  class="w-full resize-none rounded-lg border border-slate-300 bg-white px-3 py-2.5
                                         text-sm text-slate-700 placeholder-slate-400 outline-none transition
                                         focus:border-blue-500 focus:ring-2 focus:ring-blue-100">{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

                {{-- Actions --}}
                <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">

                    <a href="{{ route('subjects.index') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300
                              bg-white px-5 py-2.5 text-sm font-medium text-slate-700
                              transition hover:bg-slate-50">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <path d="M6 18 18 6"/>
                            <path d="m6 6 12 12"/>
                        </svg>

                        Annuler
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg
                                   bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white
                                   shadow-sm transition hover:bg-blue-800
                                   focus:outline-none focus:ring-2 focus:ring-blue-300">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <path d="M12 5v14"/>
                            <path d="M5 12h14"/>
                        </svg>

                        Ajouter la matière
                    </button>

                </div>

            </form>
        </div>

    </div>
</div>
@endsection
