@extends('layouts.gestion-ecole')

@section('title', 'Ajouter une année scolaire')

@section('page-title', 'Ajouter une année scolaire')

@section('page-description', 'Créez une nouvelle année scolaire pour l’établissement sélectionné')

@section('content')


{{-- Message d'erreur --}}
@if($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-800 shadow-sm">
        <div class="flex items-start gap-3">

            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-500 text-white">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>

            <div>
                <p class="font-semibold">
                    Impossible d’enregistrer l’année scolaire
                </p>

                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
@endif


{{-- Bouton retour --}}
<div class="mb-6">

    <a href="{{ route('school_years.index') }}"
       class="inline-flex items-center gap-2 rounded-lg bg-blue-100 px-4 py-2 text-sm font-medium text-blue-800 transition hover:bg-blue-200">

        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15 19l-7-7 7-7"/>
        </svg>

        Retour aux années scolaires

    </a>

</div>


{{-- Conteneur principal --}}
<div class="mx-auto max-w-4xl">

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">


        {{-- En-tête --}}
        <div class="border-b border-gray-200 bg-white px-6 py-6 sm:px-8">

            <div class="flex items-center gap-4">

                {{-- Icône --}}
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002 2h14"/>

                    </svg>

                </div>


                {{-- Texte --}}
                <div>

                    <h2 class="text-xl font-bold text-black">
                        Nouvelle année scolaire
                    </h2>

                    <p class="mt-1 text-sm text-gray-700">
                        Renseignez les informations de la nouvelle année scolaire.
                    </p>

                </div>

            </div>

        </div>


        {{-- Formulaire --}}
        <form action="{{ route('school_years.store') }}"
              method="POST"
              class="p-6 sm:p-8">

            @csrf


            {{-- Section informations générales --}}
            <div class="mb-8">

                <div class="mb-6 flex items-center gap-3">

                    {{-- Icône information --}}
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z"/>

                        </svg>

                    </div>


                    <div>

                        <h3 class="font-bold text-black">
                            Informations générales
                        </h3>

                        <p class="text-sm text-gray-600">
                            Définissez la période de l’année scolaire.
                        </p>

                    </div>

                </div>


                {{-- Champs --}}
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">


                    {{-- Nom année scolaire --}}
                    <div class="md:col-span-2">

                        <label for="name"
                               class="mb-2 block text-sm font-semibold text-black">

                            Nom de l’année scolaire

                            <span class="text-red-500">*</span>

                        </label>


                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Exemple : 2026-2027"
                               required
                               class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-black outline-none transition placeholder:text-gray-400 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">


                        <p class="mt-2 text-xs text-gray-600">
                            Exemple : 2026-2027, 2027-2028, etc.
                        </p>

                    </div>


                    {{-- Date de début --}}
                    <div>

                        <label for="start_date"
                               class="mb-2 block text-sm font-semibold text-black">

                            Date de début

                            <span class="text-red-500">*</span>

                        </label>


                        <input type="date"
                               id="start_date"
                               name="start_date"
                               value="{{ old('start_date') }}"
                               required
                               class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-black outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">

                    </div>


                    {{-- Date de fin --}}
                    <div>

                        <label for="end_date"
                               class="mb-2 block text-sm font-semibold text-black">

                            Date de fin

                            <span class="text-red-500">*</span>

                        </label>


                        <input type="date"
                               id="end_date"
                               name="end_date"
                               value="{{ old('end_date') }}"
                               required
                               class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-sm text-black outline-none transition focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">

                    </div>

                </div>

            </div>


              {{-- Année scolaire active --}}
<div class="mb-8 rounded-xl border border-green-200 bg-green-50 p-5">

    <div class="flex items-start gap-4">

        {{-- Icône --}}
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-500 text-white">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-1.042-.133-2.053-.382-3.016z"/>

            </svg>

        </div>


        {{-- Contenu --}}
        <div class="flex-1">

            <h3 class="font-semibold text-green-900">
                Année scolaire active
            </h3>

            <p class="mt-1 text-sm leading-6 text-green-800">
                Définissez cette année comme l'année scolaire active du système.
                Une seule année scolaire peut être active à la fois.
            </p>


            {{-- Checkbox --}}
            <label class="mt-4 flex cursor-pointer items-start gap-3">

                <input type="checkbox"
                       name="is_active"
                       value="1"
                       @checked(old('is_active'))
                       class="mt-1 h-5 w-5 rounded border-green-400 text-green-600 focus:ring-green-500">

                <span>

                    <span class="block font-semibold text-green-900">
                        Définir cette année comme année scolaire active
                    </span>

                    <span class="mt-1 block text-xs leading-5 text-green-700">
                        Si vous cochez cette option, l'année active actuelle
                        sera automatiquement archivée.
                    </span>

                </span>

            </label>

        </div>

    </div>

</div>


                    {{-- Texte --}}
                    <div>

                        <h3 class="font-semibold text-green-900">
                            Année scolaire active
                        </h3>

                        <p class="mt-1 text-sm leading-6 text-green-800">
                            L’année créée pourra être définie comme année scolaire active.
                            Une seule année scolaire peut être active à la fois.
                        </p>

                    </div>

                </div>

            </div>


            {{-- Boutons --}}
            <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">


                {{-- Annuler --}}
                <a href="{{ route('school_years.index') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-black transition hover:bg-gray-50">

                    Annuler

                </a>


                {{-- Enregistrer --}}
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-green-500 px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-green-600 hover:shadow-lg">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="mr-2 h-5 w-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 13l4 4L19 7"/>

                    </svg>

                    Enregistrer l’année scolaire

                </button>

            </div>

        </form>

    </div>

</div>


@endsection

