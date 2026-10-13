@extends('layouts.gestion-ecole')

@section('title', 'Créer un utilisateur')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- En-tête --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">

                <a href="{{ route('users.index') }}"
                   class="inline-flex items-center justify-center w-10 h-10 rounded-xl
                          bg-gray-100 text-gray-600 hover:bg-gray-200 transition"
                   title="Retour">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>

                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        Créer un utilisateur
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Créez un compte rattaché automatiquement à l’établissement actuel.
                    </p>
                </div>

            </div>
        </div>

        <a href="{{ route('users.index') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5
                  rounded-xl border border-gray-200 bg-white text-gray-700
                  hover:bg-gray-50 transition font-medium shadow-sm">

            <i data-lucide="users" class="w-5 h-5"></i>

            Liste des utilisateurs
        </a>
    </div>


    {{-- Messages d'erreur --}}
    @if ($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <div class="flex items-start gap-3">

                <div class="flex-shrink-0">
                    <i data-lucide="circle-alert"
                       class="w-5 h-5 text-red-600"></i>
                </div>

                <div>

                    <h3 class="font-semibold text-red-800">
                        Impossible de créer l'utilisateur
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
    <form action="{{ route('users.store') }}"
          method="POST"
          id="user-create-form">

        @csrf

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">


            {{-- Section informations --}}
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700
                                flex items-center justify-center">

                        <i data-lucide="user-plus"
                           class="w-5 h-5"></i>

                    </div>

                    <div>

                        <h2 class="text-lg font-bold text-gray-800">
                            Informations du compte
                        </h2>

                        <p class="text-sm text-gray-500">
                            Informations utilisées pour la connexion.
                        </p>

                    </div>

                </div>

            </div>


            <div class="p-6 space-y-6">


                {{-- Nom + Email --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                    {{-- Nom --}}
                    <div>

                        <label for="name"
                               class="block text-sm font-semibold text-gray-700 mb-2">

                            Nom complet
                            <span class="text-red-500">*</span>

                        </label>

                        <div class="relative">

                            <div class="absolute inset-y-0 left-0 pl-3
                                        flex items-center pointer-events-none">

                                <i data-lucide="user"
                                   class="w-5 h-5 text-gray-400"></i>

                            </div>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autocomplete="name"
                                placeholder="Ex. Nom"
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300
                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       outline-none transition
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

                        <label for="email"
                               class="block text-sm font-semibold text-gray-700 mb-2">

                            Adresse email
                            <span class="text-red-500">*</span>

                        </label>

                        <div class="relative">

                            <div class="absolute inset-y-0 left-0 pl-3
                                        flex items-center pointer-events-none">

                                <i data-lucide="mail"
                                   class="w-5 h-5 text-gray-400"></i>

                            </div>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                placeholder="nom@gmail.com"
                                class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-300
                                       focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                       outline-none transition
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

                    <label for="role"
                           class="block text-sm font-semibold text-gray-700 mb-2">

                        Rôle
                        <span class="text-red-500">*</span>

                    </label>

                    <div class="relative">

                        <div class="absolute inset-y-0 left-0 pl-3
                                    flex items-center pointer-events-none z-10">

                            <i data-lucide="shield-check"
                               class="w-5 h-5 text-gray-400"></i>

                        </div>

                        <select
                            id="role"
                            name="role"
                            required
                            class="w-full pl-11 pr-10 py-3 rounded-xl border border-gray-300
                                   bg-white focus:ring-2 focus:ring-blue-500
                                   focus:border-blue-500 outline-none transition
                                   @error('role') border-red-400 @enderror"
                        >

                            <option value="">
                                -- Sélectionner un rôle --
                            </option>

                            @foreach ($roles as $role)

                                <option
                                    value="{{ $role->value }}"
                                    {{ old('role') === $role->value ? 'selected' : '' }}
                                >
                                    {{ $role->label() }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <p class="mt-2 text-xs text-gray-500">
                        Le rôle détermine les fonctionnalités auxquelles
                        l'utilisateur pourra accéder.
                    </p>

                    @error('role')
                        <p class="mt-1.5 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Établissement courant --}}
                <div class="border border-green-200 rounded-2xl overflow-hidden">


                    <div class="px-5 py-4 bg-green-50 border-b border-green-200">

                        <div class="flex items-start gap-3">

                            <div class="w-10 h-10 rounded-xl bg-green-100
                                        text-green-700 flex items-center
                                        justify-center flex-shrink-0">

                                <i data-lucide="building-2"
                                   class="w-5 h-5"></i>

                            </div>


                            <div>

                                <h3 class="font-bold text-gray-800">
                                    Établissement de l'utilisateur
                                </h3>

                                <p class="text-sm text-gray-600 mt-1">
                                    L'utilisateur non administrateur sera
                                    automatiquement rattaché à cet établissement.
                                </p>

                            </div>

                        </div>

                    </div>


                    <div class="p-5">

                        <div class="flex items-start gap-4 p-5 rounded-xl
                                    border border-green-200 bg-white shadow-sm">

                            <div class="w-12 h-12 rounded-xl bg-blue-100
                                        text-blue-700 flex items-center
                                        justify-center flex-shrink-0">

                                <i data-lucide="building"
                                   class="w-6 h-6"></i>

                            </div>


                            <div class="flex-1 min-w-0">

                                <div class="flex flex-wrap items-center gap-2">

                                    <h4 class="text-lg font-bold text-gray-800">
                                        {{ $establishment->nom }}
                                    </h4>

                                    <span class="inline-flex items-center gap-1
                                                 px-2.5 py-1 rounded-full
                                                 bg-green-100 text-green-700
                                                 text-xs font-semibold">

                                        <i data-lucide="check-circle"
                                           class="w-3.5 h-3.5"></i>

                                        Établissement actuel

                                    </span>

                                </div>


                                @if ($establishment->schoolType)

                                    <p class="mt-2 text-sm font-semibold text-blue-700">

                                        <i data-lucide="graduation-cap"
                                           class="w-4 h-4 inline-block align-text-bottom mr-1"></i>

                                        {{ $establishment->schoolType->nom
                                            ?? $establishment->schoolType->name
                                            ?? 'Type non défini' }}

                                    </p>

                                @else

                                    <p class="mt-2 text-sm text-red-600">

                                        <i data-lucide="triangle-alert"
                                           class="w-4 h-4 inline-block align-text-bottom mr-1"></i>

                                        Type d'établissement non défini.

                                    </p>

                                @endif


                                @if ($establishment->adresse)

                                    <p class="mt-2 text-sm text-gray-500">

                                        <i data-lucide="map-pin"
                                           class="w-4 h-4 inline-block align-text-bottom mr-1"></i>

                                        {{ $establishment->adresse }}

                                    </p>

                                @endif

                            </div>

                        </div>


                        <div class="mt-4 flex items-start gap-2 p-3 rounded-xl
                                    bg-blue-50 border border-blue-100">

                            <i data-lucide="info"
                               class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>

                            <p class="text-sm text-blue-800">

                                Pour un
                                <strong>secrétaire</strong>,
                                enseignant ou autre utilisateur non administrateur,
                                cet établissement sera affecté
                                <strong>automatiquement</strong>.

                            </p>

                        </div>

                    </div>

                </div>


                {{-- Mot de passe --}}
                <div class="border-t border-gray-200 pt-6">

                    <div class="mb-4">

                        <h3 class="text-lg font-bold text-gray-800">
                            Sécurité du compte
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Définissez le mot de passe utilisé pour la connexion.
                        </p>

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                        {{-- Password --}}
                        <div>

                            <label for="password"
                                   class="block text-sm font-semibold text-gray-700 mb-2">

                                Mot de passe
                                <span class="text-red-500">*</span>

                            </label>

                            <div class="relative">

                                <div class="absolute inset-y-0 left-0 pl-3
                                            flex items-center pointer-events-none">

                                    <i data-lucide="lock"
                                       class="w-5 h-5 text-gray-400"></i>

                                </div>

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Minimum 8 caractères"
                                    class="w-full pl-11 pr-12 py-3 rounded-xl border border-gray-300
                                           focus:ring-2 focus:ring-blue-500
                                           focus:border-blue-500 outline-none transition
                                           @error('password') border-red-400 @enderror"
                                >

                                <button
                                    type="button"
                                    class="password-toggle absolute inset-y-0 right-0 px-3
                                           text-gray-400 hover:text-gray-600"
                                    data-target="password"
                                    aria-label="Afficher le mot de passe"
                                >

                                    <i data-lucide="eye"
                                       class="w-5 h-5"></i>

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

                            <label for="password_confirmation"
                                   class="block text-sm font-semibold text-gray-700 mb-2">

                                Confirmer le mot de passe
                                <span class="text-red-500">*</span>

                            </label>

                            <div class="relative">

                                <div class="absolute inset-y-0 left-0 pl-3
                                            flex items-center pointer-events-none">

                                    <i data-lucide="lock-keyhole"
                                       class="w-5 h-5 text-gray-400"></i>

                                </div>

                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    placeholder="Répétez le mot de passe"
                                    class="w-full pl-11 pr-12 py-3 rounded-xl border border-gray-300
                                           focus:ring-2 focus:ring-blue-500
                                           focus:border-blue-500 outline-none transition"
                                >

                                <button
                                    type="button"
                                    class="password-toggle absolute inset-y-0 right-0 px-3
                                           text-gray-400 hover:text-gray-600"
                                    data-target="password_confirmation"
                                    aria-label="Afficher la confirmation"
                                >

                                    <i data-lucide="eye"
                                       class="w-5 h-5"></i>

                                </button>

                            </div>

                        </div>

                    </div>


                    <div class="mt-4 flex items-start gap-2 text-sm text-gray-500">

                        <i data-lucide="shield-check"
                           class="w-5 h-5 text-green-600 flex-shrink-0"></i>

                        <p>
                            Le mot de passe doit contenir au minimum
                            <strong>8 caractères</strong>.
                        </p>

                    </div>

                </div>

            </div>


            {{-- Actions --}}
            <div class="px-6 py-5 bg-gray-50 border-t border-gray-200
                        flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">

                <a
                    href="{{ route('users.index') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3
                           rounded-xl border border-gray-300 bg-white text-gray-700
                           font-medium hover:bg-gray-100 transition"
                >

                    <i data-lucide="x" class="w-5 h-5"></i>

                    Annuler

                </a>


                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3
                           rounded-xl bg-blue-700 text-white font-semibold
                           hover:bg-blue-800 transition shadow-sm"
                >

                    <i data-lucide="user-plus"
                       class="w-5 h-5"></i>

                    Créer le compte

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
                    icon.setAttribute(
                        'data-lucide',
                        'eye-off'
                    );
                }

            } else {

                input.type = 'password';

                button.setAttribute(
                    'aria-label',
                    'Afficher le mot de passe'
                );

                if (icon) {
                    icon.setAttribute(
                        'data-lucide',
                        'eye'
                    );
                }
            }

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

        });

    });

});
</script>
@endpush
