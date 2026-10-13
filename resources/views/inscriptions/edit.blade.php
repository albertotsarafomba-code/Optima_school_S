@extends('layouts.gestion-ecole')

@section('title', 'Modifier l’inscription')

@section('content')

<div class="space-y-6">


{{-- EN-TÊTE --}}
<div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

    <div class="flex items-center gap-3">

        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-6 w-6"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="1.8">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M16.862 3.487a2.25 2.25 0 113.182 3.182L8.25 18.464 4 19.5l1.036-4.25L16.862 3.487z"/>
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M19 15v4.25A1.75 1.75 0 0117.25 21H6.75A1.75 1.75 0 015 19.25V8.75A1.75 1.75 0 016.75 7H11"/>
            </svg>

        </div>

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Modifier l'inscription
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Modifier les informations de l'inscription de l'étudiant
            </p>
        </div>

    </div>

    <a href="{{ route('inscriptions.show', $inscription) }}"
       class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">

        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor"
             stroke-width="1.8">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>

        Retour aux détails
    </a>

</div>


{{-- ERREURS --}}
@if ($errors->any())

    <div class="rounded-xl border border-red-200 bg-red-50 p-4">

        <div class="flex gap-3">

            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 9v3.75m0 3.75h.007M10.29 3.86l-7.06 12.22A1.5 1.5 0 004.53 18.3h14.94a1.5 1.5 0 001.3-2.22L13.71 3.86a1.5 1.5 0 00-2.6 0z"/>
                </svg>

            </div>

            <div>
                <p class="font-semibold text-red-800">
                    Vérifiez les informations saisies.
                </p>

                <ul class="mt-2 space-y-1 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        </div>

    </div>

@endif


{{-- FORMULAIRE --}}
<form action="{{ route('inscriptions.update', $inscription) }}"
      method="POST">

    @csrf
    @method('PUT')

    <input type="hidden"
           name="student_id"
           value="{{ old('student_id', $inscription->student_id) }}">

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        {{-- ÉTUDIANT --}}
        <div class="border-b border-gray-200">

            <div class="flex items-center gap-3 bg-gray-50 px-6 py-5">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M4.5 20.118a7.5 7.5 0 0115 0A17.933 17.933 0 0112 21.75a17.933 17.933 0 01-7.5-1.632z"/>
                    </svg>

                </div>

                <div>
                    <h2 class="font-semibold text-gray-800">
                        Informations de l'étudiant
                    </h2>

                    <p class="text-xs text-gray-500">
                        Identité et coordonnées de l'étudiant
                    </p>
                </div>

            </div>


            <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                {{-- NOM --}}
                <div>
                    <label for="nom"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Nom <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           id="nom"
                           name="nom"
                           value="{{ old('nom', $inscription->student->nom ?? '') }}"
                           required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                           placeholder="Nom de l'étudiant">

                    @error('nom')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- PRÉNOM --}}
                <div>
                    <label for="prenom"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Prénom <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           id="prenom"
                           name="prenom"
                           value="{{ old('prenom', $inscription->student->prenom ?? '') }}"
                           required
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                           placeholder="Prénom de l'étudiant">

                    @error('prenom')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- DATE NAISSANCE --}}
                <div>
                    <label for="date_naissance"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Date de naissance
                    </label>

                    <input type="date"
                           id="date_naissance"
                           name="date_naissance"
                           value="{{ old('date_naissance', $inscription->student->date_naissance ?? '') }}"
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                    @error('date_naissance')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- LIEU NAISSANCE --}}
                <div>
                    <label for="lieu_naissance"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Lieu de naissance
                    </label>

                    <input type="text"
                           id="lieu_naissance"
                           name="lieu_naissance"
                           value="{{ old('lieu_naissance', $inscription->student->lieu_naissance ?? '') }}"
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                           placeholder="Lieu de naissance">

                    @error('lieu_naissance')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- SEXE --}}
                <div>
                    <label for="sexe"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Sexe
                    </label>

                    <select id="sexe"
                            name="sexe"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                        <option value="">Sélectionner</option>

                        <option value="Masculin"
                            @selected(old('sexe', $inscription->student->sexe ?? '') === 'Masculin')>
                            Masculin
                        </option>

                        <option value="Féminin"
                            @selected(old('sexe', $inscription->student->sexe ?? '') === 'Féminin')>
                            Féminin
                        </option>

                    </select>

                    @error('sexe')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- TÉLÉPHONE --}}
                <div>
                    <label for="telephone"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Téléphone
                    </label>

                    <input type="text"
                           id="telephone"
                           name="telephone"
                           value="{{ old('telephone', $inscription->student->telephone ?? '') }}"
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                           placeholder="Numéro de téléphone">

                    @error('telephone')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- EMAIL --}}
                <div>
                    <label for="email"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Email
                    </label>

                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email', $inscription->student->email ?? '') }}"
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                           placeholder="exemple@email.com">

                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- ADRESSE --}}
                <div class="md:col-span-2">
                    <label for="adresse"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Adresse
                    </label>

                    <textarea id="adresse"
                              name="adresse"
                              rows="3"
                              class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                              placeholder="Adresse de l'étudiant">{{ old('adresse', $inscription->student->adresse ?? '') }}</textarea>

                    @error('adresse')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

        </div>


        {{-- INFORMATIONS SCOLAIRES --}}
        <div class="border-b border-gray-200">

            <div class="flex items-center gap-3 bg-gray-50 px-6 py-5">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-700">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M4.5 3.75h15A1.5 1.5 0 0121 5.25v13.5a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 013 18.75V5.25a1.5 1.5 0 011.5-1.5z"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M7.5 7.5h9M7.5 11.25h9M7.5 15h5"/>
                    </svg>

                </div>

                <div>
                    <h2 class="font-semibold text-gray-800">
                        Informations scolaires
                    </h2>

                    <p class="text-xs text-gray-500">
                        Année scolaire et classe de l'étudiant
                    </p>
                </div>

            </div>


            <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                {{-- ANNÉE SCOLAIRE --}}
                <div>
                    <label for="school_year_id"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Année scolaire <span class="text-red-500">*</span>
                    </label>

                    <select id="school_year_id"
                            name="school_year_id"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                        <option value="">
                            Sélectionner une année scolaire
                        </option>

                        @foreach($schoolYears as $schoolYear)

                            <option value="{{ $schoolYear->id }}"
                                @selected(
                                    old(
                                        'school_year_id',
                                        $inscription->school_year_id
                                    ) == $schoolYear->id
                                )>

                                {{ $schoolYear->name }}

                            </option>

                        @endforeach

                    </select>

                    @error('school_year_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- CLASSE --}}
                <div>
                    <label for="class_room_id"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Classe <span class="text-red-500">*</span>
                    </label>

                    <select id="class_room_id"
                            name="class_room_id"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                        <option value="">
                            Sélectionner une classe
                        </option>

                        @foreach($classes as $class)

                            <option value="{{ $class->id }}"
                                @selected(
                                    old(
                                        'class_room_id',
                                        $inscription->class_room_id
                                    ) == $class->id
                                )>

                                {{ $class->nom_classe }}

                                @if($class->niveau)
                                    — {{ $class->niveau }}
                                @endif

                                @if($class->filiere)
                                    — {{ $class->filiere }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                    @error('class_room_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- SITUATION SCOLAIRE --}}
                <div>
                    <label for="situation_scolaire"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Situation scolaire
                    </label>

                    <select id="situation_scolaire"
                            name="situation_scolaire"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                        <option value="">
                            Sélectionner
                        </option>

                        @foreach([
                            'Nouveau',
                            'Réinscription',
                            'Transfert'
                        ] as $situation)

                            <option value="{{ $situation }}"
                                @selected(
                                    old(
                                        'situation_scolaire',
                                        $inscription->situation_scolaire
                                    ) === $situation
                                )>

                                {{ $situation }}

                            </option>

                        @endforeach

                    </select>

                    @error('situation_scolaire')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- DATE INSCRIPTION --}}
                <div>
                    <label for="date_inscription"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Date d'inscription
                    </label>

                    <input type="date"
                           id="date_inscription"
                           name="date_inscription"
                           value="{{ old('date_inscription', $inscription->date_inscription ?? '') }}"
                           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                    @error('date_inscription')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- STATUT --}}
                <div>
                    <label for="statut"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Statut <span class="text-red-500">*</span>
                    </label>

                    <select id="statut"
                            name="statut"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 outline-none transition focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                        @foreach([
                            'Actif',
                            'En attente',
                            'Annulé'
                        ] as $statut)

                            <option value="{{ $statut }}"
                                @selected(
                                    old(
                                        'statut',
                                        $inscription->statut
                                    ) === $statut
                                )>

                                {{ $statut }}

                            </option>

                        @endforeach

                    </select>

                    @error('statut')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

        </div>


        {{-- ACTIONS --}}
        <div class="flex flex-col-reverse gap-3 bg-gray-50 px-6 py-5 sm:flex-row sm:items-center sm:justify-end">

            <a href="{{ route('inscriptions.show', $inscription) }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>

                Annuler
            </a>


            <button type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M5 13l4 4L19 7"/>
                </svg>

                Enregistrer les modifications
            </button>

        </div>

    </div>

</form>


</div>

@endsection
