@extends('layouts.gestion-ecole')

@section('title', 'Parents - OPTIMA SCHOOL')
@section('page-title', 'Gestion des parents')

@section('content')

    <div class="space-y-6">

        {{-- =========================================================
             EN-TÊTE
        ========================================================== --}}
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <h2 class="text-2xl font-black text-slate-800">
                    Parents
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Gestion des comptes parents et de leurs élèves rattachés.
                </p>

                @if($establishment)
                    <div class="mt-3 inline-flex items-center gap-2 rounded-lg
                                border border-blue-200 bg-blue-50 px-3 py-2
                                text-sm font-semibold text-blue-800">

                        <svg
                            class="h-5 w-5 shrink-0"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 21h18M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16M9 21v-4h6v4M8 7h2M14 7h2M8 11h2M14 11h2"
                            />
                        </svg>

                        <span>{{ $establishment->nom }}</span>

                    </div>
                @endif
            </div>

            <a
                href="{{ route('parents.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl
                       bg-blue-700 px-5 py-3 text-sm font-bold text-white
                       shadow-md transition hover:bg-blue-800 hover:shadow-lg"
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
                        d="M12 5v14M5 12h14"
                    />
                </svg>

                <span>Nouveau parent</span>

            </a>

        </div>


        {{-- =========================================================
             STATISTIQUE
        ========================================================== --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-center gap-4">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl
                                bg-blue-100 text-blue-700">

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"
                            />
                        </svg>

                    </div>

                    <div>
                        <p class="text-sm font-semibold text-slate-500">
                            Parents
                        </p>

                        <p class="text-2xl font-black text-slate-800">
                            {{ $parents->total() }}
                        </p>
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
             RECHERCHE
        ========================================================== --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

            <form
                method="GET"
                action="{{ route('parents.index') }}"
                class="flex flex-col gap-3 md:flex-row"
            >

                <div class="relative flex-1">

                    <div class="pointer-events-none absolute inset-y-0 left-0
                                flex items-center pl-4 text-slate-400">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <circle cx="11" cy="11" r="7" />
                            <path
                                stroke-linecap="round"
                                d="m20 20-4-4"
                            />
                        </svg>

                    </div>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Rechercher par nom, prénom, téléphone ou email..."
                        class="w-full rounded-xl border border-slate-300
                               bg-white py-3 pl-11 pr-4 text-sm text-slate-800
                               outline-none transition
                               focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                    >

                </div>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl
                           bg-blue-700 px-5 py-3 text-sm font-bold text-white
                           transition hover:bg-blue-800"
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                    >
                        <circle cx="11" cy="11" r="7" />
                        <path
                            stroke-linecap="round"
                            d="m20 20-4-4"
                        />
                    </svg>

                    <span>Rechercher</span>

                </button>

                @if(request('search'))

                    <a
                        href="{{ route('parents.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl
                               border border-slate-300 bg-white px-5 py-3
                               text-sm font-bold text-slate-700 transition
                               hover:bg-slate-50"
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
                                d="M6 6l12 12M18 6 6 18"
                            />
                        </svg>

                        <span>Réinitialiser</span>

                    </a>

                @endif

            </form>

        </div>


        {{-- =========================================================
             TABLEAU
        ========================================================== --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200
                    bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-200">

                    <thead class="bg-slate-50">

                        <tr>

                            <th class="px-6 py-4 text-left text-xs font-black
                                       uppercase tracking-wider text-slate-500">
                                Parent
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-black
                                       uppercase tracking-wider text-slate-500">
                                Contact
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-black
                                       uppercase tracking-wider text-slate-500">
                                Élèves rattachés
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-black
                                       uppercase tracking-wider text-slate-500">
                                Compte
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-black
                                       uppercase tracking-wider text-slate-500">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100 bg-white">

                        @forelse($parents as $parent)

                            <tr class="transition hover:bg-slate-50">

                                {{-- Parent --}}
                                <td class="whitespace-nowrap px-6 py-5">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-11 w-11 shrink-0 items-center
                                                    justify-center rounded-full
                                                    bg-blue-100 text-sm font-black
                                                    text-blue-700">

                                            {{ strtoupper(substr($parent->prenom ?? $parent->nom, 0, 1)) }}

                                        </div>

                                        <div>

                                            <div class="font-bold text-slate-800">
                                                {{ trim(($parent->prenom ?? '') . ' ' . $parent->nom) }}
                                            </div>

                                            <div class="text-xs text-slate-500">
                                                Parent #{{ $parent->id }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- Contact --}}
                                <td class="px-6 py-5">

                                    <div class="space-y-1 text-sm">

                                        @if($parent->telephone)

                                            <div class="flex items-center gap-2 text-slate-700">

                                                <svg
                                                    class="h-4 w-4 shrink-0 text-slate-400"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M22 16.92v3a2 2 0 0 1-2.18 2A19.79 19.79 0 0 1 3.1 5.18 2 2 0 0 1 5.11 3h3a2 2 0 0 1 2 1.72c.12.9.33 1.78.62 2.63a2 2 0 0 1-.45 2.11L9 10.73a16 16 0 0 0 4.27 4.27l1.27-1.28a2 2 0 0 1 2.11-.45c.85.29 1.73.5 2.63.62A2 2 0 0 1 21 15.89l1 .03Z"
                                                    />
                                                </svg>

                                                <span>{{ $parent->telephone }}</span>

                                            </div>

                                        @endif

                                        @if($parent->email)

                                            <div class="flex items-center gap-2 text-slate-600">

                                                <svg
                                                    class="h-4 w-4 shrink-0 text-slate-400"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2Z"
                                                    />
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="m22 6-10 7L2 6"
                                                    />
                                                </svg>

                                                <span>{{ $parent->email }}</span>

                                            </div>

                                        @endif

                                        @if(!$parent->telephone && !$parent->email)
                                            <span class="text-sm text-slate-400">
                                                Aucun contact
                                            </span>
                                        @endif

                                    </div>

                                </td>


                                {{-- Élèves --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center gap-2">

                                        <span class="inline-flex items-center rounded-full
                                                     bg-blue-100 px-3 py-1 text-sm
                                                     font-bold text-blue-800">
                                            {{ $parent->students->count() }}
                                        </span>

                                        <span class="text-sm text-slate-600">
                                            {{ $parent->students->count() > 1 ? 'élèves' : 'élève' }}
                                        </span>

                                    </div>

                                    @if($parent->students->isNotEmpty())

                                        <div class="mt-2 max-w-xs space-y-1">

                                            @foreach($parent->students->take(3) as $student)

                                                <div class="truncate text-xs font-semibold text-slate-600">
                                                    {{ trim($student->prenom . ' ' . $student->nom) }}
                                                </div>

                                            @endforeach

                                            @if($parent->students->count() > 3)

                                                <div class="text-xs font-semibold text-slate-400">
                                                    + {{ $parent->students->count() - 3 }} autre(s)
                                                </div>

                                            @endif

                                        </div>

                                    @endif

                                </td>


                                {{-- Compte --}}
                                <td class="px-6 py-5">

                                    @if($parent->user)

                                        <span class="inline-flex items-center gap-2 rounded-full
                                                     bg-green-100 px-3 py-1 text-sm
                                                     font-bold text-green-800">

                                            <span class="h-2 w-2 rounded-full bg-green-600"></span>

                                            Actif

                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-2 rounded-full
                                                     bg-slate-100 px-3 py-1 text-sm
                                                     font-bold text-slate-600">

                                            <span class="h-2 w-2 rounded-full bg-slate-400"></span>

                                            Sans compte

                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="px-6 py-5">

                                    <div class="flex items-center justify-end gap-2">

                                        <a
                                            href="{{ route('parents.show', $parent) }}"
                                            class="inline-flex items-center justify-center rounded-lg
                                                   border border-blue-200 bg-blue-50 p-2.5
                                                   text-blue-700 transition hover:bg-blue-100"
                                            title="Voir"
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
                                                    d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"
                                                />
                                                <circle cx="12" cy="12" r="2.75" />
                                            </svg>

                                        </a>


                                        <a
                                            href="{{ route('parents.edit', $parent) }}"
                                            class="inline-flex items-center justify-center rounded-lg
                                                   border border-amber-200 bg-amber-50 p-2.5
                                                   text-amber-700 transition hover:bg-amber-100"
                                            title="Modifier"
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
                                                    d="m16.862 3.487 3.651 3.651M18.5 2.25a2.121 2.121 0 0 1 3 3L7.5 19.25l-5 1 1-5L18.5 2.25Z"
                                                />
                                            </svg>

                                        </a>


                                        <form
                                            method="POST"
                                            action="{{ route('parents.destroy', $parent) }}"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer ce parent et son compte utilisateur ?');"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center rounded-lg
                                                       border border-red-200 bg-red-50 p-2.5
                                                       text-red-700 transition hover:bg-red-100"
                                                title="Supprimer"
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
                                                        d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5"
                                                    />
                                                </svg>

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="px-6 py-14 text-center">

                                    <div class="mx-auto flex max-w-md flex-col items-center">

                                        <div class="flex h-16 w-16 items-center justify-center
                                                    rounded-2xl bg-slate-100 text-slate-400">

                                            <svg
                                                class="h-8 w-8"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"
                                                />
                                            </svg>

                                        </div>

                                        <h3 class="mt-4 text-lg font-black text-slate-800">
                                            Aucun parent trouvé
                                        </h3>

                                        <p class="mt-2 text-sm text-slate-500">
                                            @if(request('search'))
                                                Aucun parent ne correspond à votre recherche.
                                            @else
                                                Aucun parent n'est encore enregistré dans cet établissement.
                                            @endif
                                        </p>

                                        @if(!request('search'))

                                            <a
                                                href="{{ route('parents.create') }}"
                                                class="mt-5 inline-flex items-center gap-2 rounded-xl
                                                       bg-blue-700 px-5 py-3 text-sm font-bold
                                                       text-white transition hover:bg-blue-800"
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
                                                        d="M12 5v14M5 12h14"
                                                    />
                                                </svg>

                                                <span>Créer le premier parent</span>

                                            </a>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- =====================================================
                 PAGINATION
            ====================================================== --}}
            @if($parents->hasPages())

                <div class="border-t border-slate-200 px-6 py-4">

                    {{ $parents->withQueryString()->links() }}

                </div>

            @endif

        </div>

    </div>

@endsection
