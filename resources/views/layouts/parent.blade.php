<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Espace famille - OptimaSchool')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">

    {{-- HEADER SIMPLE --}}
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex min-h-20 max-w-7xl items-center justify-between px-6">

            <div class="flex items-center gap-3">
                <img
                    src="{{ asset('images/optimaschool-logo.png') }}"
                    alt="OptimaSchool"
                    class="h-12 w-auto object-contain"
                >

                <div class="hidden sm:block border-l border-slate-200 pl-3">
                    <p class="text-sm font-semibold text-green-600">
                        Espace famille
                    </p>
                    <p class="text-xs text-slate-500">
                        Consultation scolaire
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">

                @include('notifications.bell')

                <span class="hidden text-sm font-semibold text-slate-700 sm:block">
                    {{ auth()->user()->name ?? 'Parent' }}
                </span>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    data-optimaconfirm-logout
                >
                    @csrf

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12H3m0 0l4-4m-4 4l4 4"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 5V4a1 1 0 011-1h6a1 1 0 011 1v16a1 1 0 01-1 1h-6a1 1 0 01-1-1v-1"/>
                        </svg>

                        Déconnexion
                    </button>
                </form>

            </div>

        </div>
    </header>


    {{-- CONTENU DE LA PAGE --}}
    <main class="min-h-[calc(100vh-80px)]">
        @yield('content')
    </main>


    {{-- MODALE DE CONFIRMATION DE DECONNEXION DU PARENT --}}
    <div
        id="optimaconfirm-logout-modal"
        class="fixed inset-0 z-[10000] hidden items-center justify-center bg-slate-950/70 p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="optimaconfirm-logout-title"
        aria-describedby="optimaconfirm-logout-description"
    >
        <div class="w-full max-w-md overflow-hidden rounded-2xl border border-blue-600 bg-black text-white shadow-2xl">

            <div class="flex items-start gap-4 border-b border-blue-900 p-6">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-600/20 text-red-500"
                    aria-hidden="true"
                >
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                    </svg>
                </div>

                <div class="min-w-0 flex-1">
                    <h2
                        id="optimaconfirm-logout-title"
                        class="text-lg font-extrabold text-blue-400"
                    >
                        Confirmer la déconnexion
                    </h2>

                    <p
                        id="optimaconfirm-logout-description"
                        class="mt-2 text-sm leading-6 text-slate-300"
                    >
                        Voulez-vous vraiment vous déconnecter de votre compte parent ?
                    </p>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-blue-900 bg-black p-5 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    id="optimaconfirm-logout-cancel"
                    class="inline-flex items-center justify-center rounded-xl border border-green-500 bg-green-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400"
                >
                    Annuler
                </button>

                <button
                    type="button"
                    id="optimaconfirm-logout-confirm"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-red-500 bg-red-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-black"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                    </svg>
                    Oui, me déconnecter
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('optimaconfirm-logout-modal');
            const cancelButton = document.getElementById('optimaconfirm-logout-cancel');
            const confirmButton = document.getElementById('optimaconfirm-logout-confirm');

            if (!modal || !cancelButton || !confirmButton) return;

            let pendingForm = null;
            let previousFocus = null;

            function openLogoutModal(form) {
                pendingForm = form;
                previousFocus = document.activeElement;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');

                cancelButton.focus();
            }

            function closeLogoutModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');

                pendingForm = null;

                if (previousFocus && typeof previousFocus.focus === 'function') {
                    previousFocus.focus();
                }
            }

            document.querySelectorAll('form[data-optimaconfirm-logout]').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.dataset.logoutConfirmed === 'true') {
                        return;
                    }

                    event.preventDefault();
                    openLogoutModal(form);
                });
            });

            cancelButton.addEventListener('click', closeLogoutModal);

            confirmButton.addEventListener('click', function () {
                if (!pendingForm) return;

                const form = pendingForm;
                form.dataset.logoutConfirmed = 'true';

                confirmButton.disabled = true;
                confirmButton.textContent = 'Déconnexion en cours...';

                HTMLFormElement.prototype.submit.call(form);
            });

            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeLogoutModal();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeLogoutModal();
                }
            });
        });
    </script>

</body>
</html>
