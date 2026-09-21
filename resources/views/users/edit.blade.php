@extends('layouts.gestion-ecole')

@section('title', 'Modifier un utilisateur')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- En-tête --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

        <div class="flex items-center gap-3">

            <a
                href="{{ route('users.index') }}"
                class="inline-flex items-center justify-center w-10 h-10 rounded-xl
                       bg-gray-100 text-gray-600 hover:bg-gray-200 transition"
                title="Retour"
            >
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>

            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Modifier l'utilisateur
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Modifiez les informations, le rôle et les établissements affectés.
                </p>
            </div>

        </div>

        <a
            href="{{ route('users.index') }}"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5
                   rounded-xl border border-gray-200 bg-white text-gray-700
                   hover:bg-gray-50 transition font-medium shadow-sm"
        >
            <i data-lucide="users" class="w-5 h-5"></i>
            Liste des utilisateurs
        </a>

    </div>


    {{-- Erreurs --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <div class="flex items-start gap-3">

                <i
                    data-lucide="circle-alert"
                    class="w-5 h-5 text-red-600 flex-shrink-0"
                ></i>

                <div>
                    <h3 class="font-semibold text-red-800">
                        Impossible de modifier l'utilisateur
                    </h3>

                    <ul class="mt-2 list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>

        </div>
    @endif


    {{-- Formulaire --}}
    <form
        method="POST"
        action="{{ route('users.update', $user) }}"
        id="user-edit-form"
    >

        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Informations du compte --}}
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">

                <div class="flex items-center gap-3">

                    <div
                        class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700
                               flex items-center justify-center"
                    >
                        <i data-lucide="user-cog" class="w-5 h-5"></i>
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-gray-800">
                            Informations du compte
                        </h2>

                        <p class="text-sm text-gray-500">
                            Modifiez les informations principales de l'utilisateur.
                        </p>
                    </div>

                </div>

            </div>


            <div class="p-6 space-y-6">

                {{-- Nom + Email --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Nom --}}
                    <div>

                        <label
                            for="name"
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Nom complet
                            <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">

                            <div
                                class="absolute inset-y-0 left-0 pl-3
                                       flex items-center pointer-events-none"
                            >
                                <i
                                    data-lucide="user"
                                    class="w-5 h-5 text-gray-400"
                                ></i>
                            </div>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                autocomplete="name"
                                class="w-full pl-11 pr-4 py-3 rounded-xl
                                       border border-gray-300
                                       focus:ring-2 focus:ring-blue-500
                                       focus:border-blue-500 outline-none transition
                                       @error('name') border-red-400 @enderror"
                            >

                        </div>

                        @error('name')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Email --}}
                    <div>

                        <label
                            for="email"
                            class="block text-sm font-semibold text-gray-700 mb-2"
                        >
                            Adresse email
                            <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">

                            <div
                                class="absolute inset-y-0 left-0 pl-3
                                       flex items-center pointer-events-none"
                            >
                                <i
                                    data-lucide="mail"
                                    class="w-5 h-5 text-gray-400"
                                ></i>
                            </div>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                autocomplete="email"
                                class="w-full pl-11 pr-4 py-3 rounded-xl
                                       border border-gray-300
                                       focus:ring-2 focus:ring-blue-500
                                       focus:border-blue-500 outline-none transition
                                       @error('email') border-red-400 @enderror"
                            >

                        </div>

                        @error('email')
                            <p class="mt-1.5 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>


                {{-- Rôle --}}
                <div>

                    <label
                        for="role"
                        class="block text-sm font-semibold text-gray-700 mb-2"
                    >
                        Rôle
                        <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">

                        <div
                            class="absolute inset-y-0 left-0 pl-3
                                   flex items-center pointer-events-none z-10"
                        >
                            <i
                                data-lucide="shield-check"
                                class="w-5 h-5 text-gray-400"
                            ></i>
                        </div>

                        <select
                            id="role"
                            name="role"
                            required
                            @disabled($user->id === auth()->id())
                            class="w-full pl-11 pr-10 py-3 rounded-xl
                                   border border-gray-300 bg-white
                                   focus:ring-2 focus:ring-blue-500
                                   focus:border-blue-500 outline-none transition
                                   disabled:bg-gray-100 disabled:text-gray-500
                                   @error('role') border-red-400 @enderror"
                        >

                            @foreach ($roles as $role)

                                <option
                                    value="{{ $role->value }}"
                                    @selected(old('role', $user->role) === $role->value)
                                >
                                    {{ $role->label() }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    @if ($user->id === auth()->id())

                        <input
                            type="hidden"
                            name="role"
                            value="{{ $user->role }}"
                        >

                        <div
                            class="mt-3 flex items-start gap-2
                                   rounded-xl border border-amber-200
                                   bg-amber-50 p-3"
                        >

                            <i
                                data-lucide="triangle-alert"
                                class="w-5 h-5 text-amber-600 flex-shrink-0"
                            ></i>

                            <p class="text-sm text-amber-800">
                                Vous ne pouvez pas modifier votre propre rôle
                                administrateur.
                            </p>

                        </div>

                    @endif

                    @error('role')
                        <p class="mt-1.5 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Établissements --}}
                <div class="border border-gray-200 rounded-2xl overflow-hidden">

                    <div class="px-5 py-4 bg-gray-50 border-b border-gray-200">

                        <div class="flex items-start gap-3">

                            <div
                                class="w-10 h-10 rounded-xl bg-green-100
                                       text-green-700 flex items-center
                                       justify-center flex-shrink-0"
                            >
                                <i
                                    data-lucide="building-2"
                                    class="w-5 h-5"
                                ></i>
                            </div>

                            <div class="flex-1">

                                <div
                                    class="flex flex-col sm:flex-row
                                           sm:items-center sm:justify-between gap-2"
                                >

                                    <div>

                                        <h3 class="font-bold text-gray-800">
                                            Établissement(s) affecté(s)
                                        </h3>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Sélectionnez les établissements dans lesquels
                                            cet utilisateur pourra travailler.
                                        </p>

                                    </div>

                                    @if ($establishments->count() > 0)

                                        <button
                                            type="button"
                                            id="toggle-all-establishments"
                                            class="text-sm font-semibold text-blue-700
                                                   hover:text-blue-800"
                                        >
                                            Tout sélectionner
                                        </button>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="p-5">

                        @if ($establishments->count() > 0)

                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-4"
                            >

                                @foreach ($establishments as $establishment)

                                    @php
                                        $isAssigned = in_array(
                                            $establishment->id,
                                            old(
                                                'establishment_ids',
                                                $assignedEstablishmentIds ?? []
                                            ),
                                            true
                                        );
                                    @endphp

                                    <label
                                        class="establishment-card relative
                                               flex items-start gap-4 p-4
                                               rounded-xl border
                                               cursor-pointer transition
                                               {{ $isAssigned
                                                    ? 'border-blue-500 bg-blue-50'
                                                    : 'border-gray-200 hover:border-blue-300 hover:bg-blue-50/40' }}"
                                    >

                                        <input
                                            type="checkbox"
                                            name="establishment_ids[]"
                                            value="{{ $establishment->id }}"
                                            {{ $isAssigned ? 'checked' : '' }}
                                            class="establishment-checkbox mt-1
                                                   w-5 h-5 rounded
                                                   border-gray-300
                                                   text-blue-600
                                                   focus:ring-blue-500"
                                        >

                                        <div class="flex-1 min-w-0">

                                            <div class="flex items-center gap-2">

                                                <i
                                                    data-lucide="building"
                                                    class="w-5 h-5 text-blue-700
                                                           flex-shrink-0"
                                                ></i>

                                                <span
                                                    class="font-semibold text-gray-800"
                                                >
                                                    {{ $establishment->nom }}
                                                </span>

                                            </div>


                                            @if ($establishment->schoolType)

                                                <p class="text-sm text-gray-500 mt-1">

                                                    {{
                                                        $establishment->schoolType->nom
                                                        ?? $establishment->schoolType->name
                                                        ?? 'Type non défini'
                                                    }}

                                                </p>

                                            @endif


                                            @if ($establishment->adresse)

                                                <p
                                                    class="text-xs text-gray-400 mt-1 truncate"
                                                >
                                                    {{ $establishment->adresse }}
                                                </p>

                                            @endif

                                        </div>

                                    </label>

                                @endforeach

                            </div>


                            <div
                                class="mt-4 flex items-start gap-2
                                       p-3 rounded-xl bg-blue-50
                                       border border-blue-100"
                            >

                                <i
                                    data-lucide="info"
                                    class="w-5 h-5 text-blue-600
                                           flex-shrink-0 mt-0.5"
                                ></i>

                                <p class="text-sm text-blue-800">
                                    Un utilisateur non administrateur doit être affecté
                                    à au moins <strong>un établissement</strong>.
                                </p>

                            </div>

                        @else

                            <div class="text-center py-8">

                                <div
                                    class="w-14 h-14 mx-auto rounded-full
                                           bg-gray-100 flex items-center
                                           justify-center"
                                >
                                    <i
                                        data-lucide="building-2"
                                        class="w-7 h-7 text-gray-400"
                                    ></i>
                                </div>

                                <h3 class="mt-4 font-semibold text-gray-800">
                                    Aucun établissement disponible
                                </h3>

                                <p
                                    class="mt-1 text-sm text-gray-500
                                           max-w-md mx-auto"
                                >
                                    Aucun établissement appartenant à
                                    l'administrateur connecté n'est disponible.
                                </p>

                                <a
                                    href="{{ route('establishments.create') }}"
                                    class="inline-flex items-center gap-2
                                           mt-4 px-4 py-2.5 rounded-xl
                                           bg-blue-700 text-white font-medium
                                           hover:bg-blue-800 transition"
                                >
                                    <i
                                        data-lucide="plus"
                                        class="w-5 h-5"
                                    ></i>

                                    Créer un établissement
                                </a>

                            </div>

                        @endif


                        @error('establishment_ids')
                            <p class="mt-3 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        @error('establishment_ids.*')
                            <p class="mt-3 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>


                {{-- Mot de passe --}}
                <div class="border-t border-gray-200 pt-6">

                    <div class="mb-4">

                        <h3 class="text-lg font-bold text-gray-800">
                            Sécurité du compte
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Laissez les champs vides si vous ne souhaitez pas
                            modifier le mot de passe actuel.
                        </p>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- Nouveau mot de passe --}}
                        <div>

                            <label
                                for="password"
                                class="block text-sm font-semibold
                                       text-gray-700 mb-2"
                            >
                                Nouveau mot de passe
                            </label>

                            <div class="relative">

                                <div
                                    class="absolute inset-y-0 left-0 pl-3
                                           flex items-center pointer-events-none"
                                >
                                    <i
                                        data-lucide="lock"
                                        class="w-5 h-5 text-gray-400"
                                    ></i>
                                </div>

                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    autocomplete="new-password"
                                    placeholder="Minimum 8 caractères"
                                    class="w-full pl-11 pr-12 py-3
                                           rounded-xl border border-gray-300
                                           focus:ring-2 focus:ring-blue-500
                                           focus:border-blue-500
                                           outline-none transition
                                           @error('password') border-red-400 @enderror"
                                >

                                <button
                                    type="button"
                                    class="password-toggle absolute
                                           inset-y-0 right-0 px-3
                                           text-gray-400 hover:text-gray-600"
                                    data-target="password"
                                    aria-label="Afficher le mot de passe"
                                >
                                    <i
                                        data-lucide="eye"
                                        class="w-5 h-5"
                                    ></i>
                                </button>

                            </div>

                            @error('password')
                                <p class="mt-1.5 text-sm text-red-600">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>


                        {{-- Confirmation --}}
                        <div>

                            <label
                                for="password_confirmation"
                                class="block text-sm font-semibold
                                       text-gray-700 mb-2"
                            >
                                Confirmer le nouveau mot de passe
                            </label>

                            <div class="relative">

                                <div
                                    class="absolute inset-y-0 left-0 pl-3
                                           flex items-center pointer-events-none"
                                >
                                    <i
                                        data-lucide="lock-keyhole"
                                        class="w-5 h-5 text-gray-400"
                                    ></i>
                                </div>

                                <input
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    autocomplete="new-password"
                                    placeholder="Répétez le nouveau mot de passe"
                                    class="w-full pl-11 pr-12 py-3
                                           rounded-xl border border-gray-300
                                           focus:ring-2 focus:ring-blue-500
                                           focus:border-blue-500
                                           outline-none transition"
                                >

                                <button
                                    type="button"
                                    class="password-toggle absolute
                                           inset-y-0 right-0 px-3
                                           text-gray-400 hover:text-gray-600"
                                    data-target="password_confirmation"
                                    aria-label="Afficher la confirmation"
                                >
                                    <i
                                        data-lucide="eye"
                                        class="w-5 h-5"
                                    ></i>
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Actions --}}
            <div
                class="px-6 py-5 bg-gray-50 border-t border-gray-200
                       flex flex-col sm:flex-row sm:items-center
                       sm:justify-end gap-3"
            >

                <a
                    href="{{ route('users.index') }}"
                    class="inline-flex items-center justify-center gap-2
                           px-5 py-3 rounded-xl border border-gray-300
                           bg-white text-gray-700 font-medium
                           hover:bg-gray-100 transition"
                >
                    <i data-lucide="x" class="w-5 h-5"></i>
                    Annuler
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2
                           px-6 py-3 rounded-xl bg-blue-700
                           text-white font-semibold
                           hover:bg-blue-800 transition shadow-sm"
                >
                    <i data-lucide="save" class="w-5 h-5"></i>
                    Enregistrer les modifications
                </button>

            </div>

        </div>

    </form>

</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
     * Initialisation Lucide
     */
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }


    /*
     * Afficher / masquer les mots de passe
     */
    document.querySelectorAll('.password-toggle').forEach(function (button) {

        button.addEventListener('click', function () {

            const targetId = button.dataset.target;
            const input = document.getElementById(targetId);

            if (!input) {
                return;
            }

            const icon = button.querySelector('i');

            if (input.type === 'password') {

                input.type = 'text';

                button.setAttribute(
                    'aria-label',
                    'Masquer le mot de passe'
                );

                if (icon) {
                    icon.setAttribute('data-lucide', 'eye-off');
                }

            } else {

                input.type = 'password';

                button.setAttribute(
                    'aria-label',
                    'Afficher le mot de passe'
                );

                if (icon) {
                    icon.setAttribute('data-lucide', 'eye');
                }
            }

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

        });

    });


    /*
     * Sélection / désélection de tous les établissements
     */
    const toggleAllButton = document.getElementById(
        'toggle-all-establishments'
    );

    const establishmentCheckboxes = document.querySelectorAll(
        '.establishment-checkbox'
    );


    if (toggleAllButton && establishmentCheckboxes.length > 0) {

        toggleAllButton.addEventListener('click', function () {

            const allChecked = Array.from(
                establishmentCheckboxes
            ).every(function (checkbox) {
                return checkbox.checked;
            });


            establishmentCheckboxes.forEach(function (checkbox) {

                checkbox.checked = !allChecked;

                updateEstablishmentCard(checkbox);

            });


            toggleAllButton.textContent = allChecked
                ? 'Tout sélectionner'
                : 'Tout désélectionner';

        });


        establishmentCheckboxes.forEach(function (checkbox) {

            checkbox.addEventListener('change', function () {

                updateEstablishmentCard(checkbox);


                const allChecked = Array.from(
                    establishmentCheckboxes
                ).every(function (item) {
                    return item.checked;
                });


                toggleAllButton.textContent = allChecked
                    ? 'Tout désélectionner'
                    : 'Tout sélectionner';

            });


            updateEstablishmentCard(checkbox);

        });

    }


    /*
     * Apparence des cartes sélectionnées
     */
    function updateEstablishmentCard(checkbox) {

        const card = checkbox.closest('.establishment-card');

        if (!card) {
            return;
        }


        if (checkbox.checked) {

            card.classList.add(
                'border-blue-500',
                'bg-blue-50'
            );

            card.classList.remove(
                'border-gray-200'
            );

        } else {

            card.classList.remove(
                'border-blue-500',
                'bg-blue-50'
            );

            card.classList.add(
                'border-gray-200'
            );

        }

    }

});
</script>
@endpush