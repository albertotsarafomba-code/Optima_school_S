@extends('layouts.gestion-ecole')

@section('title', 'Modifier un établissement')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Modifier l'établissement
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Modifier les informations de
                <strong>{{ $establishment->nom }}</strong>
            </p>
        </div>

        <a
            href="{{ route('establishments.choose') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>

            Retour
        </a>
    </div>


    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
            <h3 class="font-semibold text-red-800">
                Vérifiez les informations saisies.
            </h3>

            <ul class="mt-2 list-disc pl-5 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Informations de l'établissement
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Les champs marqués d'un
                <span class="text-red-500">*</span>
                sont obligatoires.
            </p>
        </div>


        <form
            action="{{ route('establishments.update', $establishment) }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-6"
        >

            @csrf
            @method('PUT')


            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">


                {{-- Type d'établissement --}}
                <div class="md:col-span-2">

                    <label
                        for="school_type_id"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Type d'établissement
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="school_type_id"
                        name="school_type_id"
                        required
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-700 focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                    >

                        <option value="">
                            -- Sélectionner le type --
                        </option>

                        @foreach ($schoolTypes as $schoolType)

                            <option
                                value="{{ $schoolType->id }}"
                                @selected(
                                    old(
                                        'school_type_id',
                                        $establishment->school_type_id
                                    ) == $schoolType->id
                                )
                            >
                                {{ $schoolType->nom }}
                            </option>

                        @endforeach

                    </select>

                    @error('school_type_id')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Nom --}}
                <div class="md:col-span-2">

                    <label
                        for="nom"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Nom de l'établissement
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="nom"
                        name="nom"
                        value="{{ old('nom', $establishment->nom) }}"
                        maxlength="255"
                        required
                        placeholder="Nom de l'établissement"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-700 focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                    >

                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Adresse --}}
                <div class="md:col-span-2">

                    <label
                        for="adresse"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Adresse
                    </label>

                    <input
                        type="text"
                        id="adresse"
                        name="adresse"
                        value="{{ old('adresse', $establishment->adresse) }}"
                        maxlength="255"
                        placeholder="Adresse de l'établissement"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-700 focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                    >

                    @error('adresse')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Téléphone --}}
                <div>

                    <label
                        for="telephone"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Téléphone
                    </label>

                    <input
                        type="text"
                        id="telephone"
                        name="telephone"
                        value="{{ old('telephone', $establishment->telephone) }}"
                        maxlength="10"
                        inputmode="numeric"
                        placeholder="0321234567"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-700 focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                    >

                    @error('telephone')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Email --}}
                <div>

                    <label
                        for="email"
                        class="mb-2 block text-sm font-semibold text-slate-700"
                    >
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $establishment->email) }}"
                        maxlength="255"
                        placeholder="exemple@ecole.mg"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-700 focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                    >

                    @error('email')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>


            <!-- LOGO -->

            <div class="mt-6">

                <label
                    for="logo"
                    class="mb-2 block text-sm font-semibold text-slate-700"
                >
                    Logo de l'établissement
                </label>

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">

                    <!-- LOGO ACTUEL / APERCU -->
                    <div
                        id="logo-preview-container"
                        class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-slate-300 bg-slate-50"
                    >
                        @if($establishment->logo)
                            <img
                                id="logo-preview"
                                src="{{ asset('storage/' . $establishment->logo) }}"
                                alt="Logo de {{ $establishment->nom }}"
                                class="h-full w-full object-contain"
                            >
                        @else
                            <div
                                id="logo-empty"
                                class="px-2 text-center text-xs text-slate-400"
                            >
                                Aucun logo
                            </div>

                            <img
                                id="logo-preview"
                                src=""
                                alt="Aperçu du logo"
                                class="hidden h-full w-full object-contain"
                            >
                        @endif
                    </div>

                    <div class="flex-1">

                        <input
                            id="logo"
                            type="file"
                            name="logo"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            class="block w-full cursor-pointer text-sm text-slate-600
                                   file:mr-4 file:rounded-lg file:border-0
                                   file:bg-blue-50 file:px-4 file:py-2.5
                                   file:font-semibold file:text-blue-700
                                   hover:file:bg-blue-100"
                        >

                        <p class="mt-1.5 text-xs text-slate-500">
                            Laissez vide pour conserver le logo actuel.
                            JPG, JPEG, PNG ou WEBP — 5 Mo maximum.
                        </p>

                        @error('logo')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>

            </div>


            {{-- Boutons --}}
            <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('establishments.choose') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"
                >
                    Annuler
                </a>


                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800"
                >

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 12.75 9.5 17 19 7.5"/>
                    </svg>

                    Enregistrer les modifications

                </button>

            </div>

        </form>

    </div>

</div>

@endsection

