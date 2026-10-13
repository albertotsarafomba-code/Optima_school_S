@extends('layouts.gestion-ecole')

@section('title', 'Tarifs d’écolage')

@section('content')

<div class="space-y-6">

    {{-- =========================================================
         EN-TÊTE
    ========================================================== --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Tarifs d’écolage
            </h1>

            <p class="mt-1 text-sm text-slate-600">
                {{ $establishment->nom }}
                ·
                Année scolaire :
                <span class="font-semibold text-blue-900">
                    {{ $schoolYear->name }}
                </span>
            </p>
        </div>

        <div class="flex flex-wrap gap-2">

            <a
                href="{{ route('paiements.index', [
                    'school_year_id' => $schoolYear->id,
                ]) }}"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-300
                       bg-white px-4 py-2.5 text-sm font-semibold text-slate-700
                       transition hover:bg-slate-50"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M19 12H5"/>
                    <path d="m12 19-7-7 7-7"/>
                </svg>

                Suivi des écolages
            </a>

            <a
                href="{{ route('paiements.tarifs.create', [
                    'school_year_id' => $schoolYear->id,
                ]) }}"
                class="inline-flex items-center gap-2 rounded-lg
                       bg-green-600 px-4 py-2.5 text-sm font-semibold text-white
                       transition hover:bg-green-700"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M12 5v14"/>
                    <path d="M5 12h14"/>
                </svg>

                Ajouter un tarif
            </a>

        </div>

    </div>


    {{-- =========================================================
         MESSAGES
    ========================================================== --}}
    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3">
            <div class="flex items-start gap-3">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="mt-0.5 h-5 w-5 shrink-0 text-green-700"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="m5 12 4 4L19 6"/>
                </svg>

                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>

            </div>
        </div>
    @endif


    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3">

            <div class="font-semibold text-red-800">
                Une erreur est survenue.
            </div>

            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif


    {{-- =========================================================
         FILTRE ANNÉE SCOLAIRE
    ========================================================== --}}
    <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">

        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">

            <div>
                <h2 class="text-base font-bold text-slate-900">
                    Année scolaire
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Les tarifs sont configurés séparément pour chaque année scolaire.
                </p>
            </div>

            <form
                method="GET"
                action="{{ route('paiements.tarifs.index') }}"
                class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row"
            >

                <select
                    name="school_year_id"
                    class="min-w-[230px] rounded-lg border border-slate-300 bg-white
                           px-3 py-2.5 text-sm text-slate-800
                           focus:border-blue-700 focus:outline-none
                           focus:ring-2 focus:ring-blue-100"
                >
                    @foreach($schoolYears as $yearItem)
                        <option
                            value="{{ $yearItem->id }}"
                            @selected($yearItem->id == $schoolYear->id)
                        >
                            {{ $yearItem->name }}
                            @if($yearItem->is_active)
                                — active
                            @endif
                        </option>
                    @endforeach
                </select>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg
                           bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white
                           transition hover:bg-blue-800"
                >
                    Afficher
                </button>

            </form>

        </div>

    </div>


    {{-- =========================================================
         STATISTIQUES
    ========================================================== --}}
    @php
        $totalClasses = $classes->count();
        $totalTarifs = $tarifs->count();
        $classesSansTarif = max(0, $totalClasses - $totalTarifs);
    @endphp

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-xl border border-blue-100 bg-blue-50 p-5">
            <p class="text-sm font-medium text-blue-700">
                Classes
            </p>

            <p class="mt-2 text-2xl font-bold text-blue-950">
                {{ $totalClasses }}
            </p>

            <p class="mt-1 text-xs text-blue-700">
                Dans l’année scolaire sélectionnée
            </p>
        </div>


        <div class="rounded-xl border border-green-100 bg-green-50 p-5">
            <p class="text-sm font-medium text-green-700">
                Tarifs configurés
            </p>

            <p class="mt-2 text-2xl font-bold text-green-900">
                {{ $totalTarifs }}
            </p>

            <p class="mt-1 text-xs text-green-700">
                Classes avec tarif mensuel
            </p>
        </div>


        <div class="rounded-xl border border-orange-100 bg-orange-50 p-5">
            <p class="text-sm font-medium text-orange-700">
                Sans tarif
            </p>

            <p class="mt-2 text-2xl font-bold text-orange-900">
                {{ $classesSansTarif }}
            </p>

            <p class="mt-1 text-xs text-orange-700">
                Classes à configurer
            </p>
        </div>

    </div>


    {{-- =========================================================
         TABLEAU DES TARIFS
    ========================================================== --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-5 py-4">

            <h2 class="text-base font-bold text-slate-900">
                Tarifs mensuels par classe
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Définissez le montant officiel de l’écolage mensuel pour chaque classe.
            </p>

        </div>


        @if($classes->isEmpty())

            <div class="px-6 py-12 text-center">

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-slate-500"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M4 5h16"/>
                        <path d="M4 12h16"/>
                        <path d="M4 19h16"/>
                    </svg>

                </div>

                <h3 class="mt-4 text-base font-semibold text-slate-900">
                    Aucune classe
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Aucune classe n’est disponible pour cette année scolaire.
                </p>

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Classe
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Niveau
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Tarif mensuel
                            </th>

                            <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Situation
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100 bg-white">

                        @foreach($classes as $classRoom)

                            @php
                                $tarif = $tarifs->firstWhere(
                                    'class_room_id',
                                    $classRoom->id
                                );
                            @endphp

                            <tr class="transition hover:bg-slate-50">

                                <td class="whitespace-nowrap px-5 py-4">

                                    <div class="font-semibold text-slate-900">
                                        {{ $classRoom->nom_classe }}
                                    </div>

                                    @if($classRoom->filiere)
                                        <div class="mt-0.5 text-xs text-slate-500">
                                            {{ $classRoom->filiere }}
                                        </div>
                                    @endif

                                </td>


                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-700">
                                    {{ $classRoom->niveau ?: '—' }}
                                </td>


                                <td class="whitespace-nowrap px-5 py-4 text-right">

                                    @if($tarif)

                                        <span class="text-base font-bold text-slate-900">
                                            {{ number_format((float) $tarif->montant_mensuel, 0, ',', ' ') }}
                                            Ar
                                        </span>

                                    @else

                                        <span class="text-sm font-medium text-slate-400">
                                            Non défini
                                        </span>

                                    @endif

                                </td>


                                <td class="whitespace-nowrap px-5 py-4 text-center">

                                    @if($tarif)

                                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                                            Configuré
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-800">
                                            À configurer
                                        </span>

                                    @endif

                                </td>


                                <td class="whitespace-nowrap px-5 py-4 text-right">

                                    <div class="flex justify-end gap-2">

                                        @if($tarif)

                                            <a
                                                href="{{ route('paiements.tarifs.edit', $tarif) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg
                                                       border border-blue-200 bg-blue-50
                                                       px-3 py-2 text-xs font-semibold text-blue-800
                                                       transition hover:bg-blue-100"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >
                                                    <path d="M12 20h9"/>
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/>
                                                </svg>

                                                Modifier
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('paiements.tarifs.destroy', $tarif) }}"
                                                onsubmit="return confirm('Supprimer le tarif de cette classe ?');"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg
                                                           border border-red-200 bg-red-50
                                                           px-3 py-2 text-xs font-semibold text-red-800
                                                           transition hover:bg-red-100"
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                    >
                                                        <path d="M3 6h18"/>
                                                        <path d="M8 6V4h8v2"/>
                                                        <path d="M19 6l-1 14H6L5 6"/>
                                                        <path d="M10 11v5"/>
                                                        <path d="M14 11v5"/>
                                                    </svg>

                                                    Supprimer
                                                </button>

                                            </form>

                                        @else

                                            <a
                                                href="{{ route('paiements.tarifs.create', [
                                                    'school_year_id' => $schoolYear->id,
                                                    'class_room_id' => $classRoom->id,
                                                ]) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg
                                                       bg-green-600 px-3 py-2 text-xs font-semibold
                                                       text-white transition hover:bg-green-700"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-4 w-4"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                >
                                                    <path d="M12 5v14"/>
                                                    <path d="M5 12h14"/>
                                                </svg>

                                                Ajouter
                                            </a>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

</div>

@endsection
