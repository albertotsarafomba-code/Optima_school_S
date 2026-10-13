<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Connexion - OPTIMASCHOOL</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e3a8a',
                        secondary: '#16a34a',
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-white">

    <div class="min-h-screen grid grid-cols-1 md:grid-cols-2">

        <!-- =====================================================
             PARTIE GAUCHE : BLANC
        ====================================================== -->
        <div class="bg-white min-h-screen flex items-center justify-center p-6 md:p-10">

            <div class="w-full max-w-md">

                <!-- Logo -->
                <div class="flex items-center gap-3 mb-10">

                    <div class="w-16 h-16 rounded-xl bg-white border border-slate-200 flex items-center justify-center shadow-md overflow-hidden">

                        <img
                            src="{{ asset('images/LOGO_optima.jpg') }}"
                            alt="OPTIMASCHOOL"
                            class="w-full h-full object-contain p-1"
                        >

                    </div>

                    <div>
                        <h1 class="text-2xl font-extrabold tracking-wide text-blue-700">
                            OPTIMASCHOOL
                        </h1>

                        <p class="text-red-600 font-semibold text-sm">
                            Gestion d'école
                        </p>
                    </div>

                </div>


                <!-- Message de bienvenue -->
                <div class="mb-8">

                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-50 border border-green-500 text-green-700 text-xs font-bold mb-4">

                        <svg
                            class="w-4 h-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="M5 12l4 4L19 6"/>
                        </svg>

                        Plateforme scolaire

                    </div>

                    <h2 class="text-3xl md:text-4xl font-extrabold leading-tight text-blue-900">
                        Bienvenue sur
                        <span class="text-green-600">
                            OPTIMASCHOOL
                        </span>
                    </h2>

                    <p class="mt-4 text-sm leading-6 text-slate-600">
                        Connectez-vous pour gérer efficacement
                        votre établissement, vos étudiants,
                        vos enseignants et vos activités scolaires.
                    </p>

                </div>


                <!-- Fonctionnalités -->
                <div class="space-y-5">

                    <!-- Établissements -->
                    <div class="flex items-center gap-4">

                        <div class="w-10 h-10 rounded-lg bg-blue-50 border border-blue-200 flex items-center justify-center shrink-0">

                            <svg
                                class="w-5 h-5 text-blue-700"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M3 10l9-7 9 7"/>
                                <path d="M5 10v10h14V10"/>
                                <path d="M9 20v-6h6v6"/>
                            </svg>

                        </div>

                        <div>
                            <p class="text-sm font-bold text-blue-900">
                                Gestion des établissements
                            </p>

                            <p class="text-xs font-medium text-slate-500">
                                Organisez vos établissements facilement
                            </p>
                        </div>

                    </div>


                    <!-- Utilisateurs -->
                    <div class="flex items-center gap-4">

                        <div class="w-10 h-10 rounded-lg bg-green-50 border border-green-200 flex items-center justify-center shrink-0">

                            <svg
                                class="w-5 h-5 text-green-700"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>

                        </div>

                        <div>
                            <p class="text-sm font-bold text-blue-900">
                                Gestion des utilisateurs
                            </p>

                            <p class="text-xs font-medium text-slate-500">
                                Étudiants, enseignants et administrateurs
                            </p>
                        </div>

                    </div>


                    <!-- Académique -->
                    <div class="flex items-center gap-4">

                        <div class="w-10 h-10 rounded-lg bg-blue-50 border border-blue-200 flex items-center justify-center shrink-0">

                            <svg
                                class="w-5 h-5 text-blue-700"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <rect x="4" y="4" width="16" height="16" rx="2"/>
                                <path d="M8 8h8"/>
                                <path d="M8 12h8"/>
                                <path d="M8 16h5"/>
                            </svg>

                        </div>

                        <div>
                            <p class="text-sm font-bold text-blue-900">
                                Gestion académique
                            </p>

                            <p class="text-xs font-medium text-slate-500">
                                Classes, matières, notes et inscriptions
                            </p>
                        </div>

                    </div>

                </div>


                <!-- Footer -->
                <div class="mt-12 text-xs text-slate-400 font-semibold">
                    © {{ date('Y') }} OPTIMASCHOOL
                </div>

            </div>

        </div>


        <!-- =====================================================
             PARTIE DROITE : BLEU
        ====================================================== -->
        <div class="bg-[#1e3a8a] min-h-screen flex items-center justify-center p-6 md:p-10">

            <div class="w-full max-w-md">

                <!-- Logo / titre sur fond bleu -->
                <div class="text-center mb-8">

                    <div class="mx-auto w-20 h-20 rounded-2xl bg-white flex items-center justify-center shadow-xl overflow-hidden mb-5">

                        <img
                            src="{{ asset('images/LOGO_optima.jpg') }}"
                            alt="OPTIMASCHOOL"
                            class="w-full h-full object-contain p-1"
                        >

                    </div>

                    <h2 class="text-3xl font-extrabold text-white tracking-wide">
                        CONNEXION
                    </h2>

                    <p class="text-sm font-medium text-blue-100 mt-2">
                        Connectez-vous à votre espace administrateur
                    </p>

                </div>


                <!-- Carte formulaire -->
                <div class="bg-white rounded-2xl shadow-2xl p-7 md:p-8">

                    <!-- Erreurs -->
                    @if ($errors->any())

                        <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-3">

                            <div class="flex gap-2">

                                <svg
                                    class="w-5 h-5 text-red-500 shrink-0"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <circle cx="12" cy="12" r="9"/>
                                    <path d="M12 8v4"/>
                                    <path d="M12 16h.01"/>
                                </svg>

                                <div class="text-sm text-red-700">

                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach

                                </div>

                            </div>

                        </div>

                    @endif


                    <!-- Formulaire -->
                    <form
                        method="POST"
                        action="{{ route('login') }}"
                        class="space-y-5"
                        data-optimaconfirm-login
                    >

                        @csrf


                        <!-- Email -->
                        <div>

                            <label
                                for="email"
                                class="block text-sm font-bold text-blue-900 mb-2"
                            >
                                Adresse e-mail
                            </label>

                            <div class="relative">

                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                                    <svg
                                        class="w-5 h-5 text-slate-500"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                                        <path d="M3 7l9 6 9-6"/>
                                    </svg>

                                </div>

                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="email"
                                    placeholder="nom@gmail.com"
                                    class="w-full h-11 pl-10 pr-4 rounded-lg border border-slate-300 bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none text-sm text-slate-900 transition"
                                >

                            </div>

                        </div>


                        <!-- Password -->
                        <div>

                            <div class="flex items-center justify-between mb-2">

                                <label
                                    for="password"
                                    class="text-sm font-bold text-blue-900"
                                >
                                    Mot de passe
                                </label>

                                @if (Route::has('password.request'))

                                    <a
                                        href="{{ route('password.request') }}"
                                        class="text-xs font-bold text-blue-700 hover:text-green-600 transition"
                                    >
                                        Mot de passe oublié ?
                                    </a>

                                @endif

                            </div>


                            <div class="relative">

                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                                    <svg
                                        class="w-5 h-5 text-slate-500"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <rect x="4" y="10" width="16" height="11" rx="2"/>
                                        <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                                    </svg>

                                </div>


                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Votre mot de passe"
                                    class="w-full h-11 pl-10 pr-11 rounded-lg border border-slate-300 bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none text-sm text-slate-900 transition"
                                >


                                <button
                                    type="button"
                                    id="toggleLoginPassword"
                                    class="absolute inset-y-0 right-0 px-3 text-slate-500 hover:text-blue-700 transition"
                                    aria-label="Afficher le mot de passe"
                                >

                                    <svg
                                        id="loginEye"
                                        class="w-5 h-5"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>

                                </button>

                            </div>

                        </div>


                        <!-- Remember -->
                        <div class="flex items-center">

                            <label class="inline-flex items-center gap-2 cursor-pointer">

                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="w-4 h-4 rounded border-slate-300 text-blue-700 focus:ring-blue-500"
                                >

                                <span class="text-sm font-medium text-slate-600">
                                    Se souvenir de moi
                                </span>

                            </label>

                        </div>


                        <!-- Button -->
                        <button
                            type="submit"
                            class="w-full h-11 rounded-lg bg-[#1e3a8a] hover:bg-blue-950 text-white font-semibold text-sm shadow-md hover:shadow-lg transition flex items-center justify-center gap-2"
                        >

                            <svg
                                class="w-5 h-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                <path d="M10 17l5-5-5-5"/>
                                <path d="M15 12H3"/>
                            </svg>

                            Se connecter

                        </button>

                    </form>


                    <!-- Register -->
                    @if (Route::has('register'))

                        <div class="mt-6 text-center">

                            <span class="text-sm text-slate-500">
                                Vous n'avez pas encore de compte ?
                            </span>

                            <a
                                href="{{ route('register') }}"
                                class="text-sm font-bold text-blue-900 hover:text-green-600 transition ml-1"
                            >
                                Créer un compte
                            </a>

                        </div>

                    @endif

                </div>


                <!-- Footer -->
                <div class="text-center mt-6">

                    <p class="text-xs text-blue-200">
                        OPTIMASCHOOL — Plateforme de gestion scolaire
                    </p>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         PASSWORD VISIBILITY
    ====================================================== -->
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const password = document.getElementById('password');
            const toggle = document.getElementById('toggleLoginPassword');
            const eye = document.getElementById('loginEye');

            if (!password || !toggle || !eye) {
                return;
            }

            toggle.addEventListener('click', function () {

                const visible = password.type === 'text';

                password.type = visible ? 'password' : 'text';

                toggle.setAttribute(
                    'aria-label',
                    visible
                        ? 'Afficher le mot de passe'
                        : 'Masquer le mot de passe'
                );

                if (visible) {

                    eye.innerHTML = `
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                        <circle cx="12" cy="12" r="3"/>
                    `;

                } else {

                    eye.innerHTML = `
                        <path d="M3 3l18 18"/>
                        <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                        <path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c7 0 10 8 10 8a17.7 17.7 0 0 1-3.1 4.3"/>
                        <path d="M6.2 6.2C3.6 8.1 2 12 2 12s3 7 10 7c1.7 0 3.2-.4 4.5-1.1"/>
                    `;

                }

            });

        });

    </script>


    {{-- MODALE DE CONFIRMATION DE CONNEXION OPTIMASCHOOL --}}
    <div
        id="optimaconfirm-login-modal"
        class="fixed inset-0 z-[10000] hidden items-center justify-center bg-black/75 p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="optimaconfirm-login-title"
        aria-describedby="optimaconfirm-login-description"
    >
        <div class="w-full max-w-md overflow-hidden rounded-2xl border border-blue-600 bg-black text-white shadow-2xl">

            <div class="flex items-start gap-4 border-b border-blue-900 p-6">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-600/20 text-blue-400">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                    </svg>
                </div>

                <div class="min-w-0 flex-1">
                    <h2 id="optimaconfirm-login-title" class="text-lg font-extrabold text-blue-400">
                        Confirmer la connexion
                    </h2>

                    <p id="optimaconfirm-login-description" class="mt-2 text-sm leading-6 text-slate-300">
                        Voulez-vous continuer et vous connecter à votre espace OPTIMASCHOOL ?
                    </p>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-blue-900 bg-black p-5 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    id="optimaconfirm-login-cancel"
                    class="inline-flex items-center justify-center rounded-xl border border-green-500 bg-green-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400"
                >
                    Annuler
                </button>

                <button
                    type="button"
                    id="optimaconfirm-login-confirm"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-blue-500 bg-blue-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-black"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                    </svg>
                    Oui, me connecter
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('optimaconfirm-login-modal');
            const cancelButton = document.getElementById('optimaconfirm-login-cancel');
            const confirmButton = document.getElementById('optimaconfirm-login-confirm');

            if (!modal || !cancelButton || !confirmButton) return;

            let pendingForm = null;
            let previousFocus = null;

            function openModal(form) {
                pendingForm = form;
                previousFocus = document.activeElement;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');

                cancelButton.focus();
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');

                pendingForm = null;

                if (previousFocus && typeof previousFocus.focus === 'function') {
                    previousFocus.focus();
                }
            }

            document.querySelectorAll('form[data-optimaconfirm-login]').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.dataset.loginConfirmed === 'true') {
                        return;
                    }

                    event.preventDefault();
                    openModal(form);
                });
            });

            cancelButton.addEventListener('click', closeModal);

            confirmButton.addEventListener('click', function () {
                if (!pendingForm) return;

                const form = pendingForm;

                form.dataset.loginConfirmed = 'true';
                confirmButton.disabled = true;
                confirmButton.textContent = 'Connexion en cours...';

                HTMLFormElement.prototype.submit.call(form);
            });

            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>

</body>
</html>
