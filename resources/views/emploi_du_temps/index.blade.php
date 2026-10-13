@extends(auth()->user()->role === 'parent'
    ? 'layouts.parent'
    : 'layouts.gestion-ecole')

@section('title', 'Emploi du temps')

@section('content')
@php
    $totalCours = $emplois->count();
    $totalClasses = $emplois->pluck('class_room_id')->filter()->unique()->count();
    $totalEnseignants = $emplois->pluck('teacher_id')->filter()->unique()->count();
    $totalSalles = $emplois->pluck('salle_id')->filter()->unique()->count();

    $emploisParJour = $emplois->groupBy('jour');

    $hasFilters = request()->filled('school_year_id')
        || request()->filled('class_room_id')
        || request()->filled('jour');
@endphp

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="px-6 py-6 lg:px-8">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-8 w-8"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 7V3m8 4V3M4 9h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 13h2m4 0h2M8 17h2m4 0h2"/>
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                            Emploi du temps
                        </h1>

                        <p class="mt-1 text-sm text-slate-500">
                            Organisez les cours par classe, matière, enseignant et salle.
                        </p>

                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                {{ $establishment->nom }}
                            </span>

                            @if($schoolYear)
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                    Année scolaire : {{ $schoolYear->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <a href="{{ route('emploi-du-temps.create') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajouter un horaire
                </a>

            </div>
        </div>
    </div>


    {{-- STATISTIQUES --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Cours planifiés</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalCours }}</p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8 7V3m8 4V3M4 9h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Classes</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalClasses }}</p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H6a2 2 0 01-2-2v-1a5 5 0 015-5h2a5 5 0 015 5v1a2 2 0 01-2 2z"/>
                        <circle cx="10" cy="7" r="3"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16 3.5a3 3 0 010 5.8"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Enseignants</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalEnseignants }}</p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-50 text-violet-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <circle cx="12" cy="7" r="3"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 21a7 7 0 0114 0"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Salles utilisées</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalSalles }}</p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 21h18M5 21V6l7-3 7 3v15M9 21v-4h6v4M8 9h1m6 0h1m-8 3h1m6 0h1"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>


    {{-- FILTRES --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-100 text-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 5h18M6 12h12M10 19h4"/>
                    </svg>
                </div>

                <div>
                    <h2 class="text-base font-bold text-slate-900">
                        Filtrer l'emploi du temps
                    </h2>
                    <p class="text-sm text-slate-500">
                        Sélectionnez une année, une classe ou une journée.
                    </p>
                </div>
            </div>
        </div>

        <form method="GET"
              action="{{ route('emploi-du-temps.index') }}"
              class="grid grid-cols-1 gap-4 p-6 md:grid-cols-2 xl:grid-cols-4">

            <div>
                <label for="school_year_id"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Année scolaire
                </label>

                <select id="school_year_id"
                        name="school_year_id"
                        class="block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-blue-600 focus:ring-blue-600">
                    @foreach($schoolYears as $year)
                        <option value="{{ $year->id }}"
                            @selected((string) $selectedSchoolYearId === (string) $year->id)>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="class_room_id"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Classe
                </label>

                <select id="class_room_id"
                        name="class_room_id"
                        class="block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-blue-600 focus:ring-blue-600">

                    <option value="">Toutes les classes</option>

                    @foreach($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}"
                            @selected((string) $selectedClassRoomId === (string) $classRoom->id)>
                            {{ $classRoom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="jour"
                       class="mb-2 block text-sm font-semibold text-slate-700">
                    Jour
                </label>

                <select id="jour"
                        name="jour"
                        class="block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm focus:border-blue-600 focus:ring-blue-600">

                    <option value="">Tous les jours</option>

                    @foreach($jours as $jour)
                        <option value="{{ $jour }}"
                            @selected($selectedJour === $jour)>
                            {{ $jour }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                        class="inline-flex min-h-[46px] flex-1 items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <circle cx="11" cy="11" r="7"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="m20 20-4-4"/>
                    </svg>
                    Filtrer
                </button>

                @if($hasFilters)
                    <a href="{{ route('emploi-du-temps.index') }}"
                       class="inline-flex min-h-[46px] items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Réinitialiser
                    </a>
                @endif
            </div>

        </form>
    </div>


    {{-- EMPLOI DU TEMPS --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-lg font-bold text-slate-900">
                    Planning des cours
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    @if($selectedClass)
                        Emploi du temps de la classe
                        <span class="font-semibold text-slate-700">
                            {{ $selectedClass->name }}
                        </span>
                    @else
                        Vue générale des cours planifiés.
                    @endif
                </p>
            </div>

            <div class="text-sm text-slate-500">
                <span class="font-semibold text-slate-800">{{ $totalCours }}</span>
                cours affiché{{ $totalCours > 1 ? 's' : '' }}
            </div>
        </div>


        @if($emplois->isEmpty())

            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-8 w-8"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.7">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 7V3m8 4V3M4 9h16M5 5h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 13h2m4 0h2M8 17h2m4 0h2"/>
                    </svg>
                </div>

                <h3 class="mt-5 text-lg font-bold text-slate-900">
                    Aucun horaire enregistré
                </h3>

                <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                    Aucun cours ne correspond aux critères sélectionnés pour cette année scolaire.
                </p>

                <a href="{{ route('emploi-du-temps.create') }}"
                   class="mt-6 inline-flex items-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajouter le premier horaire
                </a>
            </div>

        @else

            {{-- Grille responsive --}}
            <div class="overflow-x-auto">
                <div class="grid min-w-[1500px] grid-cols-7 divide-x divide-slate-200">

                    @foreach($jours as $jour)

                        @php
                            $coursDuJour = $emploisParJour->get($jour, collect());
                        @endphp

                        <div class="min-h-[560px] bg-slate-50/50">

                            {{-- JOUR --}}
                            <div class="sticky top-0 z-10 border-b border-slate-200 bg-white px-4 py-4">
                                <div class="flex items-center justify-between gap-2">
                                    <h3 class="text-sm font-bold text-slate-900">
                                        {{ $jour }}
                                    </h3>

                                    <span class="inline-flex min-w-7 items-center justify-center rounded-full bg-blue-50 px-2 py-1 text-xs font-bold text-blue-700">
                                        {{ $coursDuJour->count() }}
                                    </span>
                                </div>
                            </div>

                            {{-- COURS --}}
                            <div class="space-y-3 p-3">

                                @forelse($coursDuJour as $emploi)

                                    <div class="group rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:border-blue-200 hover:shadow-md">

                                        {{-- HORAIRE --}}
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-bold text-blue-700">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="1.8">
                                                    <circle cx="12" cy="12" r="8"/>
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M12 8v4l2.5 2"/>
                                                </svg>
                                                {{ \Illuminate\Support\Str::of($emploi->heure_debut)->substr(0, 5) }}
                                                -
                                                {{ \Illuminate\Support\Str::of($emploi->heure_fin)->substr(0, 5) }}
                                            </span>
                                        </div>


                                        {{-- MATIÈRE --}}
                                        <div class="mt-4">
                                            <h4 class="text-sm font-bold leading-5 text-slate-900">
                                                {{ $emploi->subject?->name ?? 'Matière non définie' }}
                                            </h4>
                                        </div>


                                        {{-- CLASSE --}}
                                        @if(!$selectedClass)
                                            <div class="mt-3 flex items-center gap-2 text-xs text-slate-600">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4 shrink-0 text-blue-600"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="1.8">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H6a2 2 0 01-2-2v-1a5 5 0 015-5h2a5 5 0 015 5v1a2 2 0 01-2 2z"/>
                                                    <circle cx="10" cy="7" r="3"/>
                                                </svg>

                                                <span class="font-semibold">
                                                    {{ $emploi->classRoom?->name ?? 'Classe non définie' }}
                                                </span>
                                            </div>
                                        @endif


                                        {{-- ENSEIGNANT --}}
                                        <div class="mt-2 flex items-center gap-2 text-xs text-slate-600">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4 shrink-0 text-emerald-600"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.8">
                                                <circle cx="12" cy="7" r="3"/>
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M5 21a7 7 0 0114 0"/>
                                            </svg>

                                            <span class="truncate">
                                                @if($emploi->teacher)
                                                    {{ $emploi->teacher->nom }}
                                                    {{ $emploi->teacher->prenom }}
                                                @else
                                                    Enseignant non défini
                                                @endif
                                            </span>
                                        </div>


                                        {{-- SALLE --}}
                                        <div class="mt-2 flex items-center gap-2 text-xs text-slate-600">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-4 w-4 shrink-0 text-amber-600"
                                                 fill="none"
                                                 viewBox="0 0 24 24"
                                                 stroke="currentColor"
                                                 stroke-width="1.8">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M3 21h18M5 21V6l7-3 7 3v15M9 21v-4h6v4M8 9h1m6 0h1m-8 3h1m6 0h1"/>
                                            </svg>

                                            <span class="truncate">
                                                {{ $emploi->salle?->nom ?? $emploi->salle?->name ?? 'Salle non définie' }}
                                            </span>
                                        </div>


                                        {{-- ACTIONS --}}
                                        <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-100 pt-3">

                                            <a href="{{ route('emploi-du-temps.edit', $emploi) }}"
                                               title="Modifier"
                                               class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-blue-200 bg-blue-50 text-blue-700 transition hover:bg-blue-100">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-5 w-5"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="1.8">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652l-1.688 1.687M16.862 4.487L7.5 13.85l-.75 3.4 3.4-.75 9.363-9.363M16.862 4.487L19.5 7.125"/>
                                                </svg>
                                            </a>

                                            <form action="{{ route('emploi-du-temps.destroy', $emploi) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cet horaire ?');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        title="Supprimer"
                                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 transition hover:bg-red-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         class="h-5 w-5"
                                                         fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke="currentColor"
                                                         stroke-width="1.8">
                                                        <path stroke-linecap="round"
                                                              stroke-linejoin="round"
                                                              d="M6 7h12m-9 0V5h6v2m-7 4v6m4-6v6m5-8v10a2 2 0 01-2 2H9a2 2 0 01-2-2V9"/>
                                                    </svg>
                                                </button>
                                            </form>

                                        </div>

                                    </div>

                                @empty

                                    <div class="rounded-xl border border-dashed border-slate-200 bg-white/70 px-3 py-8 text-center">
                                        <p class="text-xs font-medium text-slate-400">
                                            Aucun cours
                                        </p>
                                    </div>

                                @endforelse

                            </div>
                        </div>

                    @endforeach

                </div>
            </div>

        @endif
    </div>

</div>
@endsection
