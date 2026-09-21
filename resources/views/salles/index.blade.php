@extends('layouts.gestion-ecole')

@section('title', 'Salles')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 21h18"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 7h2M9 11h2M9 15h2M14 7h2M14 11h2M14 15h2"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold text-slate-800">
                        Salles
                    </h1>

                    <p class="text-sm text-slate-500">
                        Gestion des salles de l'établissement
                    </p>
                </div>
            </div>
        </div>

        <a href="{{ route('salles.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-blue-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M12 5v14M5 12h14"/>
            </svg>

            Ajouter une salle
        </a>
    </div>


    {{-- ALERTS --}}
    @if(session('success'))
        <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5 shrink-0"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M9 12l2 2 4-4"/>
                <circle cx="12"
                        cy="12"
                        r="9"/>
            </svg>

            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5 shrink-0"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <circle cx="12"
                        cy="12"
                        r="9"/>
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M15 9l-6 6M9 9l6 6"/>
            </svg>

            {{ session('error') }}
        </div>
    @endif


    {{-- SEARCH --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

        <form method="GET"
              action="{{ route('salles.index') }}"
              class="flex flex-col gap-3 md:flex-row">

            <div class="relative flex-1">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">
                    <circle cx="11"
                            cy="11"
                            r="7"/>
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M20 20l-4-4"/>
                </svg>

                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Rechercher une salle..."
                       class="w-full rounded-xl border border-slate-200 py-3 pl-10 pr-4 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>

            <button type="submit"
                    class="rounded-xl bg-slate-800 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-900">
                Rechercher
            </button>

            @if($search)
                <a href="{{ route('salles.index') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    Réinitialiser
                </a>
            @endif

        </form>
    </div>


    {{-- TABLE --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="border-b border-slate-200 bg-slate-50">

                    <tr class="text-left text-xs font-bold uppercase tracking-wide text-slate-500">

                        <th class="px-6 py-4">
                            Salle
                        </th>

                        <th class="px-6 py-4">
                            Capacité
                        </th>

                        <th class="px-6 py-4">
                            Classes
                        </th>

                        <th class="px-6 py-4">
                            Description
                        </th>

                        <th class="px-6 py-4 text-right">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($salles as $salle)

                        <tr class="transition hover:bg-slate-50">

                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 text-sm font-bold text-blue-700">
                                        {{ strtoupper(substr($salle->nom, 0, 1)) }}
                                    </div>

                                    <div>
                                        <div class="font-semibold text-slate-800">
                                            {{ $salle->nom }}
                                        </div>

                                        <div class="text-xs text-slate-400">
                                            Salle scolaire
                                        </div>
                                    </div>

                                </div>

                            </td>

                            <td class="px-6 py-4">

                                @if($salle->capacite)
                                    <span class="rounded-lg bg-green-50 px-3 py-1.5 text-sm font-semibold text-green-700">
                                        {{ $salle->capacite }} places
                                    </span>
                                @else
                                    <span class="text-sm text-slate-400">
                                        Non définie
                                    </span>
                                @endif

                            </td>

                            <td class="px-6 py-4">

                                <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-sm font-semibold text-blue-700">
                                    {{ $salle->class_rooms_count }}
                                    {{ $salle->class_rooms_count > 1 ? 'classes' : 'classe' }}
                                </span>

                            </td>

                            <td class="max-w-xs px-6 py-4">

                                <p class="truncate text-sm text-slate-500">
                                    {{ $salle->description ?: 'Aucune description' }}
                                </p>

                            </td>

                            <td class="px-6 py-4">

                                <div class="flex justify-end gap-2">

                                    {{-- VOIR --}}
                                    <a href="{{ route('salles.show', $salle) }}"
                                       title="Voir"
                                       class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition hover:bg-blue-100">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z"/>
                                            <circle cx="12"
                                                    cy="12"
                                                    r="2.5"/>
                                        </svg>

                                    </a>

                                    {{-- MODIFIER --}}
                                    <a href="{{ route('salles.edit', $salle) }}"
                                       title="Modifier"
                                       class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-600 transition hover:bg-amber-100">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-5 w-5"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M12 20h9"/>
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M16.5 3.5a2.12 2.12 0 013 3L8 18l-4 1 1-4L16.5 3.5z"/>
                                        </svg>

                                    </a>

                                    {{-- SUPPRIMER --}}
                                    <form method="POST"
                                          action="{{ route('salles.destroy', $salle) }}"
                                          onsubmit="return confirm('Voulez-vous vraiment supprimer cette salle ?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                title="Supprimer"
                                                class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 transition hover:bg-red-100">

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 class="h-5 w-5"
                                                 viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M3 6h18"/>
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M8 6V4h8v2"/>
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M19 6l-1 15H6L5 6"/>
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      d="M10 11v6M14 11v6"/>
                                            </svg>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5"
                                class="px-6 py-16 text-center">

                                <div class="mx-auto flex max-w-md flex-col items-center">

                                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-8 w-8"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M3 21h18"/>
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16"/>
                                        </svg>

                                    </div>

                                    <h3 class="text-lg font-bold text-slate-700">
                                        Aucune salle
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Commencez par ajouter une salle à votre établissement.
                                    </p>

                                    <a href="{{ route('salles.create') }}"
                                       class="mt-5 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                                        Ajouter une salle
                                    </a>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($salles->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                {{ $salles->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
