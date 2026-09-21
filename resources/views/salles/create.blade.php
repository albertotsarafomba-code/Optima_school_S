@extends('layouts.gestion-ecole')

@section('title', 'Ajouter une salle')

@section('content')

<div class="mx-auto max-w-4xl space-y-6">

    {{-- HEADER --}}
    <div class="flex items-center gap-4">

        <a href="{{ route('salles.index') }}"
           class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M15 18l-6-6 6-6"/>
            </svg>

        </a>

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Ajouter une salle
            </h1>

            <p class="text-sm text-slate-500">
                Créez une nouvelle salle pour votre établissement.
            </p>
        </div>

    </div>


    {{-- ERREURS --}}
    @if($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <div class="flex items-center gap-2 font-semibold text-red-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">
                    <circle cx="12"
                            cy="12"
                            r="9"/>
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 8v4M12 16h.01"/>
                </svg>

                Vérifiez les informations saisies.
            </div>

            <ul class="mt-2 list-disc pl-7 text-sm text-red-600">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- FORM --}}
    <form method="POST"
          action="{{ route('salles.store') }}"
          class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        @csrf

        <div class="border-b border-slate-100 bg-slate-50 px-6 py-5">

            <h2 class="font-bold text-slate-800">
                Informations de la salle
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Les informations seront associées automatiquement à l'établissement actuel.
            </p>

        </div>


        <div class="grid gap-6 p-6 md:grid-cols-2">

            {{-- NOM --}}
            <div class="md:col-span-2">

                <label for="nom"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Nom de la salle
                    <span class="text-red-500">*</span>
                </label>

                <input type="text"
                       id="nom"
                       name="nom"
                       value="{{ old('nom') }}"
                       placeholder="Exemple : Salle 101"
                       required
                       class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

            </div>


            {{-- CAPACITE --}}
            <div>

                <label for="capacite"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Capacité
                </label>

                <div class="relative">

                    <input type="number"
                           id="capacite"
                           name="capacite"
                           value="{{ old('capacite') }}"
                           min="1"
                           placeholder="Exemple : 40"
                           class="w-full rounded-xl border border-slate-200 px-4 py-3 pr-20 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-400">
                        places
                    </span>

                </div>

            </div>


            {{-- DESCRIPTION --}}
            <div class="md:col-span-2">

                <label for="description"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Description
                </label>

                <textarea id="description"
                          name="description"
                          rows="4"
                          placeholder="Informations complémentaires sur la salle..."
                          class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">{{ old('description') }}</textarea>

            </div>

        </div>


        {{-- ACTIONS --}}
        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end">

            <a href="{{ route('salles.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                Annuler
            </a>

            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 5v14M5 12h14"/>
                </svg>

                Enregistrer la salle
            </button>

        </div>

    </form>

</div>

@endsection
