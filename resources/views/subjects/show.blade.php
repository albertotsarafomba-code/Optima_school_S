@extends('layouts.gestion-ecole')

@section('title', 'Détails matière')

@section('page-title', 'Détails de la matière')

@section('page-description', 'Consultez les informations détaillées de cette matière')

@section('content')

<div class="mx-auto max-w-6xl">

    {{-- En-tête de page --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-3">

            {{-- Icône matière --}}
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M4 5a2 2 0 012-2h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5z"/>

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M8 7h8M8 11h8M8 15h5"/>

                </svg>

            </div>

            <div>

                <h2 class="text-xl font-bold text-blue-950">
                    {{ $subject->nom }}
                </h2>

                <p class="text-sm text-gray-500">
                    Code : {{ $subject->code }}
                </p>

            </div>

        </div>


        {{-- Retour --}}
        <a href="{{ route('subjects.index') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-4 w-4"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M10 19l-7-7 7-7"/>

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M3 12h18"/>

            </svg>

            <span>Retour aux matières</span>

        </a>

    </div>


    {{-- Carte principale --}}
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">


        {{-- Bandeau --}}
        <div class="border-b border-blue-100 bg-blue-50 px-6 py-5">

            <div class="flex items-center gap-4">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-white">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>

                    </svg>

                </div>

                <div>

                    <h3 class="text-lg font-bold text-blue-950">
                        Informations de la matière
                    </h3>

                    <p class="mt-0.5 text-sm text-gray-600">
                        Détails et informations associées à cette matière.
                    </p>

                </div>

            </div>

        </div>


        {{-- Informations --}}
        <div class="p-6">


            {{-- Première ligne --}}
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">


                {{-- Code --}}
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">

                    <div class="mb-3 flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M7 8h10M7 12h10M7 16h6"/>

                            </svg>

                        </div>

                        <p class="text-sm font-medium text-gray-500">
                            Code matière
                        </p>

                    </div>

                    <p class="text-lg font-bold text-blue-950">
                        {{ $subject->code }}
                    </p>

                </div>


                {{-- Nom --}}
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">

                    <div class="mb-3 flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-100 text-green-700">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M4 5a2 2 0 012-2h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5z"/>

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M8 7h8M8 11h8M8 15h5"/>

                            </svg>

                        </div>

                        <p class="text-sm font-medium text-gray-500">
                            Nom de la matière
                        </p>

                    </div>

                    <p class="text-lg font-bold text-blue-950">
                        {{ $subject->nom }}
                    </p>

                </div>


                {{-- Type établissement --}}
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">

                    <div class="mb-3 flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-100 text-green-700">

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
                                      d="M5 21V5l7-3 7 3v16"/>

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M9 21v-6h6v6"/>

                            </svg>

                        </div>

                        <p class="text-sm font-medium text-gray-500">
                            Type d'établissement
                        </p>

                    </div>

                    @if($subject->schoolType)

                        <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-bold text-green-700">

                            {{ $subject->schoolType->name }}

                        </span>

                    @else

                        <span class="text-sm font-medium text-gray-400">
                            Non défini
                        </span>

                    @endif

                </div>


                {{-- Identifiant --}}
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">

                    <div class="mb-3 flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M9 12h6"/>

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 9v6"/>

                                <circle cx="12"
                                        cy="12"
                                        r="9"/>

                            </svg>

                        </div>

                        <p class="text-sm font-medium text-gray-500">
                            Identifiant
                        </p>

                    </div>

                    <p class="text-lg font-bold text-blue-950">
                        #{{ $subject->id }}
                    </p>

                </div>

            </div>


            {{-- Description --}}
            <div class="mt-5 rounded-xl border border-gray-200 bg-gray-50 p-5">

                <div class="mb-3 flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M4 6h16M4 12h16M4 18h10"/>

                        </svg>

                    </div>

                    <p class="text-sm font-medium text-gray-500">
                        Description
                    </p>

                </div>

                @if($subject->description)

                    <p class="text-sm leading-6 text-gray-700">
                        {{ $subject->description }}
                    </p>

                @else

                    <p class="text-sm italic text-gray-400">
                        Aucune description renseignée.
                    </p>

                @endif

            </div>


            {{-- Actions --}}
            <div class="mt-6 flex flex-col gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:items-center sm:justify-between">

                <a href="{{ route('subjects.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M10 19l-7-7 7-7"/>

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 12h18"/>

                    </svg>

                    Retour

                </a>


                <div class="flex flex-col gap-3 sm:flex-row">


                    {{-- Modifier --}}
                    <a href="{{ route('subjects.edit', $subject) }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5"/>

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>

                        </svg>

                        Modifier

                    </a>


                    {{-- Supprimer --}}
                    <form action="{{ route('subjects.destroy', $subject) }}"
                          method="POST"
                          onsubmit="return confirm('Voulez-vous vraiment supprimer cette matière ?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-5 py-2.5 text-sm font-semibold text-red-700 transition hover:border-red-300 hover:bg-red-600 hover:text-white sm:w-auto">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M3 6h18"/>

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2"/>

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M10 11v6M14 11v6"/>

                            </svg>

                            Supprimer

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection