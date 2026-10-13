@extends(auth()->user()->role === 'parent'
    ? 'layouts.parent'
    : 'layouts.gestion-ecole')

@if(auth()->user()->role === 'parent')

@section('title', 'Mes bulletins')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-5xl">

        {{-- En-tête --}}
        <div class="mb-8">
            <a
                href="{{ route('parent.dashboard') }}"
                class="mb-4 inline-flex items-center gap-2 text-sm font-semibold text-slate-600 transition hover:text-[#1e3a8a]"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour à l'espace famille
            </a>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-[#16a34a]">
                            Espace famille
                        </p>

                        <h1 class="mt-1 text-2xl font-bold text-[#1e3a8a] sm:text-3xl">
                            Mes bulletins
                        </h1>

                        <p class="mt-2 text-sm text-slate-600 sm:text-base">
                            Consultez les bulletins scolaires de vos enfants.
                        </p>
                    </div>

                    @if(isset($selectedSchoolYear))
                        <div class="rounded-xl bg-slate-100 px-4 py-3 text-sm">
                            <p class="text-xs font-medium text-slate-500">
                                Année scolaire
                            </p>
                            <p class="mt-1 font-bold text-slate-800">
                                {{ $selectedSchoolYear->name ?? $selectedSchoolYear->libelle ?? 'Année scolaire' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Liste des enfants --}}
        @php
            /*
             * Le contrôleur applique déjà la sécurité parent_student.
             * Cette vue ne reçoit donc que les élèves autorisés.
             */
            $parentStudents = collect($students ?? []);

            if ($parentStudents->isEmpty() && isset($student)) {
                $parentStudents = collect([$student]);
            }
        @endphp

        @if($parentStudents->isNotEmpty())

            <div class="grid gap-5 md:grid-cols-2">

                @foreach($parentStudents as $item)

                    @php
                        $child = $item->student ?? $item;

                        $childId = $child->id ?? null;
                        $childName = $child->nom_complet
                            ?? $child->name
                            ?? trim(($child->prenom ?? '') . ' ' . ($child->nom ?? ''))
                            ?? 'Élève';

                        $className = $item->classRoom->name
                            ?? $item->classRoom->nom
                            ?? $child->classRoom->name
                            ?? $child->class_room
                            ?? 'Classe non renseignée';
                    @endphp

                    @if($childId)
                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                            <div class="border-b border-slate-100 bg-slate-50 px-5 py-4">
                                <div class="flex items-start gap-4">

                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#1e3a8a] text-lg font-bold text-white">
                                        {{ strtoupper(substr($childName, 0, 1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <h2 class="truncate text-lg font-bold text-slate-800">
                                            {{ $childName }}
                                        </h2>

                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $className }}
                                        </p>

                                        @if(!empty($child->matricule))
                                            <p class="mt-1 text-xs text-slate-400">
                                                Matricule : {{ $child->matricule }}
                                            </p>
                                        @endif
                                    </div>

                                </div>
                            </div>

                            <div class="p-5">

                                <div class="mb-5 rounded-xl border border-blue-100 bg-blue-50 p-4">
                                    <div class="flex items-start gap-3">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="mt-0.5 h-5 w-5 shrink-0 text-[#1e3a8a]"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.414 4.414A2 2 0 0119 9v10a2 2 0 01-2 2z"/>
                                        </svg>

                                        <div>
                                            <p class="font-semibold text-[#1e3a8a]">
                                                Bulletin scolaire
                                            </p>
                                            <p class="mt-1 text-sm text-slate-600">
                                                Consultez les résultats scolaires de cet enfant.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <a
                                    href="{{ route('bulletins.show', $childId) }}"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#1e3a8a] px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-900"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>

                                    Voir le bulletin
                                </a>

                            </div>
                        </div>
                    @endif

                @endforeach

            </div>

        @else

            <div class="rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-8 w-8 text-slate-400"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a2 2 0 011.414.586l4.414 4.414A2 2 0 0119 9v10a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <h2 class="mt-5 text-lg font-bold text-slate-800">
                    Aucun bulletin disponible
                </h2>

                <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                    Aucun enfant associé à votre compte ne possède actuellement
                    de bulletin consultable pour cette année scolaire.
                </p>

            </div>

        @endif

    </div>
</div>
@endsection

@else

@section('title', 'Bulletins')

@section('content')
<div class="space-y-6">

    {{-- En-tête --}}
    <div class="rounded-2xl border border-blue-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-blue-900">
                    Bulletins scolaires
                </h1>

                <p class="mt-1 text-sm text-gray-600">
                    Génération du bulletin individuel d'un étudiant
                </p>

                @if($establishment)
                    <p class="mt-2 text-sm font-semibold text-green-700">
                        {{ $establishment->nom }}
                    </p>
                @endif
            </div>

            <a
                href="{{ route('resultats.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl
                       border border-blue-800 bg-blue-900 px-5 py-3
                       text-sm font-semibold text-white transition
                       hover:bg-blue-800"
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
                        d="M9 17v-2m3 2v-4m3 4v-6m2 9H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2z"
                    />
                </svg>

                Résultats
            </a>
        </div>
    </div>

    {{-- Sélection --}}
    <div class="rounded-2xl border border-blue-200 bg-white p-6 shadow-sm">

        <div class="mb-6">
            <h2 class="text-lg font-bold text-blue-900">
                Sélectionner un étudiant
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Choisissez l'année scolaire, la classe puis l'étudiant.
            </p>
        </div>

        <form
            method="GET"
            action="{{ route('bulletins.index') }}"
            class="grid grid-cols-1 gap-5 md:grid-cols-3"
        >

            {{-- Année scolaire --}}
            <div>
                <label
                    for="school_year_id"
                    class="mb-2 block text-sm font-semibold text-gray-700"
                >
                    Année scolaire
                </label>

                <select
                    id="school_year_id"
                    name="school_year_id"
                    onchange="this.form.submit()"
                    class="w-full rounded-xl border border-gray-300 bg-white
                           px-4 py-3 text-sm text-gray-800 shadow-sm
                           focus:border-blue-600 focus:outline-none
                           focus:ring-2 focus:ring-blue-200"
                >
                    @forelse($schoolYears as $schoolYear)
                        <option
                            value="{{ $schoolYear->id }}"
                            @selected(
                                $selectedSchoolYear &&
                                $selectedSchoolYear->id == $schoolYear->id
                            )
                        >
                            {{ $schoolYear->name }}
                            @if($schoolYear->is_active)
                                — Active
                            @endif
                        </option>
                    @empty
                        <option value="">
                            Aucune année scolaire disponible
                        </option>
                    @endforelse
                </select>
            </div>

            {{-- Classe --}}
            <div>
                <label
                    for="class_room_id"
                    class="mb-2 block text-sm font-semibold text-gray-700"
                >
                    Classe
                </label>

                <select
                    id="class_room_id"
                    name="class_room_id"
                    onchange="this.form.submit()"
                    class="w-full rounded-xl border border-gray-300 bg-white
                           px-4 py-3 text-sm text-gray-800 shadow-sm
                           focus:border-blue-600 focus:outline-none
                           focus:ring-2 focus:ring-blue-200"
                    {{ $classes->isEmpty() ? 'disabled' : '' }}
                >
                    <option value="">
                        -- Sélectionner une classe --
                    </option>

                    @foreach($classes as $class)
                        <option
                            value="{{ $class->id }}"
                            @selected(
                                (int) $selectedClassId ===
                                (int) $class->id
                            )
                        >
                            {{ $class->nom_classe }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Étudiant --}}
            <div>
                <label
                    for="student_id"
                    class="mb-2 block text-sm font-semibold text-gray-700"
                >
                    Étudiant
                </label>

                <select
                    id="student_id"
                    name="student_id"
                    class="w-full rounded-xl border border-gray-300 bg-white
                           px-4 py-3 text-sm text-gray-800 shadow-sm
                           focus:border-blue-600 focus:outline-none
                           focus:ring-2 focus:ring-blue-200"
                    {{ $students->isEmpty() ? 'disabled' : '' }}
                >
                    <option value="">
                        -- Sélectionner un étudiant --
                    </option>

                    @foreach($students as $student)
                        <option
                            value="{{ $student->id }}"
                            @selected(
                                (int) $selectedStudentId ===
                                (int) $student->id
                            )
                        >
                            {{ $student->matricule }}
                            — {{ $student->nom }}
                            {{ $student->prenom }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Bouton --}}
            <div class="md:col-span-3">
                <button
                    type="button"
                    onclick="
                        const studentId = document.getElementById('student_id').value;
                        const schoolYearId = document.getElementById('school_year_id').value;
                        const classRoomId = document.getElementById('class_room_id').value;

                        if (!studentId) {
                            alert('Veuillez sélectionner un étudiant.');
                            return;
                        }

                        const url =
                            '{{ url('/bulletins') }}/' +
                            encodeURIComponent(studentId) +
                            '?school_year_id=' +
                            encodeURIComponent(schoolYearId) +
                            '&class_room_id=' +
                            encodeURIComponent(classRoomId);

                        window.location.href = url;
                    "
                    class="inline-flex items-center justify-center gap-2 rounded-xl
                           bg-green-600 px-6 py-3 text-sm font-bold text-white
                           shadow-sm transition hover:bg-green-700"
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
                            d="M15 12H3m12 0l-4-4m4 4l-4 4m6-9h2a2 2 0 012 2v10a2 2 0 01-2 2h-2"
                        />
                    </svg>

                    Générer le bulletin
                </button>
            </div>

        </form>
    </div>

    {{-- Information --}}
    @if($selectedSchoolYear && $selectedClassId && $students->isNotEmpty())
        <div class="rounded-2xl border border-green-200 bg-green-50 p-5">
            <div class="flex items-start gap-3">
                <svg
                    class="mt-0.5 h-6 w-6 shrink-0 text-green-700"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                </svg>

                <div>
                    <p class="font-bold text-green-800">
                        {{ $students->count() }} étudiant(s) disponible(s)
                    </p>

                    <p class="mt-1 text-sm text-green-700">
                        Sélectionnez un étudiant puis cliquez sur
                        « Générer le bulletin ».
                    </p>
                </div>
            </div>
        </div>
    @elseif($selectedSchoolYear && $selectedClassId)
        <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-5">
            <p class="font-semibold text-yellow-800">
                Aucun étudiant inscrit dans cette classe pour cette année scolaire.
            </p>
        </div>
    @endif

</div>
@endsection

@endif
