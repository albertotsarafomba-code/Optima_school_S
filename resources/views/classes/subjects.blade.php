@extends('layouts.gestion-ecole')

@section('title', 'Matières de la classe')

@section('page-title', 'Matières de la classe')

@section(
    'page-description',
    'Gestion des matières affectées à cette classe'
)

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="flex items-center gap-3">

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100">
                    <i data-lucide="book-open" class="h-6 w-6 text-blue-600"></i>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Matières de la classe
                    </h1>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $class->nom_classe }}
                        @if($class->niveau)
                            — {{ $class->niveau }}
                        @endif
                    </p>
                </div>

            </div>
        </div>

        <a
            href="{{ route('class_rooms.index') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50"
        >
            <i data-lucide="arrow-left" class="h-4 w-4"></i>
            Retour aux classes
        </a>

    </div>


    {{-- SUCCESS --}}
    @if(session('success'))

        <div class="rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-medium text-green-700">
            <div class="flex items-center gap-3">

                <i data-lucide="circle-check" class="h-5 w-5"></i>

                <span>
                    {{ session('success') }}
                </span>

            </div>
        </div>

    @endif


    {{-- ERROR --}}
    @if(session('error'))

        <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>

    @endif


    {{-- VALIDATION --}}
    @if($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-5">

            <div class="flex gap-3">

                <i
                    data-lucide="alert-circle"
                    class="h-5 w-5 shrink-0 text-red-600"
                ></i>

                <div>

                    <h3 class="font-semibold text-red-800">
                        Veuillez corriger les erreurs
                    </h3>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- INFORMATIONS --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                Classe
            </p>

            <p class="mt-2 text-lg font-bold text-gray-900">
                {{ $class->nom_classe }}
            </p>

        </div>


        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                Niveau
            </p>

            <p class="mt-2 text-lg font-bold text-gray-900">
                {{ $class->niveau ?: '—' }}
            </p>

        </div>


        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                Année scolaire
            </p>

            <p class="mt-2 text-lg font-bold text-gray-900">

                {{
                    $class->schoolYear?->name
                    ?? $class->annee_scolaire
                    ?? '—'
                }}

            </p>

        </div>


        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                Matières affectées
            </p>

            <p class="mt-2 text-2xl font-bold text-blue-600">
                {{ $class->subjects->count() }}
            </p>

        </div>

    </div>


    {{-- FORMULAIRE --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

        <div class="border-b border-gray-100 bg-blue-50 px-6 py-5">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100">
                    <i data-lucide="plus" class="h-5 w-5 text-blue-600"></i>
                </div>

                <div>

                    <h2 class="text-lg font-bold text-gray-900">
                        Affecter une matière
                    </h2>

                    <p class="text-sm text-gray-500">
                        Sélectionnez une matière existante et définissez son coefficient.
                    </p>

                </div>

            </div>

        </div>


        <div class="p-6">

            @php
                $availableSubjects = $subjects->reject(
                    fn($subject) =>
                        $class->subjects->contains($subject->id)
                );
            @endphp


            @if($availableSubjects->count() > 0)

                <form
                    action="{{ route('class_rooms.subjects.attach', $class) }}"
                    method="POST"
                    class="grid grid-cols-1 gap-5 md:grid-cols-3"
                >

                    @csrf


                    <div>

                        <label
                            for="subject_id"
                            class="mb-2 block text-sm font-semibold text-gray-700"
                        >
                            Matière
                        </label>

                        <select
                            name="subject_id"
                            id="subject_id"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >

                            <option value="">
                                -- Sélectionner une matière --
                            </option>

                            @foreach($availableSubjects as $subject)

                                <option
                                    value="{{ $subject->id }}"
                                    {{ old('subject_id') == $subject->id ? 'selected' : '' }}
                                >
                                    {{ $subject->code }}
                                    —
                                    {{ $subject->nom }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div>

                        <label
                            for="coefficient"
                            class="mb-2 block text-sm font-semibold text-gray-700"
                        >
                            Coefficient
                        </label>

                        <input
                            type="number"
                            name="coefficient"
                            id="coefficient"
                            value="{{ old('coefficient', 1) }}"
                            min="1"
                            max="20"
                            step="1"
                            required
                            class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >

                    </div>


                    <div class="flex items-end">

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                        >

                            <i data-lucide="plus" class="h-4 w-4"></i>

                            Affecter la matière

                        </button>

                    </div>

                </form>

            @else

                <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">

                    <div class="flex items-start gap-3">

                        <i
                            data-lucide="info"
                            class="h-5 w-5 shrink-0 text-amber-600"
                        ></i>

                        <div>

                            <p class="font-semibold text-amber-800">
                                Toutes les matières disponibles sont déjà affectées.
                            </p>

                            <p class="mt-1 text-sm text-amber-700">
                                Ou aucune matière n'a encore été créée dans cet établissement.
                            </p>

                        </div>

                    </div>

                </div>

            @endif

        </div>

    </div>


    {{-- LISTE --}}
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">

        <div class="border-b border-gray-100 px-6 py-5">

            <h2 class="text-lg font-bold text-gray-900">
                Matières affectées
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Liste des matières enseignées dans cette classe.
            </p>

        </div>


        @if($class->subjects->count() > 0)

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-6 py-4 text-left font-semibold text-gray-700">
                                Code
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-gray-700">
                                Matière
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-gray-700">
                                Coefficient
                            </th>

                            <th class="px-6 py-4 text-right font-semibold text-gray-700">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @foreach($class->subjects as $subject)

                            <tr class="transition hover:bg-gray-50">

                                <td class="px-6 py-4">

                                    <span class="rounded-lg bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">
                                        {{ $subject->code }}
                                    </span>

                                </td>


                                <td class="px-6 py-4">

                                    <div class="font-semibold text-gray-900">
                                        {{ $subject->nom }}
                                    </div>

                                    @if($subject->description)

                                        <div class="mt-1 text-xs text-gray-500">
                                            {{ $subject->description }}
                                        </div>

                                    @endif

                                </td>


                                <td class="px-6 py-4">

                                    <form
                                        action="{{ route('class_rooms.subjects.update', [$class, $subject]) }}"
                                        method="POST"
                                        class="flex items-center gap-2"
                                    >

                                        @csrf

                                        @method('PUT')

                                        <input
                                            type="number"
                                            name="coefficient"
                                            value="{{ $subject->pivot->coefficient }}"
                                            min="1"
                                            max="20"
                                            step="1"
                                            required
                                            class="w-20 rounded-lg border border-gray-200 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                        >

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 transition hover:bg-blue-100"
                                        >
                                            Modifier
                                        </button>

                                    </form>

                                </td>


                                <td class="px-6 py-4 text-right">

                                    <form
                                        action="{{ route('class_rooms.subjects.detach', [$class, $subject]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Voulez-vous retirer cette matière de cette classe ?');"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-2 rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100"
                                        >

                                            <i data-lucide="trash-2" class="h-4 w-4"></i>

                                            Retirer

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="px-6 py-14 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100">

                    <i
                        data-lucide="book-open"
                        class="h-7 w-7 text-gray-400"
                    ></i>

                </div>

                <h3 class="mt-4 text-lg font-bold text-gray-900">
                    Aucune matière affectée
                </h3>

                <p class="mt-2 text-sm text-gray-500">
                    Utilisez le formulaire ci-dessus pour affecter une matière.
                </p>

            </div>

        @endif

    </div>

</div>

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

});
</script>

@endpush

@endsection