<div class="min-h-screen bg-gray-50 px-4 py-5 sm:px-6 lg:px-8">

    {{-- EN-TÊTE DE LA PAGE --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-900 text-white shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.1a7.5 7.5 0 0 1 15 0A17.9 17.9 0 0 1 12 21.75 17.9 17.9 0 0 1 4.5 20.1Z"/>
                </svg>
            </div>

            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Mon profil
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Gérez vos informations personnelles et votre photo de profil.
                </p>
            </div>
        </div>

        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-4 w-4"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="1.8">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M15 19l-7-7 7-7M8 12h13"/>
            </svg>
            Retour au tableau de bord
        </a>
    </div>

    {{-- IDENTITÉ DU COMPTE --}}
    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 bg-white px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m6-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm14 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">
                        Informations du compte
                    </h2>
                    <p class="text-sm text-gray-500">
                        Votre identité sur OPTIMASCHOOL
                    </p>
                </div>
            </div>
        </div>

        <div class="p-5 sm:p-6">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-full border-2 border-blue-100 bg-blue-100 text-2xl font-bold text-blue-900">
                    @if (auth()->user()->profilePhotoUrl())
                        <img
                            src="{{ auth()->user()->profilePhotoUrl() }}"
                            alt="Photo de profil actuelle"
                            class="h-full w-full object-cover"
                        >
                    @else
                        {{ auth()->user()->initials() }}
                    @endif
                </div>

                <div class="min-w-0">
                    <p class="break-words text-xl font-bold text-gray-900">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="mt-1 break-all text-sm text-gray-500">
                        {{ auth()->user()->email }}
                    </p>
                    <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                        Compte connecté
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- PHOTO DE PROFIL --}}
    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 text-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M4 7h3l2-3h6l2 3h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2Zm8 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">
                        Photo de profil
                    </h2>
                    <p class="text-sm text-gray-500">
                        Personnalisez votre compte avec une photo.
                    </p>
                </div>
            </div>
        </div>

        <div class="p-5 sm:p-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-start">
                <div class="flex shrink-0 flex-col items-center gap-3 sm:flex-row sm:items-start">
                    @if ($photo)
                        <img
                            src="{{ $photo->temporaryUrl() }}"
                            alt="Aperçu de la nouvelle photo"
                            class="h-28 w-28 rounded-full border-4 border-blue-100 object-cover shadow-sm"
                        >
                    @elseif (auth()->user()->profilePhotoUrl())
                        <img
                            src="{{ auth()->user()->profilePhotoUrl() }}"
                            alt="Photo de profil"
                            class="h-28 w-28 rounded-full border-4 border-blue-100 object-cover shadow-sm"
                        >
                    @else
                        <div class="flex h-28 w-28 items-center justify-center rounded-full border-4 border-blue-100 bg-blue-50 text-3xl font-bold text-blue-900">
                            {{ auth()->user()->initials() }}
                        </div>
                    @endif

                    <div class="text-center sm:pt-3 sm:text-left">
                        <p class="font-semibold text-gray-900">
                            Votre photo
                        </p>
                        <p class="mt-1 text-sm text-gray-500">
                            JPG, JPEG, PNG ou WEBP
                        </p>
                        <p class="text-sm text-gray-500">
                            Taille maximale : 2 Mo
                        </p>
                    </div>
                </div>

                <div class="min-w-0 flex-1">
                    <form wire:submit="updateProfilePhoto" class="space-y-4">
                        <label for="profile-photo"
                               class="block text-sm font-semibold text-gray-700">
                            Choisir une nouvelle photo
                        </label>

                        <input
                            id="profile-photo"
                            type="file"
                            wire:model="photo"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            class="block w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:border-0 file:bg-blue-50 file:px-4 file:py-3 file:font-semibold file:text-blue-900 hover:file:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >

                        @error('photo')
                            <p class="text-sm font-medium text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        <div wire:loading wire:target="photo"
                             class="text-sm font-medium text-blue-700">
                            Chargement de la photo en cours...
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="photo,updateProfilePhoto"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-4 w-4"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M5 13l4 4L19 7"/>
                                </svg>
                                Enregistrer la photo
                            </button>

                            @if (auth()->user()->profile_photo_path)
                                <button
                                    type="button"
                                    wire:click="deleteProfilePhoto"
                                    wire:confirm="Voulez-vous vraiment supprimer votre photo de profil ?"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-50"
                                >
                                    Supprimer la photo
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODIFICATION DES INFORMATIONS --}}
    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-800">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M16.862 4.487 19.5 7.125M4 20l4.5-1 10.2-10.2a1.87 1.87 0 0 0-2.65-2.65L5.85 16.35 4 20ZM13 6H5a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900">
                        Informations personnelles
                    </h2>
                    <p class="text-sm text-gray-500">
                        Modifiez votre nom et votre adresse e-mail.
                    </p>
                </div>
            </div>
        </div>

        <div class="p-5 sm:p-6">
            <form wire:submit="updateProfileInformation" class="max-w-2xl space-y-5">
                <div>
                    <label for="profile-name" class="mb-2 block text-sm font-semibold text-gray-700">
                        Nom complet
                    </label>
                    <input
                        id="profile-name"
                        wire:model="name"
                        type="text"
                        required
                        autocomplete="name"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-blue-700 focus:ring-2 focus:ring-blue-100"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="profile-email" class="mb-2 block text-sm font-semibold text-gray-700">
                        Adresse e-mail
                    </label>
                    <input
                        id="profile-email"
                        wire:model="email"
                        type="email"
                        required
                        autocomplete="email"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-blue-700 focus:ring-2 focus:ring-blue-100"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if ($this->hasUnverifiedEmail)
                        <div class="mt-3 rounded-lg border border-amber-200 bg-amber-50 p-4">
                            <p class="text-sm text-amber-800">
                                Votre adresse e-mail n'est pas encore vérifiée.
                            </p>
                            <button
                                type="button"
                                wire:click.prevent="resendVerificationNotification"
                                class="mt-2 text-sm font-semibold text-blue-900 underline hover:text-blue-700"
                            >
                                Renvoyer l'e-mail de vérification
                            </button>
                        </div>
                    @endif
                </div>

                <div class="border-t border-gray-100 pt-5">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SUPPRESSION DU COMPTE : COMPOSANT EXISTANT CONSERVÉ --}}
    @if ($this->showDeleteUser)
        <div class="overflow-hidden rounded-xl border border-red-200 bg-white shadow-sm">
            <div class="border-b border-red-100 bg-red-50 px-5 py-4 sm:px-6">
                <h2 class="text-base font-bold text-red-800">
                    Zone de danger
                </h2>
                <p class="mt-1 text-sm text-red-700">
                    La suppression du compte est une action sensible.
                </p>
            </div>
            <div class="p-5 sm:p-6">
                <livewire:settings.delete-user-form />
            </div>
        </div>
    @endif

</div>
