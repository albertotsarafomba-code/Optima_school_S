@extends('layouts.gestion-ecole')

@section('content')

@php
    $role = \App\Enums\UserRole::tryFrom($user->role);

    $roleConfig = match($user->role) {
        'admin' => [
            'label' => 'Administrateur',
            'icon' => 'shield-check',
            'bg' => 'bg-blue-50',
            'text' => 'text-blue-700',
            'border' => 'border-blue-200',
        ],

        'enseignant' => [
            'label' => 'Enseignant',
            'icon' => 'graduation-cap',
            'bg' => 'bg-green-50',
            'text' => 'text-green-700',
            'border' => 'border-green-200',
        ],

        'comptable' => [
            'label' => 'Comptabilité',
            'icon' => 'calculator',
            'bg' => 'bg-purple-50',
            'text' => 'text-purple-700',
            'border' => 'border-purple-200',
        ],

        'parent' => [
            'label' => 'Parent',
            'icon' => 'users',
            'bg' => 'bg-purple-50',
            'text' => 'text-purple-700',
            'border' => 'border-purple-200',
        ],

        'secretariat' => [
            'label' => 'Secrétariat',
            'icon' => 'briefcase-business',
            'bg' => 'bg-amber-50',
            'text' => 'text-amber-700',
            'border' => 'border-amber-200',
        ],

        default => [
            'label' => $user->role,
            'icon' => 'circle-help',
            'bg' => 'bg-slate-50',
            'text' => 'text-slate-700',
            'border' => 'border-slate-200',
        ],
    };

    $assignedEstablishments = $user->assignedEstablishments ?? collect();
@endphp


<div class="space-y-5">

    {{-- =========================================================
         HEADER
    ========================================================== --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>

            <div class="mb-2 flex items-center gap-2 text-sm text-slate-500">

                <a
                    href="{{ route('users.index') }}"
                    class="inline-flex items-center gap-1.5 transition hover:text-blue-700"
                >
                    <i data-lucide="users" class="h-4 w-4"></i>
                    Utilisateurs
                </a>

                <i
                    data-lucide="chevron-right"
                    class="h-4 w-4 text-slate-400"
                ></i>

                <span class="text-slate-600">
                    Détails
                </span>

            </div>


            <div class="flex items-center gap-3">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-700 shadow-sm">

                    <i
                        data-lucide="user-round"
                        class="h-6 w-6 text-white"
                    ></i>

                </div>


                <div>

                    <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                        Détails de l'utilisateur
                    </h1>

                    <p class="mt-0.5 text-sm text-slate-500">
                        Consultez les informations du compte et ses établissements affectés.
                    </p>

                </div>

            </div>

        </div>


        <div class="flex items-center gap-2">

            <a
                href="{{ route('users.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50"
            >

                <i
                    data-lucide="arrow-left"
                    class="h-4 w-4"
                ></i>

                Retour

            </a>


            <a
                href="{{ route('users.edit', $user) }}"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800"
            >

                <i
                    data-lucide="pencil"
                    class="h-4 w-4"
                ></i>

                Modifier

            </a>

        </div>

    </div>


    {{-- =========================================================
         PROFIL + INFORMATIONS
    ========================================================== --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

        {{-- =====================================================
             PROFIL
        ====================================================== --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-100 px-5 py-4">

                <div class="flex items-center gap-2">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50">

                        <i
                            data-lucide="circle-user"
                            class="h-5 w-5 text-blue-700"
                        ></i>

                    </div>

                    <div>

                        <h2 class="text-sm font-bold text-slate-800">
                            Profil utilisateur
                        </h2>

                        <p class="text-xs text-slate-500">
                            Informations principales
                        </p>

                    </div>

                </div>

            </div>


            <div class="px-5 py-6">

                <div class="flex flex-col items-center text-center">

                    {{-- Avatar --}}
                    <div class="flex h-24 w-24 items-center justify-center rounded-full bg-blue-50 text-2xl font-bold text-blue-700 ring-8 ring-slate-50">

                        {{ $user->initials() }}

                    </div>


                    <h3 class="mt-5 text-lg font-bold text-slate-800">
                        {{ $user->name }}
                    </h3>


                    <p class="mt-1 max-w-full break-all text-sm text-slate-500">
                        {{ $user->email }}
                    </p>


                    {{-- Rôle --}}
                    <div class="mt-5">

                        <span
                            class="inline-flex items-center gap-2 rounded-full border px-3.5 py-1.5 text-xs font-semibold
                            {{ $roleConfig['bg'] }}
                            {{ $roleConfig['text'] }}
                            {{ $roleConfig['border'] }}"
                        >

                            <i
                                data-lucide="{{ $roleConfig['icon'] }}"
                                class="h-4 w-4"
                            ></i>

                            {{ $roleConfig['label'] }}

                        </span>

                    </div>


                    {{-- Statut --}}
                    <div class="mt-4">

                        @if($user->id === auth()->id())

                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-green-600">

                                <span class="h-2 w-2 rounded-full bg-green-500"></span>

                                Compte connecté

                            </span>

                        @else

                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500">

                                <span class="h-2 w-2 rounded-full bg-slate-300"></span>

                                Compte utilisateur

                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             INFORMATIONS DU COMPTE
        ====================================================== --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2">

            <div class="border-b border-slate-100 px-5 py-4">

                <div class="flex items-center gap-2">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-50">

                        <i
                            data-lucide="info"
                            class="h-5 w-5 text-green-700"
                        ></i>

                    </div>

                    <div>

                        <h2 class="text-sm font-bold text-slate-800">
                            Informations du compte
                        </h2>

                        <p class="text-xs text-slate-500">
                            Détails du compte utilisateur
                        </p>

                    </div>

                </div>

            </div>


            <div class="divide-y divide-slate-100">

                {{-- Nom --}}
                <div class="flex items-center gap-4 px-5 py-4">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50">

                        <i
                            data-lucide="user"
                            class="h-5 w-5 text-blue-700"
                        ></i>

                    </div>

                    <div class="min-w-0">

                        <p class="text-xs font-medium text-slate-400">
                            Nom complet
                        </p>

                        <p class="mt-0.5 text-sm font-semibold text-slate-800">
                            {{ $user->name }}
                        </p>

                    </div>

                </div>


                {{-- Email --}}
                <div class="flex items-center gap-4 px-5 py-4">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-green-50">

                        <i
                            data-lucide="mail"
                            class="h-5 w-5 text-green-700"
                        ></i>

                    </div>

                    <div class="min-w-0">

                        <p class="text-xs font-medium text-slate-400">
                            Adresse email
                        </p>

                        <p class="mt-0.5 break-all text-sm font-semibold text-slate-800">
                            {{ $user->email }}
                        </p>

                    </div>

                </div>


                {{-- Rôle --}}
                <div class="flex items-center gap-4 px-5 py-4">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50">

                        <i
                            data-lucide="shield-check"
                            class="h-5 w-5 text-indigo-700"
                        ></i>

                    </div>

                    <div class="min-w-0">

                        <p class="text-xs font-medium text-slate-400">
                            Rôle
                        </p>

                        <p class="mt-0.5 text-sm font-semibold text-slate-800">
                            {{ $roleConfig['label'] }}
                        </p>

                    </div>

                </div>


                {{-- Création --}}
                <div class="flex items-center gap-4 px-5 py-4">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100">

                        <i
                            data-lucide="calendar-plus"
                            class="h-5 w-5 text-slate-600"
                        ></i>

                    </div>

                    <div class="min-w-0">

                        <p class="text-xs font-medium text-slate-400">
                            Compte créé le
                        </p>

                        <p class="mt-0.5 text-sm font-semibold text-slate-800">
                            {{ $user->created_at?->format('d/m/Y à H:i') ?? '—' }}
                        </p>

                    </div>

                </div>


                {{-- Modification --}}
                <div class="flex items-center gap-4 px-5 py-4">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100">

                        <i
                            data-lucide="clock-3"
                            class="h-5 w-5 text-slate-600"
                        ></i>

                    </div>

                    <div class="min-w-0">

                        <p class="text-xs font-medium text-slate-400">
                            Dernière modification
                        </p>

                        <p class="mt-0.5 text-sm font-semibold text-slate-800">
                            {{ $user->updated_at?->format('d/m/Y à H:i') ?? '—' }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         ÉTABLISSEMENTS AFFECTÉS
    ========================================================== --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-100 px-5 py-4">

            <div class="flex items-center justify-between gap-4">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50">

                        <i
                            data-lucide="building-2"
                            class="h-5 w-5 text-blue-700"
                        ></i>

                    </div>

                    <div>

                        <h2 class="text-sm font-bold text-slate-800">
                            Établissement(s) affecté(s)
                        </h2>

                        <p class="text-xs text-slate-500">
                            Établissements auxquels cet utilisateur peut accéder.
                        </p>

                    </div>

                </div>


                @if($user->role !== 'admin')

                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                        {{ $assignedEstablishments->count() }}
                        {{ $assignedEstablishments->count() > 1 ? 'établissements' : 'établissement' }}
                    </span>

                @endif

            </div>

        </div>


        <div class="p-5">

            {{-- Admin --}}
            @if($user->role === 'admin')

                <div class="flex items-start gap-4 rounded-xl border border-blue-200 bg-blue-50 p-4">

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-blue-100">

                        <i
                            data-lucide="shield-check"
                            class="h-5 w-5 text-blue-700"
                        ></i>

                    </div>

                    <div>

                        <h3 class="font-semibold text-blue-800">
                            Administration globale
                        </h3>

                        <p class="mt-1 text-sm leading-6 text-blue-700">
                            Le compte administrateur n'est pas limité à un établissement
                            particulier. Il peut gérer les établissements qui lui appartiennent
                            et administrer les comptes utilisateurs.
                        </p>

                    </div>

                </div>


            {{-- Utilisateur avec établissements --}}
            @elseif($assignedEstablishments->isNotEmpty())

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    @foreach($assignedEstablishments as $establishment)

                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-blue-200 hover:bg-blue-50/40">

                            <div class="flex items-start gap-3">

                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-white text-blue-700 shadow-sm">

                                    <i
                                        data-lucide="building-2"
                                        class="h-5 w-5"
                                    ></i>

                                </div>


                                <div class="min-w-0 flex-1">

                                    <h3 class="font-semibold text-slate-800">
                                        {{ $establishment->nom }}
                                    </h3>


                                    @if($establishment->schoolType)

                                        <p class="mt-1 text-xs font-medium text-blue-700">

                                            {{ $establishment->schoolType->nom
                                                ?? $establishment->schoolType->name
                                                ?? 'Type non renseigné' }}

                                        </p>

                                    @else

                                        <p class="mt-1 text-xs font-medium text-slate-400">
                                            Type d'établissement non renseigné
                                        </p>

                                    @endif


                                    @if($establishment->adresse)

                                        <div class="mt-2 flex items-start gap-1.5 text-xs text-slate-500">

                                            <i
                                                data-lucide="map-pin"
                                                class="mt-0.5 h-3.5 w-3.5 shrink-0"
                                            ></i>

                                            <span>
                                                {{ $establishment->adresse }}
                                            </span>

                                        </div>

                                    @endif


                                    @if($establishment->telephone)

                                        <div class="mt-1.5 flex items-center gap-1.5 text-xs text-slate-500">

                                            <i
                                                data-lucide="phone"
                                                class="h-3.5 w-3.5 shrink-0"
                                            ></i>

                                            <span>
                                                {{ $establishment->telephone }}
                                            </span>

                                        </div>

                                    @endif

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>


            {{-- Aucun établissement --}}
            @else

                <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-red-200 bg-red-50 px-5 py-8 text-center">

                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100">

                        <i
                            data-lucide="building-2"
                            class="h-6 w-6 text-red-600"
                        ></i>

                    </div>

                    <h3 class="mt-3 font-semibold text-red-700">
                        Aucun établissement affecté
                    </h3>

                    <p class="mt-1 max-w-lg text-sm text-red-600">
                        Cet utilisateur ne possède actuellement aucune affectation.
                        Pour un compte non administrateur, une affectation est nécessaire
                        pour limiter correctement son accès aux données de l'établissement.
                    </p>

                    <a
                        href="{{ route('users.edit', $user) }}"
                        class="mt-4 inline-flex items-center gap-2 rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800"
                    >

                        <i
                            data-lucide="pencil"
                            class="h-4 w-4"
                        ></i>

                        Affecter un établissement

                    </a>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
         ACTIONS
    ========================================================== --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="flex flex-col gap-4 px-5 py-4 md:flex-row md:items-center md:justify-between">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50">

                    <i
                        data-lucide="settings-2"
                        class="h-5 w-5 text-green-700"
                    ></i>

                </div>

                <div>

                    <h3 class="text-sm font-bold text-slate-800">
                        Actions du compte
                    </h3>

                    <p class="text-xs text-slate-500">
                        Gérez cet utilisateur.
                    </p>

                </div>

            </div>


            <div class="flex flex-wrap items-center gap-2">

                <a
                    href="{{ route('users.edit', $user) }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700 transition hover:bg-blue-100"
                >

                    <i
                        data-lucide="pencil"
                        class="h-4 w-4"
                    ></i>

                    Modifier

                </a>


                @if($user->id !== auth()->id())

                    <form
                        action="{{ route('users.destroy', $user) }}"
                        method="POST"
                        data-delete-modal-form
                        data-user-name="{{ $user->name ?? $user->email ?? 'cet utilisateur' }}"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="button"
                            data-open-delete-modal
                            class="inline-flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-100"
                        >

                            <i
                                data-lucide="trash-2"
                                class="h-4 w-4"
                            ></i>

                            Supprimer

                        </button>

                    </form>

                @else

                    <div class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-500">

                        <i
                            data-lucide="lock-keyhole"
                            class="h-4 w-4"
                        ></i>

                        Compte actuel

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- =========================================================
         MESSAGE SUCCÈS
    ========================================================== --}}
    @if(session('success'))

        <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 shadow-sm">

            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-100">

                <i
                    data-lucide="circle-check"
                    class="h-5 w-5 text-green-700"
                ></i>

            </div>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif

</div>


{{-- =============================================================
     LUCIDE
============================================================= --}}

<script src="https://unpkg.com/lucide@latest"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>


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