@extends('layouts.gestion-ecole')

@section('title', 'Détails enseignant')

@section('page-title', 'Enseignant')

@section('page-description', 'Informations détaillées de l’enseignant')

@section('content')

@php
/*
|--------------------------------------------------------------------------
| Affectations réelles de l'enseignant
|--------------------------------------------------------------------------
| On utilise teacher_subjects :
| - classe
| - matière
| - année scolaire
|
| On n'utilise plus $teacher->matiere.
*/
$affectations = $teacher->teacherSubjects
->filter(function ($affectation) {
return $affectation->subject && $affectation->classRoom;
});


$affectationsParClasse = $affectations
    ->groupBy('class_room_id');


@endphp

{{-- ========================================================= --}}
{{-- EN-TÊTE --}}
{{-- ========================================================= --}}

<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">


<div>

    <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">

        <a href="{{ route('teachers.index') }}"
           class="transition hover:text-blue-600">
            Enseignants
        </a>

        <svg class="h-4 w-4"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 5l7 7-7 7"/>
        </svg>

        <span class="font-medium text-gray-700">
            {{ $teacher->prenom }} {{ $teacher->nom }}
        </span>

    </div>

    <h2 class="text-2xl font-bold tracking-tight text-gray-900">
        Détails de l’enseignant
    </h2>

    <p class="mt-1 text-sm text-gray-500">
        Informations personnelles, professionnelles et matières enseignées.
    </p>

</div>

<div class="flex flex-wrap items-center gap-3">

    <a href="{{ route('teachers.index') }}"
       class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">

        <svg class="h-5 w-5"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 19l-7-7 7-7"/>
        </svg>

        Retour

    </a>

    <a href="{{ route('teachers.edit', $teacher) }}"
       class="inline-flex items-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700">

        <svg class="h-5 w-5"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>

        Modifier

    </a>

</div>


</div>

{{-- ========================================================= --}}
{{-- PROFIL --}}
{{-- ========================================================= --}}

<div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">


{{-- En-tête profil --}}
<div class="bg-blue-950 px-6 py-6 sm:px-8">

    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

        <div class="flex items-center gap-4">

            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/10 text-xl font-bold text-white ring-1 ring-white/20">

                {{ strtoupper(substr($teacher->prenom ?? 'E', 0, 1)) }}{{ strtoupper(substr($teacher->nom ?? 'N', 0, 1)) }}

            </div>

            <div>

                <p class="text-sm font-medium text-blue-200">
                    Enseignant
                </p>

                <h3 class="mt-1 text-xl font-bold text-white">
                    {{ $teacher->prenom }} {{ $teacher->nom }}
                </h3>

                @if($teacher->specialite)

                    <p class="mt-1 text-sm text-blue-200">
                        {{ $teacher->specialite }}
                    </p>

                @endif

            </div>

        </div>

        <div class="inline-flex w-fit items-center gap-2 rounded-full bg-green-500/20 px-4 py-2 text-sm font-semibold text-green-100 ring-1 ring-green-400/30">

            <span class="h-2.5 w-2.5 rounded-full bg-green-400"></span>

            Enseignant

        </div>

    </div>

</div>


<div class="p-5 sm:p-7">

    {{-- ================================================= --}}
    {{-- INFORMATIONS PERSONNELLES --}}
    {{-- ================================================= --}}

    <div>

        <div class="mb-4 flex items-center gap-3">

            <div class="h-7 w-1 rounded-full bg-blue-600"></div>

            <div>
                <h4 class="text-lg font-bold text-gray-900">
                    Informations personnelles
                </h4>

                <p class="text-sm text-gray-500">
                    Informations générales de l’enseignant.
                </p>
            </div>

        </div>


        <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">

            {{-- Nom --}}
            <div class="rounded-xl border border-blue-100 bg-blue-50/60 p-4">

                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">
                    Nom
                </p>

                <p class="mt-1 font-bold text-gray-900">
                    {{ $teacher->nom ?: '—' }}
                </p>

            </div>

            {{-- Prénom --}}
            <div class="rounded-xl border border-green-100 bg-green-50/60 p-4">

                <p class="text-xs font-semibold uppercase tracking-wider text-green-600">
                    Prénom
                </p>

                <p class="mt-1 font-bold text-gray-900">
                    {{ $teacher->prenom ?: '—' }}
                </p>

            </div>

            {{-- Sexe --}}
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">

                <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">
                    Sexe
                </p>

                <p class="mt-1 font-bold text-gray-900">
                    {{ $teacher->sexe ?: '—' }}
                </p>

            </div>

            {{-- Date naissance --}}
            <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm">

                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Date de naissance
                </p>

                <p class="mt-1 font-bold text-gray-900">

                    @if($teacher->date_naissance)
                        {{ \Carbon\Carbon::parse($teacher->date_naissance)->format('d/m/Y') }}
                    @else
                        —
                    @endif

                </p>

            </div>

            {{-- Lieu naissance --}}
            <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm">

                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Lieu de naissance
                </p>

                <p class="mt-1 font-bold text-gray-900">
                    {{ $teacher->lieu_naissance ?: '—' }}
                </p>

            </div>

            {{-- Spécialité --}}
            <div class="rounded-xl border border-blue-100 bg-blue-50/60 p-4">

                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">
                    Spécialité
                </p>

                <p class="mt-1 font-bold text-gray-900">
                    {{ $teacher->specialite ?: '—' }}
                </p>

            </div>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- COORDONNÉES --}}
    {{-- ================================================= --}}

    <div class="mt-7">

        <div class="mb-4 flex items-center gap-3">

            <div class="h-7 w-1 rounded-full bg-green-600"></div>

            <div>
                <h4 class="text-lg font-bold text-gray-900">
                    Coordonnées
                </h4>

                <p class="text-sm text-gray-500">
                    Informations permettant de contacter l’enseignant.
                </p>
            </div>

        </div>


        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">

            {{-- Téléphone --}}
            <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">

                        <svg class="h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 5a2 2 0 012-2h3.28a2 2 0 011.9 1.368l1.07 3.208a2 2 0 01-.5 2.11l-1.27 1.27a16.016 16.016 0 006.344 6.344l1.27-1.27a2 2 0 012.11-.5l3.208 1.07A2 2 0 0122 18.72V22a2 2 0 01-2 2h-1C9.611 24 0 14.389 0 5V4a2 2 0 012-2h1z"/>
                        </svg>

                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                            Téléphone
                        </p>

                        <p class="mt-1 font-bold text-gray-900">
                            {{ $teacher->telephone ?: '—' }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- Email --}}
            <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-100 text-green-600">

                        <svg class="h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2v10a2 2 0 002 2z"/>
                        </svg>

                    </div>

                    <div class="min-w-0">

                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                            Adresse email
                        </p>

                        <p class="mt-1 truncate font-bold text-gray-900">
                            {{ $teacher->email ?: '—' }}
                        </p>

                    </div>

                </div>

            </div>


            {{-- Adresse --}}
            <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm md:col-span-2">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600">

                        <svg class="h-5 w-5"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M17.657 16.657L13.414 21a8 8 0 1111.314 0L13.414 21a2 2 0 01-2.828 0l-4.243-4.343a8 8 0 0111.314 0z"/>
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>

                    </div>

                    <div class="min-w-0">

                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                            Adresse
                        </p>

                        <p class="mt-1 font-bold text-gray-900">
                            {{ $teacher->adresse ?: '—' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- MATIÈRES ET CLASSES ENSEIGNÉES --}}
    {{-- ================================================= --}}

    <div class="mt-7">

        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex items-center gap-3">

                <div class="h-7 w-1 rounded-full bg-green-600"></div>

                <div>

                    <h4 class="text-lg font-bold text-gray-900">
                        Classes et matières enseignées
                    </h4>

                    <p class="text-sm text-gray-500">
                        Matières réellement affectées à cet enseignant.
                    </p>

                </div>

            </div>


            <div class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-sm font-semibold text-blue-700">

                <svg class="h-4 w-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>

                {{ $affectations->count() }}
                {{ $affectations->count() > 1 ? 'affectations' : 'affectation' }}

            </div>

        </div>


        @if($affectationsParClasse->isNotEmpty())

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                @foreach($affectationsParClasse as $classId => $classeAffectations)

                    @php
                        $classe = $classeAffectations->first()->classRoom;
                        $annee = $classeAffectations->first()->schoolYear;
                        $matieres = $classeAffectations
                            ->pluck('subject.nom')
                            ->filter()
                            ->unique()
                            ->values();
                    @endphp

                    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

                        {{-- Classe --}}
                        <div class="flex items-center justify-between border-b border-gray-100 bg-blue-50 px-4 py-3">

                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white">

                                    <svg class="h-5 w-5"
                                         fill="none"
                                         stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M3 7l9-4 9 4v10l-9 4-9-4V7z"/>
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M3 7l9 4 9-4M12 11v10"/>
                                    </svg>

                                </div>

                                <div>

                                    <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">
                                        Classe
                                    </p>

                                    <h5 class="font-bold text-gray-900">
                                        {{ $classe->nom_classe }}
                                    </h5>

                                </div>

                            </div>

                            @if($annee)
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-gray-600 ring-1 ring-gray-200">
                                    {{ $annee->name }}
                                </span>
                            @endif

                        </div>


                        {{-- Niveau / filière --}}
                        @if($classe->niveau || $classe->filiere)

                            <div class="border-b border-gray-100 px-4 py-3">

                                <div class="flex flex-wrap gap-2">

                                    @if($classe->niveau)

                                        <span class="rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">
                                            Niveau : {{ $classe->niveau }}
                                        </span>

                                    @endif

                                    @if($classe->filiere)

                                        <span class="rounded-lg bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700">
                                            Filière : {{ $classe->filiere }}
                                        </span>

                                    @endif

                                </div>

                            </div>

                        @endif


                        {{-- Matières --}}
                        <div class="p-4">

                            <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-400">
                                Matières enseignées
                            </p>

                            <div class="space-y-2">

                                @foreach($matieres as $matiere)

                                    <div class="flex items-center gap-3 rounded-xl border border-green-100 bg-green-50/60 px-3 py-2.5">

                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-green-600 text-white">

                                            <svg class="h-4 w-4"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253"/>
                                            </svg>

                                        </div>

                                        <span class="font-semibold text-gray-900">
                                            {{ $matiere }}
                                        </span>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center">

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-gray-200 text-gray-500">

                    <svg class="h-6 w-6"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 6v12m6-6H6"/>
                    </svg>

                </div>

                <h5 class="mt-3 font-bold text-gray-900">
                    Aucune matière affectée
                </h5>

                <p class="mt-1 text-sm text-gray-500">
                    Cet enseignant n’a encore aucune matière affectée à une classe.
                </p>

                <a href="{{ route('teachers.edit', $teacher) }}"
                   class="mt-4 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">

                    <svg class="h-4 w-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11v-5"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>

                    Affecter des matières

                </a>

            </div>

        @endif

    </div>


    {{-- ================================================= --}}
    {{-- INFORMATIONS SYSTÈME --}}
    {{-- ================================================= --}}

    <div class="mt-7">

        <div class="mb-4 flex items-center gap-3">

            <div class="h-7 w-1 rounded-full bg-gray-400"></div>

            <h4 class="text-lg font-bold text-gray-900">
                Informations système
            </h4>

        </div>


        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">

            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">

                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Identifiant
                </p>

                <p class="mt-1 text-lg font-bold text-gray-900">
                    #{{ $teacher->id }}
                </p>

            </div>


            <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">

                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Enseignant ajouté le
                </p>

                <p class="mt-1 text-lg font-bold text-gray-900">
                    {{ $teacher->created_at ? $teacher->created_at->format('d/m/Y') : '—' }}
                </p>

            </div>

        </div>

    </div>

</div>


</div>

{{-- ========================================================= --}}
{{-- ACTIONS --}}
{{-- ========================================================= --}}

<div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">


<a href="{{ route('teachers.index') }}"
   class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">

    <svg class="h-5 w-5"
         fill="none"
         stroke="currentColor"
         viewBox="0 0 24 24">
        <path stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M15 19l-7-7 7-7"/>
    </svg>

    Retour aux enseignants

</a>


<div class="flex flex-col gap-3 sm:flex-row">

    <a href="{{ route('teachers.edit', $teacher) }}"
       class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">

        <svg class="h-5 w-5"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11v-5"/>
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>

        Modifier l’enseignant

    </a>


    <form action="{{ route('teachers.destroy', $teacher) }}"
          method="POST"
          onsubmit="return confirm('Voulez-vous vraiment supprimer cet enseignant ?');">

        @csrf
        @method('DELETE')

        <button type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-semibold text-red-700 transition hover:bg-red-100 sm:w-auto">

            <svg class="h-5 w-5"
                   fill="none"
                   stroke="currentColor"
                   viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>

            Supprimer

        </button>

    </form>

</div>


</div>

@endsection
