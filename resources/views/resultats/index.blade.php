@extends('layouts.gestion-ecole')

@section('title', 'Résultats')

@section('content')

<div class="space-y-6">

    {{-- EN-TÊTE --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-2xl font-bold text-[#1e3a8a]">
                    Résultats scolaires
                </h1>

                <p class="mt-1 text-gray-600">
                    {{ $establishment->nom }}
                </p>
            </div>

            <div class="text-sm text-gray-500">
                Calcul automatique à partir des notes enregistrées
            </div>

        </div>
    </div>


    {{-- FILTRES --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

        <form
            method="GET"
            action="{{ route('resultats.index') }}"
            class="grid grid-cols-1 md:grid-cols-3 gap-4"
        >

            {{-- ANNÉE SCOLAIRE --}}
            <div>
                <label
                    for="school_year_id"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Année scolaire
                </label>

                <select
                    id="school_year_id"
                    name="school_year_id"
                    onchange="this.form.submit()"
                    class="w-full rounded-lg border-gray-300 focus:border-[#1e3a8a] focus:ring-[#1e3a8a]"
                >
                    @foreach($schoolYears as $schoolYear)
                        <option
                            value="{{ $schoolYear->id }}"
                            @selected(
                                $selectedSchoolYear &&
                                $selectedSchoolYear->id === $schoolYear->id
                            )
                        >
                            {{ $schoolYear->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- CLASSE --}}
            <div>
                <label
                    for="class_room_id"
                    class="block text-sm font-semibold text-gray-700 mb-2"
                >
                    Classe
                </label>

                <select
                    id="class_room_id"
                    name="class_room_id"
                    class="w-full rounded-lg border-gray-300 focus:border-[#1e3a8a] focus:ring-[#1e3a8a]"
                    @disabled($classes->isEmpty())
                >
                    <option value="">
                        -- Sélectionner une classe --
                    </option>

                    @foreach($classes as $class)
                        <option
                            value="{{ $class->id }}"
                            @selected(
                                $selectedClass &&
                                $selectedClass->id === $class->id
                            )
                        >
                            {{ $class->nom_classe }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- BOUTON --}}
            <div class="flex items-end">
                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-[#1e3a8a] px-5 py-3 text-white font-semibold hover:bg-blue-900 transition"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 9H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2z"
                        />
                    </svg>

                    Afficher les résultats
                </button>
            </div>

        </form>
    </div>


    {{-- MESSAGE SI PAS DE CLASSE --}}
    @if(!$selectedClass)

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start gap-3">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-6 h-6 text-[#1e3a8a] flex-shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"
                    />
                </svg>

                <div>
                    <h3 class="font-semibold text-[#1e3a8a]">
                        Sélectionnez une classe
                    </h3>

                    <p class="mt-1 text-sm text-gray-600">
                        Choisissez une classe pour afficher les résultats
                        des étudiants inscrits pour l'année scolaire sélectionnée.
                    </p>
                </div>

            </div>
        </div>

    @else

        {{-- INFORMATIONS CLASSE --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <div>
                    <h2 class="text-xl font-bold text-gray-800">
                        {{ $selectedClass->nom_classe }}
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Année scolaire :
                        <span class="font-semibold">
                            {{ $selectedSchoolYear->name }}
                        </span>
                    </p>
                </div>

                <div class="text-sm text-gray-600">
                    {{ $studentsResults->count() }}
                    étudiant(s)
                </div>

            </div>

        </div>


        {{-- TABLEAU DES RÉSULTATS --}}
        @if($studentsResults->isEmpty())

            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                <p class="text-yellow-800">
                    Aucun étudiant inscrit ou aucune donnée de résultat
                    disponible pour cette classe et cette année scolaire.
                </p>
            </div>

        @else

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">
                                    Rang
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">
                                    Matricule
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 uppercase">
                                    Étudiant
                                </th>

                                @php
                                    $subjects = collect();

                                    foreach ($studentsResults as $studentResult) {
                                        foreach ($studentResult['subject_results'] as $subjectResult) {
                                            if (!$subjects->contains('subject.id', $subjectResult['subject']->id)) {
                                                $subjects->push($subjectResult);
                                            }
                                        }
                                    }
                                @endphp

                                @foreach($subjects as $subjectResult)

                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase whitespace-nowrap">
                                        {{ $subjectResult['subject']->nom }}
                                    </th>

                                @endforeach

                                <th class="px-4 py-3 text-center text-xs font-bold text-[#1e3a8a] uppercase whitespace-nowrap">
                                    Moyenne générale
                                </th>

                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 uppercase">
                                    Mention
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-200">

                            @foreach($studentsResults as $result)

                                @php
                                    $average = $result['general_average'];

                                    $mention = null;

                                    if ($average !== null) {
                                        if ($average >= 16) {
                                            $mention = 'Très bien';
                                        } elseif ($average >= 14) {
                                            $mention = 'Bien';
                                        } elseif ($average >= 12) {
                                            $mention = 'Assez bien';
                                        } elseif ($average >= 10) {
                                            $mention = 'Passable';
                                        } else {
                                            $mention = 'Insuffisant';
                                        }
                                    }
                                @endphp

                                <tr class="hover:bg-gray-50">

                                    <td class="px-4 py-4 text-sm font-bold text-[#1e3a8a]">
                                        @if($result['rank'])
                                            {{ $result['rank'] }}
                                        @else
                                            —
                                        @endif
                                    </td>


                                    <td class="px-4 py-4 text-sm text-gray-700 whitespace-nowrap">
                                        {{ $result['student']->matricule ?? '—' }}
                                    </td>


                                    <td class="px-4 py-4 text-sm font-semibold text-gray-800 whitespace-nowrap">
                                        {{ $result['student']->nom }}
                                        {{ $result['student']->prenom }}
                                    </td>


                                    @foreach($subjects as $subjectResult)

                                        @php
                                            $studentSubject = collect($result['subject_results'])
                                                ->first(
                                                    fn ($item) =>
                                                        $item['subject']->id ===
                                                        $subjectResult['subject']->id
                                                );
                                        @endphp

                                        <td class="px-4 py-4 text-center text-sm">

                                            @if($studentSubject && $studentSubject['average'] !== null)

                                                <span class="font-semibold">
                                                    {{ number_format(
                                                        $studentSubject['average'],
                                                        2,
                                                        ',',
                                                        ' '
                                                    ) }}
                                                </span>

                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif

                                        </td>

                                    @endforeach


                                    <td class="px-4 py-4 text-center whitespace-nowrap">

                                        @if($average !== null)

                                            <span class="inline-flex items-center rounded-lg bg-blue-50 px-3 py-1.5 text-sm font-bold text-[#1e3a8a]">
                                                {{ number_format(
                                                    $average,
                                                    2,
                                                    ',',
                                                    ' '
                                                ) }}
                                            </span>

                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif

                                    </td>


                                    <td class="px-4 py-4 text-center text-sm whitespace-nowrap">

                                        @if($mention)

                                            <span class="font-semibold text-green-700">
                                                {{ $mention }}
                                            </span>

                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        @endif

    @endif

</div>

@endsection
