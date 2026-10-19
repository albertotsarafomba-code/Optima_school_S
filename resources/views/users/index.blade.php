@extends('layouts.gestion-ecole')

@section('content')

<div class="space-y-6">

    {{-- En-tête --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Gestion des utilisateurs
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Gérez les comptes, les rôles et les établissements affectés.
            </p>
        </div>

        <a
            href="{{ route('users.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 4v16m8-8H4"/>
            </svg>

            Nouvel utilisateur
        </a>

    </div>


    {{-- Message succès --}}
    @if(session('success'))

        <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5 shrink-0"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M5 13l4 4L19 7"/>
            </svg>

            {{ session('success') }}

        </div>

    @endif


    {{-- Message erreur --}}
    @if(session('error'))

        <div class="flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-5 w-5 shrink-0"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>

            {{ session('error') }}

        </div>

    @endif


    {{-- Recherche / filtres --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

        <form
            method="GET"
            action="{{ route('users.index') }}"
            class="grid grid-cols-1 gap-4 md:grid-cols-[1fr_220px_auto]"
        >

            {{-- Recherche --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Rechercher
                </label>

                <div class="relative">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="m21 21-4.35-4.35m2.35-5.65a8 8 0 1 1-16 0 8 8 0 0 1 16 0Z"/>
                    </svg>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Nom ou adresse email..."
                        class="w-full rounded-xl border border-slate-300 py-3 pl-10 pr-4 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    >

                </div>

            </div>


            {{-- Filtre rôle --}}
            <div>

                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Rôle
                </label>

                <select
                    name="role"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                >

                    <option value="">
                        Tous les rôles
                    </option>

                    @foreach($roles as $role)

                        <option
                            value="{{ $role->value }}"
                            @selected(request('role') === $role->value)
                        >
                            {{ $role->label() }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Boutons --}}
            <div class="flex items-end gap-2">

                <button
                    type="submit"
                    class="inline-flex h-[46px] items-center justify-center rounded-xl bg-slate-800 px-5 text-sm font-semibold text-white transition hover:bg-slate-900"
                >
                    Filtrer
                </button>

                @if(request()->filled('search') || request()->filled('role'))

                    <a
                        href="{{ route('users.index') }}"
                        class="inline-flex h-[46px] items-center justify-center rounded-xl border border-slate-300 px-4 text-sm font-medium text-slate-600 transition hover:bg-slate-50"
                    >
                        Réinitialiser
                    </a>

                @endif

            </div>

        </form>

    </div>


    {{-- Tableau --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-slate-200">

                <thead class="bg-slate-50">

                    <tr>

                        <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                            Utilisateur
                        </th>

                        <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                            Email
                        </th>

                        <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                            Rôle
                        </th>

                        <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                            Établissement(s)
                        </th>

                        <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                            Créé le
                        </th>

                        <th class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wide text-slate-500">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($users as $user)

                        @php
                            $roleEnum = \App\Enums\UserRole::tryFrom($user->role);
                        @endphp

                        <tr class="transition hover:bg-slate-50">

                            {{-- Utilisateur --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                                        {{ $user->initials() }}
                                    </div>

                                    <div>

                                        <div class="font-semibold text-slate-800">
                                            {{ $user->name }}
                                        </div>

                                        @if($user->id === auth()->id())

                                            <span class="text-xs font-medium text-green-600">
                                                Compte connecté
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- Email --}}
                            <td class="px-5 py-4 text-sm text-slate-600">
                                {{ $user->email }}
                            </td>


                            {{-- Rôle --}}
                            <td class="px-5 py-4">

                                @if($roleEnum)

                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                        @if($roleEnum->value === 'admin')
                                            bg-blue-100 text-blue-700
                                        @elseif($roleEnum->value === 'enseignant')
                                            bg-green-100 text-green-700
                                        @elseif($roleEnum->value === 'comptable')
                                            bg-purple-100 text-purple-700
                                        @elseif($roleEnum->value === 'parent')
                                            bg-orange-100 text-orange-700
                                        @elseif($roleEnum->value === 'secretariat')
                                            bg-emerald-100 text-emerald-700
                                        @else
                                            bg-slate-100 text-slate-700
                                        @endif"
                                    >
                                        {{ $roleEnum->label() }}
                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        {{ $user->role }}
                                    </span>

                                @endif

                            </td>


                            {{-- Établissements --}}
                            <td class="px-5 py-4">

                                @if($user->role === 'admin')

                                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="2">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-8h6v8"/>
                                        </svg>

                                        Administration globale

                                    </span>

                                @elseif($user->assignedEstablishments->isNotEmpty())

                                    <div class="flex flex-col gap-1.5">

                                        @foreach($user->assignedEstablishments as $establishment)

                                            <span class="inline-flex w-fit items-center gap-1.5 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-700">

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-3.5 w-3.5 text-slate-500"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="2">
                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-8h6v8"/>
                                                </svg>

                                                {{ $establishment->nom }}

                                            </span>

                                        @endforeach

                                    </div>

                                @else

                                    <span class="text-xs font-medium text-red-500">
                                        Aucun établissement
                                    </span>

                                @endif

                            </td>


                            {{-- Date --}}
                            <td class="px-5 py-4 text-sm text-slate-500">
                                {{ $user->created_at?->format('d/m/Y') }}
                            </td>


                            {{-- Actions --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    {{-- Voir --}}
                                    <a
                                        href="{{ route('users.show', $user) }}"
                                        title="Voir"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600"
                                    >

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="2">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7Z"/>

                                            <circle cx="12" cy="12" r="3"/>

                                        </svg>

                                    </a>


                                    {{-- Modifier --}}
                                    <a
                                        href="{{ route('users.edit', $user) }}"
                                        title="Modifier"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-green-200 hover:bg-green-50 hover:text-green-600"
                                    >

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-4 w-4"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="2">

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5"/>

                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5Z"/>

                                        </svg>

                                    </a>


                                    {{-- Supprimer --}}
                                    @if($user->id !== auth()->id())

                                        <form
                                            method="POST"
                                            action="{{ route('users.destroy', $user) }}"
                        data-delete-modal-form
                        data-user-name="{{ $user->name ?? $user->email ?? 'cet utilisateur' }}"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                data-open-delete-modal
                                                title="Supprimer"
                                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600"
                                            >

                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="h-4 w-4"
                                                     fill="none"
                                                     viewBox="0 0 24 24"
                                                     stroke="currentColor"
                                                     stroke-width="2">

                                                    <path stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="M6 7h12M9 7V5h6v2m-8 0 1 13h8l1-13M10 11v5m4-5v5"/>

                                                </svg>

                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="px-5 py-12 text-center">

                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-7 w-7"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="2">

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>

                                        <circle cx="9" cy="7" r="4"/>

                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M22 21v-2a4 4 0 0 0-3-3.87m-1-12.13a4 4 0 0 1 0 7.75"/>

                                    </svg>

                                </div>

                                <p class="mt-4 font-semibold text-slate-700">
                                    Aucun utilisateur trouvé
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Aucun compte ne correspond aux critères sélectionnés.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($users->hasPages())

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $users->links() }}
            </div>

        @endif

    </div>

</div>


{{-- Modale de confirmation de suppression --}}
<div id="user-delete-modal"
     class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/60 p-4"
     role="dialog"
     aria-modal="true"
     aria-labelledby="user-delete-modal-title"
     aria-describedby="user-delete-modal-description">

    <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">
        <div class="bg-blue-900 px-6 py-5 text-white">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/15">
                    <i data-lucide="shield-alert" class="h-6 w-6"></i>
                </div>
                <div>
                    <h2 id="user-delete-modal-title" class="text-lg font-bold">
                        Confirmer la suppression
                    </h2>
                    <p class="mt-1 text-sm text-blue-100">
                        Vérifiez avant de continuer.
                    </p>
                </div>
            </div>
        </div>

        <div class="px-6 py-5">
            <p class="text-sm leading-6 text-slate-600">
                Voulez-vous vraiment supprimer le compte :
            </p>

            <p id="user-delete-modal-name"
               class="mt-2 break-words font-semibold text-slate-900"></p>

            <div class="mt-4 flex gap-3 rounded-xl border border-red-200 bg-red-50 p-3">
                <i data-lucide="alert-triangle"
                   class="mt-0.5 h-5 w-5 shrink-0 text-red-600"></i>
                <p id="user-delete-modal-description"
                   class="text-sm leading-5 text-red-800">
                    Cette action est définitive. La suppression ne pourra pas être annulée.
                </p>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">
            <button type="button"
                    data-close-delete-modal
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                <i data-lucide="x" class="h-4 w-4"></i>
                Annuler
            </button>

            <button type="button"
                    id="user-delete-confirm"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-200">
                <i data-lucide="trash-2" class="h-4 w-4"></i>
                Supprimer définitivement
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    function initUserDeleteModal() {
        const modal = document.getElementById('user-delete-modal');
        if (!modal || modal.dataset.initialized === 'true') return;

        modal.dataset.initialized = 'true';

        const nameElement = document.getElementById('user-delete-modal-name');
        const confirmButton = document.getElementById('user-delete-confirm');
        let pendingForm = null;
        let previousFocus = null;

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            pendingForm = null;

            if (previousFocus && document.contains(previousFocus)) {
                previousFocus.focus();
            }
        }

        document.querySelectorAll('[data-open-delete-modal]').forEach(function (button) {
            button.addEventListener('click', function () {
                const form = button.closest('[data-delete-modal-form]');
                if (!form) return;

                pendingForm = form;
                previousFocus = button;

                nameElement.textContent = form.dataset.userName || 'cet utilisateur';
                modal.classList.remove('hidden');
                modal.classList.add('flex');

                if (window.lucide) window.lucide.createIcons();

                document.querySelector('[data-close-delete-modal]').focus();
            });
        });

        modal.querySelectorAll('[data-close-delete-modal]').forEach(function (button) {
            button.addEventListener('click', closeModal);
        });

        modal.addEventListener('click', function (event) {
            if (event.target === modal) closeModal();
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        confirmButton.addEventListener('click', function () {
            if (!pendingForm) return;

            confirmButton.disabled = true;
            pendingForm.submit();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initUserDeleteModal, { once: true });
    } else {
        initUserDeleteModal();
    }
})();
</script>
@endpush

@endsection