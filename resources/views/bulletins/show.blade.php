@extends(auth()->user()->role === 'parent'
    ? 'layouts.parent'
    : 'layouts.gestion-ecole')

@section('title', 'Bulletin individuel')

@section('content')

<div class="space-y-6">

    {{-- Actions --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between print:hidden">

        <a
            href="{{ auth()->user()->role === 'parent'
                ? route('bulletins.index', ['school_year_id' => $schoolYear->id])
                : route('bulletins.index', [
                    'school_year_id' => $schoolYear->id,
                    'class_room_id' => $classRoom->id,
                ]) }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl
                   border border-blue-800 bg-white px-5 py-3
                   text-sm font-semibold text-blue-900
                   transition hover:bg-blue-50"
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
                    d="M15 19l-7-7 7-7"
                />
            </svg>

            {{ auth()->user()->role === 'parent'
                ? 'Retour à mes bulletins'
                : 'Retour aux bulletins' }}
        </a>

        <button
            type="button"
            onclick="window.print()"
            class="inline-flex items-center justify-center gap-2 rounded-xl
                   bg-green-600 px-5 py-3
                   text-sm font-bold text-white
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
                    d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z"
                />
            </svg>

            Imprimer le bulletin
        </button>

    </div>

    {{-- Bulletin --}}
    <div
        id="bulletin"
        class="mx-auto max-w-5xl rounded-2xl border border-gray-300
               bg-white p-6 shadow-sm sm:p-8"
    >

        {{-- En-tête établissement --}}
        <div class="border-b-2 border-blue-900 pb-6">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex items-center gap-4">

                    @if(file_exists(public_path('images/Optimaschool.png')))
                        <img
                            src="{{ asset('images/Optimaschool.png') }}"
                            alt="OptimaSchool"
                            class="h-20 w-20 object-contain"
                        >
                    @elseif(file_exists(public_path('images/optimaschool-logo.png')))
                        <img
                            src="{{ asset('images/optimaschool-logo.png') }}"
                            alt="OptimaSchool"
                            class="h-20 w-20 object-contain"
                        >
                    @endif

                    <div>
                        <h1 class="text-2xl font-extrabold text-blue-900">
                            {{ $establishment->nom }}
                        </h1>

                        @if($establishment->adresse)
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $establishment->adresse }}
                            </p>
                        @endif

                        @if($establishment->telephone)
                            <p class="text-sm text-gray-600">
                                Tél : {{ $establishment->telephone }}
                            </p>
                        @endif

                        @if($establishment->email)
                            <p class="text-sm text-gray-600">
                                {{ $establishment->email }}
                            </p>
                        @endif
                    </div>

                </div>

                <div class="text-left sm:text-right">
                    <h2 class="text-xl font-extrabold text-green-700">
                        BULLETIN SCOLAIRE
                    </h2>

                    <p class="mt-2 text-sm font-semibold text-gray-700">
                        Année scolaire :
                        {{ $schoolYear->name }}
                    </p>
                </div>

            </div>
        </div>

        {{-- Informations étudiant --}}
        <div class="mt-6 rounded-xl border border-blue-200 bg-blue-50 p-5">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Étudiant
                    </p>

                    <p class="mt-1 text-lg font-extrabold text-blue-900">
                        {{ $student->nom }}
                        {{ $student->prenom }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Matricule
                    </p>

                    <p class="mt-1 text-lg font-bold text-gray-800">
                        {{ $student->matricule }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Classe
                    </p>

                    <p class="mt-1 text-base font-bold text-gray-800">
                        {{ $classRoom->nom_classe }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Niveau
                    </p>

                    <p class="mt-1 text-base font-bold text-gray-800">
                        {{ $classRoom->niveau ?: '—' }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Tableau des matières --}}
        <div class="mt-6 overflow-hidden rounded-xl border border-gray-300">

            <table class="w-full border-collapse text-sm">

                <thead>
                    <tr class="bg-blue-900 text-white">

                        <th class="border border-blue-800 px-4 py-3 text-left font-bold">
                            Matière
                        </th>

                        <th class="border border-blue-800 px-4 py-3 text-center font-bold">
                            Notes
                        </th>

                        <th class="border border-blue-800 px-4 py-3 text-center font-bold">
                            Coefficient
                        </th>

                        <th class="border border-blue-800 px-4 py-3 text-center font-bold">
                            Moyenne
                        </th>

                    </tr>
                </thead>

                <tbody>

                    @forelse($subjectResults as $result)

                        <tr class="border-b border-gray-200">

                            <td class="border border-gray-200 px-4 py-3 font-semibold text-gray-800">
                                {{ $result['subject']->nom }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3 text-center text-gray-700">
                                {{ $result['notes_count'] }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3 text-center text-gray-700">
                                {{ number_format($result['coefficient'], 2, ',', ' ') }}
                            </td>

                            <td class="border border-gray-200 px-4 py-3 text-center">

                                @if($result['average'] !== null)

                                    <span class="font-extrabold text-blue-900">
                                        {{ number_format($result['average'], 2, ',', ' ') }}
                                        / 20
                                    </span>

                                @else

                                    <span class="font-semibold text-gray-400">
                                        —
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="4"
                                class="px-4 py-8 text-center text-gray-500"
                            >
                                Aucune matière disponible pour cette classe.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Synthèse --}}
        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="rounded-xl border border-blue-200 bg-blue-50 p-5 text-center">
                <p class="text-sm font-semibold text-gray-600">
                    Moyenne générale
                </p>

                <p class="mt-2 text-3xl font-extrabold text-blue-900">

                    @if($generalAverage !== null)
                        {{ number_format($generalAverage, 2, ',', ' ') }}
                        <span class="text-lg">/ 20</span>
                    @else
                        —
                    @endif

                </p>
            </div>

            <div class="rounded-xl border border-green-200 bg-green-50 p-5 text-center">
                <p class="text-sm font-semibold text-gray-600">
                    Rang
                </p>

                <p class="mt-2 text-3xl font-extrabold text-green-700">

                    @if($rank !== null)
                        {{ $rank }}
                        <span class="text-lg">
                            / {{ $classSize }}
                        </span>
                    @else
                        —
                    @endif

                </p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 text-center">
                <p class="text-sm font-semibold text-gray-600">
                    Mention
                </p>

                <p class="mt-2 text-xl font-extrabold text-gray-800">
                    {{ $mention ?: '—' }}
                </p>
            </div>

        </div>

        {{-- Appréciation --}}
        <div class="mt-6 rounded-xl border border-gray-300 p-5">

            <h3 class="text-base font-bold text-blue-900">
                Appréciation générale
            </h3>

            <div class="mt-4 min-h-20 border-b border-dashed border-gray-400">
            </div>

        </div>

        {{-- Signatures --}}
        <div class="mt-10 grid grid-cols-1 gap-10 text-center sm:grid-cols-2">

            <div>
                <p class="font-bold text-gray-800">
                    Signature de l'enseignant
                </p>

                <div class="mt-12 border-b border-gray-400">
                </div>
            </div>

            <div>
                <p class="font-bold text-gray-800">
                    Signature du responsable
                </p>

                <div class="mt-12 border-b border-gray-400">
                </div>
            </div>

        </div>

        {{-- Pied de page --}}
        <div class="mt-10 border-t border-gray-300 pt-4 text-center">

            <p class="text-xs text-gray-500">
                Bulletin généré par OptimaSchool
            </p>

        </div>

    </div>

</div>

{{-- Style impression --}}
<style>
    @media print {

        body {
            background: white !important;
        }

        aside,
        nav,
        header,
        footer,
        .print\:hidden {
            display: none !important;
        }

        #bulletin {
            max-width: none !important;
            margin: 0 !important;
            border: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        @page {
            size: A4;
            margin: 12mm;
        }
    }
</style>

@endsection
