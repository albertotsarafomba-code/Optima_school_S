<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'OPTIMA SCHOOL')
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    {{-- Optimisation de l'espace des menus sans modifier les fonctionnalités --}}
    <style>
        /* Réduire les espaces entre les menus */
        aside nav a {
            padding-top: 0.55rem !important;
            padding-bottom: 0.55rem !important;
            margin-bottom: 0.25rem !important;
        }

        /* Garder des icônes lisibles */
        aside nav a svg {
            flex-shrink: 0;
        }

        /* Réduire légèrement l'espace intérieur de la navigation */
        aside nav {
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
        }

        /* Réduire les grands espaces entre les sections */
        aside nav .mb-6 {
            margin-bottom: 0.75rem !important;
        }

        aside nav .my-4 {
            margin-top: 0.5rem !important;
            margin-bottom: 0.5rem !important;
        }

        /* Réduire légèrement le bouton Déconnexion sans le déplacer */
        aside form button[type="submit"] {
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
            font-size: 0.8125rem !important;
            gap: 0.5rem;
        }

        aside form button[type="submit"] svg {
            width: 1.125rem;
            height: 1.125rem;
        }

        /* Conserver la navigation défilante si tous les menus ne tiennent pas */
        aside nav {
            min-height: 0;
            overflow-y: auto;
            scrollbar-width: thin;
        }
    </style>


    {{-- Ajustement léger : établissement et déconnexion --}}
    <style>
        /* Bloc établissement : réduire légèrement les dimensions */
        .px-4.pt-4 {
            padding-top: 0.65rem !important;
        }

        /* Réduire les espaces intérieurs sans modifier les menus */
        .px-4.pt-4 > * {
            margin-top: 0.15rem;
            margin-bottom: 0.15rem;
        }

        /* Bouton Déconnexion : dimensions plus compactes */
        form[action*="logout"] button,
        form[action*="logout"] input[type="submit"],
        a[href*="logout"] {
            padding: 0.45rem 0.75rem !important;
            font-size: 0.875rem !important;
            line-height: 1.25rem !important;
            min-height: 0 !important;
            border-radius: 0.375rem;
        }
    </style>

</head>

<body class="min-h-screen bg-slate-100 text-slate-800">

    <div class="flex min-h-screen">

        {{-- Commandes de navigation mobile --}}
        <div class="fixed inset-x-0 top-0 z-[60] flex h-14 items-center
                    justify-between border-b border-blue-100 bg-white px-4
                    shadow-sm lg:hidden">
            <a href="{{ url('/dashboard') }}"
               class="truncate text-sm font-black text-blue-950">
                OPTIMA SCHOOL
            </a>

            <button
                id="optimamobile-toggle"
                type="button"
                aria-label="Ouvrir le menu"
                aria-controls="optimamobile-sidebar"
                aria-expanded="false"
                class="inline-flex h-10 w-10 items-center justify-center
                       rounded-lg border border-blue-200 bg-white
                       text-blue-950 hover:bg-blue-50
                       focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <svg id="optimamobile-open-icon"
                     class="h-6 w-6" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24"
                     aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="optimamobile-close-icon"
                     class="hidden h-6 w-6" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24"
                     aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6 6l12 12M18 6L6 18"/>
                </svg>
            </button>
        </div>

        <div id="optimamobile-overlay"
             class="fixed inset-0 z-40 hidden bg-slate-950/50 lg:hidden"
             aria-hidden="true"></div>


        {{-- =========================================================
             SIDEBAR
        ========================================================== --}}
        @if(!auth()->check() || auth()->user()->role !== 'parent')

        <aside
            data-optimamobile-sidebar
            id="optimamobile-sidebar"
            class="fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw]
                   flex-col bg-blue-950 text-white shadow-2xl
                   transition-transform duration-200 ease-in-out
                   -translate-x-full lg:translate-x-0"
        >

           {{-- =====================================================
     LOGO
====================================================== --}}
<div class="border-b border-blue-800 px-6 py-5">

    <div class="flex items-center gap-3">

        <div
            class="flex h-15 w-15 shrink-0 items-center justify-center
                   overflow-hidden rounded-2xl bg-white shadow-lg"
        >
            <img
                src="{{ asset('images/optimaschool-logo.png') }}"
                alt="OPTIMASCHOOL"
                class="h-full w-full object-contain p-1"
            >
        </div>

        <div class="min-w-0">

            <div
                class="truncate text-lg font-black tracking-wide"
            >
                OPTIMA SCHOOL
            </div>

            <div class="text-xs font-medium text-blue-300">
                Gestion scolaire
            </div>

        </div>

    </div>

</div>

            {{-- =====================================================
                 ETABLISSEMENT ACTIF
            ====================================================== --}}
            @if(session('establishment_id'))

                <div class="px-4 pt-2">

                    <div
                        class="rounded-xl border border-blue-800
                               bg-blue-900 px-3 py-2 shadow-inner"
                    >

                        <div class="mb-1 flex items-center gap-2">

                            <div
                                class="flex h-8 w-8 items-center justify-center
                                       rounded-lg bg-green-600 text-white"
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
                                        d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-8h6v8M8 9h.01M12 9h.01M16 9h.01"
                                    />
                                </svg>

                            </div>

                            <span
                                class="text-[11px] font-bold uppercase
                                       tracking-wider text-blue-300"
                            >
                                Établissement
                            </span>

                        </div>

                        <div
                            class="truncate text-sm font-bold text-white"
                        >
                            {{ session('establishment_name', 'Établissement') }}
                        </div>

                        @if(session('school_year_name'))

                            <div
                                class="mt-1 truncate text-xs text-blue-300"
                            >
                                Année :
                                {{ session('school_year_name') }}
                            </div>

                        @endif

                    </div>

                </div>

            @endif


            {{-- =====================================================
                 NAVIGATION
            ====================================================== --}}
            <nav class="flex-1 overflow-y-auto px-4 py-5">

                <div
                    class="mb-3 px-3 text-[11px] font-bold uppercase
                           tracking-widest text-blue-300"
                >
                    Navigation
                </div>


                {{-- =================================================
                     TABLEAU DE BORD
                ================================================== --}}
                <a
                    href="{{ route('dashboard') }}"
                    class="group mb-2 flex items-center gap-3 rounded-xl
                           border px-4 py-3 text-sm font-semibold
                           transition-all duration-200
                           {{ request()->routeIs('dashboard')
                                ? 'border-blue-400 bg-blue-600 text-white shadow-lg'
                                : 'border-blue-800 bg-blue-900 text-white hover:border-blue-500 hover:bg-blue-800' }}"
                >

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
                            d="M3 13h8V3H3v10ZM13 21h8V11h-8v10ZM13 3h8v4h-8V3ZM3 17h8v4H3v-4Z"
                        />
                    </svg>

                    <span>Tableau de bord</span>

                </a>


                {{-- =================================================
                     ETUDIANTS
                ================================================== --}}
                <a
                    href="{{ route('students.index') }}"
                    class="group mb-2 flex items-center gap-3 rounded-xl
                           border px-4 py-3 text-sm font-semibold
                           transition-all duration-200
                           {{ request()->routeIs('students.*')
                                ? 'border-blue-400 bg-blue-600 text-white shadow-lg'
                                : 'border-blue-800 bg-blue-900 text-white hover:border-blue-500 hover:bg-blue-800' }}"
                >

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
                            d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"
                        />
                    </svg>

                    <span>Étudiants</span>

                </a>


                {{-- =================================================
                     ENSEIGNANTS
                ================================================== --}}
                <a
                    href="{{ route('teachers.index') }}"
                    class="group mb-2 flex items-center gap-3 rounded-xl
                           border px-4 py-3 text-sm font-semibold
                           transition-all duration-200
                           {{ request()->routeIs('teachers.*')
                                ? 'border-blue-400 bg-blue-600 text-white shadow-lg'
                                : 'border-blue-800 bg-blue-900 text-white hover:border-blue-500 hover:bg-blue-800' }}"
                >

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
                            d="M12 14a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM4 21a8 8 0 0 1 16 0M19 8h3M20.5 6.5v3"
                        />
                    </svg>

                    <span>Enseignants</span>

                </a>


                {{-- =================================================
                     CLASSES
                ================================================== --}}
                <a
                    href="{{ route('class_rooms.index') }}"
                    class="group mb-2 flex items-center gap-3 rounded-xl
                           border px-4 py-3 text-sm font-semibold
                           transition-all duration-200
                           {{ request()->routeIs('class_rooms.*')
                                ? 'border-blue-400 bg-blue-600 text-white shadow-lg'
                                : 'border-blue-800 bg-blue-900 text-white hover:border-blue-500 hover:bg-blue-800' }}"
                >

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
                            d="M3 21h18M5 21V5h14v16M8 8h2M14 8h2M8 12h2M14 12h2M8 16h2M14 16h2"
                        />
                    </svg>

                    <span>Classes</span>

                </a>


                {{-- =================================================
                     SALLES
                ================================================== --}}
                <a
                    href="{{ route('salles.index') }}"
                    class="group mb-2 flex items-center gap-3 rounded-xl
                           border px-4 py-3 text-sm font-semibold
                           transition-all duration-200
                           {{ request()->routeIs('salles.*')
                                ? 'border-blue-400 bg-blue-600 text-white shadow-lg'
                                : 'border-blue-800 bg-blue-900 text-white hover:border-blue-500 hover:bg-blue-800' }}"
                >

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
                            d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-8h6v8M8 9h.01M12 9h.01M16 9h.01"
                        />
                    </svg>

                    <span>Salles</span>

                </a>


                {{-- =================================================
                     MATIERES
                ================================================== --}}
                <a
                    href="{{ route('subjects.index') }}"
                    class="group mb-2 flex items-center gap-3 rounded-xl
                           border px-4 py-3 text-sm font-semibold
                           transition-all duration-200
                           {{ request()->routeIs('subjects.*')
                                ? 'border-blue-400 bg-blue-600 text-white shadow-lg'
                                : 'border-blue-800 bg-blue-900 text-white hover:border-blue-500 hover:bg-blue-800' }}"
                >

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
                            d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"
                        />
                    </svg>

                    <span>Matières</span>

                </a>


                {{-- =================================================
                     INSCRIPTIONS
                ================================================== --}}
                <a
                    href="{{ route('inscriptions.index') }}"
                    class="group mb-2 flex items-center gap-3 rounded-xl
                           border px-4 py-3 text-sm font-semibold
                           transition-all duration-200
                           {{ request()->routeIs('inscriptions.*')
                                ? 'border-blue-400 bg-blue-600 text-white shadow-lg'
                                : 'border-blue-800 bg-blue-900 text-white hover:border-blue-500 hover:bg-blue-800' }}"
                >

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
                            d="M9 12h6M9 16h6M7 3h10a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2ZM9 7h6"
                        />
                    </svg>

                    <span>Inscriptions</span>

                </a>


                {{-- =================================================
                     PARENTS
                ================================================== --}}
                @if(auth()->check() && in_array(auth()->user()->role, ["admin", "secretariat"], true))

                    <a
                        href="{{ route("parents.index") }}"
                        class="group mb-2 flex items-center gap-3 rounded-xl
                               border px-4 py-3 text-sm font-semibold
                               transition-all duration-200
                               {{ request()->routeIs("parents.*")
                                    ? "border-green-400 bg-green-600 text-white shadow-lg"
                                    : "border-blue-800 bg-blue-900 text-white hover:border-green-500 hover:bg-blue-800" }}"
                    >

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
                                d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"
                            />
                        </svg>

                        <span>Parents</span>

                    </a>

                @endif


                {{-- =================================================
                     NOTES
                ================================================== --}}
                <a
                    href="{{ route('notes.index') }}"
                    class="group mb-2 flex items-center gap-3 rounded-xl
                           border px-4 py-3 text-sm font-semibold
                           transition-all duration-200
                           {{ request()->routeIs('notes.*')
                                ? 'border-blue-400 bg-blue-600 text-white shadow-lg'
                                : 'border-blue-800 bg-blue-900 text-white hover:border-blue-500 hover:bg-blue-800' }}"
                >

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
                            d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"
                        />
                    </svg>

                    <span>Notes</span>

                </a>


                {{-- =================================================
                     RÉSULTATS
                ================================================== --}}
                <a
                    href="{{ route('resultats.index') }}"
                    class="group mb-2 flex items-center gap-3 rounded-xl
                           border px-4 py-3 text-sm font-semibold
                           transition-all duration-200
                           {{ request()->routeIs('resultats.*')
                                ? 'border-green-400 bg-green-600 text-white shadow-lg'
                                : 'border-blue-800 bg-blue-900 text-white hover:border-green-500 hover:bg-blue-800' }}"
                >
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
                            d="M9 17v-2m3 2v-4m3 4v-6m2 9H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2z"
                        />
                    </svg>

                    <span>Résultats</span>
                </a>

                {{-- =================================================
                     EMPLOI DU TEMPS
                ================================================== --}}
                <a
                    href="{{ route('emploi-du-temps.index') }}"
                    class="group mb-2 flex items-center gap-3 rounded-xl
                           border px-4 py-3 text-sm font-semibold
                           transition-all duration-200
                           {{ request()->routeIs('emploi-du-temps.*')
                                ? 'border-green-400 bg-green-600 text-white shadow-lg'
                                : 'border-blue-800 bg-blue-900 text-white hover:border-green-500 hover:bg-blue-800' }}"
                >
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
                            d="M6.75 3.75h10.5A2.25 2.25 0 0 1 19.5 6v12a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 18V6a2.25 2.25 0 0 1 2.25-2.25ZM8.25 7.5h7.5M8.25 11.25h7.5M8.25 15h4.5"
                        />
                    </svg>

                    <span>Emploi du temps</span>
                </a>

                {{-- =================================================
                     BULLETINS
                ================================================== --}}
                <a
                    href="{{ route('bulletins.index') }}"
                    class="group mb-2 flex items-center gap-3 rounded-xl
                           border px-4 py-3 text-sm font-semibold
                           transition-all duration-200
                           {{ request()->routeIs('bulletins.*')
                                ? 'border-green-400 bg-green-600 text-white shadow-lg'
                                : 'border-blue-800 bg-blue-900 text-white hover:border-green-500 hover:bg-blue-800' }}"
                >

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
                            d="M6 3h9l4 4v14H6a2 2 0 01-2-2V5a2 2 0 012-2zM14 3v5h5M8 12h8M8 16h8"
                        />
                    </svg>

                    <span>Bulletins</span>

                </a>


                {{-- =================================================
                     PAIEMENTS
                ================================================== --}}
                <a
                    href="{{ route('paiements.index') }}"
                    class="group mb-2 flex items-center gap-3 rounded-xl
                           border px-4 py-3 text-sm font-semibold
                           transition-all duration-200
                           {{ request()->routeIs('paiements.*')
                                ? 'border-blue-400 bg-blue-600 text-white shadow-lg'
                                : 'border-blue-800 bg-blue-900 text-white hover:border-blue-500 hover:bg-blue-800' }}"
                >

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
                            d="M3 7h18v10H3V7ZM7 11h4M7 15h2"
                        />
                    </svg>

                    <span>Paiements</span>

                </a>

                {{-- =================================================
                     SUIVI MENSUEL DES ÉCOLAGES
                ================================================== --}}
                <a
                    href="{{ route('ecolages-mensuels.index') }}"
                    class="group mb-2 flex items-center gap-3 rounded-xl
                           border px-4 py-3 text-sm font-semibold
                           transition-all duration-200
                           {{ request()->routeIs('ecolages-mensuels.*')
                                ? 'border-green-400 bg-green-600 text-white shadow-lg'
                                : 'border-blue-800 bg-blue-900 text-white hover:border-green-500 hover:bg-blue-800' }}"
                >
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
                            d="M4 19V5m0 14h16M8 15v-4m4 4V7m4 8V9"
                        />
                    </svg>

                    <span>Suivi des écolages</span>
                </a>


                {{-- =================================================
                     ANNEES SCOLAIRES
                     Route correcte : school_years.index
                ================================================== --}}
                <a
                    href="{{ route('school_years.index') }}"
                    class="group mb-2 flex items-center gap-3 rounded-xl
                           border px-4 py-3 text-sm font-semibold
                           transition-all duration-200
                           {{ request()->routeIs('school_years.*')
                                ? 'border-blue-400 bg-blue-600 text-white shadow-lg'
                                : 'border-blue-800 bg-blue-900 text-white hover:border-blue-500 hover:bg-blue-800' }}"
                >

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
                            d="M7 3v4M17 3v4M4 9h16M5 5h14a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z"
                        />
                    </svg>

                    <span>Années scolaires</span>

                </a>


                {{-- =================================================
                     ADMINISTRATION
                ================================================== --}}
                @if(auth()->check() && auth()->user()->role === 'admin')

                    <div class="my-4 border-t border-blue-800"></div>

                    <div
                        class="mb-3 px-3 text-[11px] font-bold uppercase
                               tracking-widest text-green-400"
                    >
                        Administration
                    </div>


                    {{-- ABONNEMENT LOGICIEL --}}
                    <a
                        href="{{ route('admin.abonnement.index') }}"
                        class="group mb-2 flex items-center gap-3 rounded-xl
                               border px-4 py-3 text-sm font-semibold
                               transition-all duration-200
                               {{ request()->routeIs('admin.abonnement.*')
                                    ? 'border-green-400 bg-green-600 text-white shadow-lg'
                                    : 'border-blue-800 bg-blue-900 text-white hover:border-green-500 hover:bg-blue-800' }}"
                    >
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
                                d="M3 7h18v12H3zM3 11h18M7 15h3M7 3v4M17 3v4"
                            />
                        </svg>

                        <span>Mon abonnement</span>
                    </a>

                    {{-- UTILISATEURS --}}
                    <a
                        href="{{ route('users.index') }}"
                        class="group mb-2 flex items-center gap-3 rounded-xl
                               border px-4 py-3 text-sm font-semibold
                               transition-all duration-200
                               {{ request()->routeIs('users.*')
                                    ? 'border-green-400 bg-green-600 text-white shadow-lg'
                                    : 'border-blue-800 bg-blue-900 text-white hover:border-green-500 hover:bg-blue-800' }}"
                    >

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
                                d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0-0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"
                            />
                        </svg>

                        <span>Utilisateurs</span>

                    </a>

                @endif

            </nav>


            {{-- =====================================================
                 UTILISATEUR + DECONNEXION
            ====================================================== --}}
            <div
                class="border-t border-blue-800 bg-blue-950 px-3 py-2"
            >

                @auth

                    <div class="mb-2 flex items-center gap-2">

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center
                                   overflow-hidden rounded-full border-2 border-blue-400
                                   bg-blue-600 text-sm font-black text-white"
                        >
                            @if (auth()->user()->profilePhotoUrl())
                                <img
                                    src="{{ auth()->user()->profilePhotoUrl() }}"
                                    alt="Photo de profil"
                                    class="h-full w-full object-cover"
                                    loading="eager"
                                >
                            @else
                                {{ auth()->user()->initials() }}
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">

                            <div
                                class="truncate text-sm font-bold text-white"
                            >
                                {{ auth()->user()->name }}
                            </div>

                            <div
                                class="truncate text-xs text-blue-300"
                            >
                                {{ auth()->user()->email }}
                            </div>

                        </div>

                    </div>


                    {{-- PARAMÈTRES DU PROFIL --}}
                    <a
                        href="{{ route('profile.edit') }}"
                        class="mb-2 flex w-full items-center gap-2 rounded-lg
                               border border-blue-500/50 bg-blue-600/10
                               px-3 py-2 text-sm font-semibold text-blue-100
                               transition hover:bg-blue-600 hover:text-white"
                    >
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
                                d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m19.4 15 .1.1a1.8 1.8 0 0 1-2.5 2.5l-.1-.1a1.8 1.8 0 0 0-3 .8v.2a1.8 1.8 0 0 1-3.6 0v-.2a1.8 1.8 0 0 0-3-.8l-.1.1a1.8 1.8 0 0 1-2.5-2.5l.1-.1a1.8 1.8 0 0 0-.8-3h-.2a1.8 1.8 0 0 1 0-3.6h.2a1.8 1.8 0 0 0 .8-3l-.1-.1a1.8 1.8 0 0 1 2.5-2.5l.1.1a1.8 1.8 0 0 0 3-.8v-.2a1.8 1.8 0 0 1 3.6 0v.2a1.8 1.8 0 0 0 3 .8l.1-.1a1.8 1.8 0 0 1 2.5 2.5l-.1.1a1.8 1.8 0 0 0 .8 3h.2a1.8 1.8 0 0 1 0 3.6h-.2a1.8 1.8 0 0 0-.8 3Z"
                            />
                        </svg>
                        <span>Mon profil / Paramètres</span>
                    </a>

                    {{-- DECONNEXION --}}
                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        data-optimaconfirm-logout
                    >

                        @csrf

                        <button
                            type="submit"
                            class="flex w-full items-center gap-2 rounded-lg
                                   border border-red-500/50 bg-red-600/10
                                   px-3 py-2 text-sm font-semibold text-red-200
                                   transition hover:border-red-400
                                   hover:bg-red-600 hover:text-white"
                        >

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
                                    d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"
                                />
                            </svg>

                            <span>Déconnexion</span>

                        </button>

                    </form>

                @endauth

            </div>

        </aside>

        @else

        {{-- =========================================================
             SIDEBAR ESPACE FAMILLE — PARENT
             Consultation uniquement
        ========================================================== --}}
        <aside
            data-optimamobile-sidebar
            class="fixed inset-y-0 left-0 z-50 flex w-72 max-w-[85vw]
                   flex-col bg-white text-slate-800 shadow-2xl
                   transition-transform duration-200 ease-in-out
                   -translate-x-full lg:translate-x-0"
        >

            {{-- =====================================================
                 LOGO / IDENTITÉ
            ====================================================== --}}
            <div class="border-b border-slate-200 px-6 py-6">

                <a
                    href="{{ route('parent.dashboard') }}"
                    class="flex items-center gap-3"
                >

                    <div
                        class="flex h-12 w-12 shrink-0 items-center
                               justify-center overflow-hidden rounded-xl
                               bg-blue-950 shadow-md"
                    >
                        <img
                            src="{{ asset('images/optimaschool-logo.png') }}"
                            alt="OPTIMASCHOOL"
                            class="h-10 w-10 object-contain"
                        >
                    </div>

                    <div class="min-w-0">

                        <div
                            class="text-base font-black tracking-wide
                                   text-blue-950"
                        >
                            OPTIMA SCHOOL
                        </div>

                        <div
                            class="mt-0.5 text-xs font-bold uppercase
                                   tracking-wider text-green-600"
                        >
                            Espace famille
                        </div>

                    </div>

                </a>

            </div>


            {{-- =====================================================
                 UTILISATEUR CONNECTÉ
            ====================================================== --}}
            @auth
                <div class="mx-4 mt-5 rounded-2xl border border-blue-100
                            bg-blue-50 px-4 py-4">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex h-11 w-11 shrink-0 items-center
                                   justify-center overflow-hidden rounded-full
                                   border-2 border-white bg-blue-950
                                   text-sm font-black text-white shadow-sm"
                        >
                            @if (auth()->user()->profilePhotoUrl())
                                <img
                                    src="{{ auth()->user()->profilePhotoUrl() }}"
                                    alt="Photo de profil de {{ auth()->user()->name }}"
                                    class="h-full w-full object-cover"
                                    loading="eager"
                                >
                            @else
                                {{ auth()->user()->initials() }}
                            @endif
                        </div>

                        <div class="min-w-0">

                            <div
                                class="truncate text-sm font-black
                                       text-slate-800"
                            >
                                {{ auth()->user()->name }}
                            </div>

                            <div
                                class="mt-0.5 text-xs font-semibold
                                       text-blue-700"
                            >
                                Parent
                            </div>

                        </div>

                    </div>

                </div>
            @endauth


            {{-- =====================================================
                 NAVIGATION
            ====================================================== --}}
            <nav class="flex-1 overflow-y-auto px-4 py-6">

                {{-- TABLEAU DE BORD --}}
                <div class="mb-6">

                    <div
                        class="mb-2 px-3 text-[11px] font-black uppercase
                               tracking-widest text-slate-400"
                    >
                        ESPACE FAMILLE
                    </div>

                    <a
                        href="{{ route('parent.dashboard') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-3
                               text-sm font-bold text-blue-950
                               transition hover:bg-blue-50"
                    >

                        <svg
                            class="h-5 w-5 shrink-0 text-blue-700"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3 12l9-9 9 9"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 10v10a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V10"
                            />
                        </svg>

                        <span>Tableau de bord</span>

                    </a>

                </div>


                {{-- MES ENFANTS --}}
                <div class="mb-6">

                    <div
                        class="mb-2 px-3 text-[11px] font-black uppercase
                               tracking-widest text-slate-400"
                    >
                        MES ENFANTS
                    </div>

                    <a
                        href="{{ route('parent.inscriptions') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-3
                               text-sm font-bold text-slate-700
                               transition hover:bg-blue-50
                               hover:text-blue-900"
                    >

                        <svg
                            class="h-5 w-5 shrink-0 text-blue-700"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
                            />
                            <circle cx="9" cy="7" r="4" />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"
                            />
                        </svg>

                        <span>Mes enfants</span>

                    </a>

                </div>


                {{-- SCOLARITÉ --}}
                <div class="mb-6">

                    <div
                        class="mb-2 px-3 text-[11px] font-black uppercase
                               tracking-widest text-slate-400"
                    >
                        SCOLARITÉ
                    </div>


                    <a
                        href="{{ route('parent.dashboard') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5
                               text-sm font-semibold text-slate-700
                               transition hover:bg-blue-50
                               hover:text-blue-900"
                    >
                        <svg
                            class="h-5 w-5 shrink-0 text-blue-700"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"
                            />
                        </svg>
                        <span>Classe de mes enfants</span>
                    </a>


                    <a
                        href="{{ route('parent.dashboard') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5
                               text-sm font-semibold text-slate-700
                               transition hover:bg-blue-50
                               hover:text-blue-900"
                    >
                        <svg
                            class="h-5 w-5 shrink-0 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
                            />
                            <circle cx="9" cy="7" r="4" />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16 3.13a4 4 0 0 1 4 3.87"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M20 21v-2a4 4 0 0 0-3-3.87"
                            />
                        </svg>
                        <span>Enseignants</span>
                    </a>


                    <a
                        href="{{ route('parent.dashboard') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5
                               text-sm font-semibold text-slate-700
                               transition hover:bg-blue-50
                               hover:text-blue-900"
                    >
                        <svg
                            class="h-5 w-5 shrink-0 text-blue-700"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6.5 2H20v15H6.5A2.5 2.5 0 0 0 4 19.5V6.5A4.5 4.5 0 0 1 8.5 2H20"
                            />
                        </svg>
                        <span>Matières</span>
                    </a>


                    <a
                        href="{{ route('notes.index') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5
                               text-sm font-semibold text-slate-700
                               transition hover:bg-blue-50
                               hover:text-blue-900"
                    >
                        <svg
                            class="h-5 w-5 shrink-0 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6.5 2H20v15H6.5A2.5 2.5 0 0 0 4 21"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 7h8M8 11h6"
                            />
                        </svg>
                        <span>Notes & résultats</span>
                    </a>


                    <a
                        href="{{ route('bulletins.index') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5
                               text-sm font-semibold text-slate-700
                               transition hover:bg-blue-50
                               hover:text-blue-900"
                    >
                        <svg
                            class="h-5 w-5 shrink-0 text-blue-700"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 2h9l5 5v15H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M14 2v6h6M8 13h8M8 17h6"
                            />
                        </svg>
                        <span>Bulletins</span>
                    </a>


                    <a
                        href="{{ route('emploi-du-temps.index') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-2.5
                               text-sm font-semibold text-slate-700
                               transition hover:bg-blue-50
                               hover:text-blue-900"
                    >
                        <svg
                            class="h-5 w-5 shrink-0 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <rect
                                x="3"
                                y="4"
                                width="18"
                                height="17"
                                rx="2"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 2v4M16 2v4M3 10h18"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M8 14h2M14 14h2M8 18h2"
                            />
                        </svg>
                        <span>Emploi du temps</span>
                    </a>

                </div>


                {{-- ÉCOLAGE --}}
                <div class="mb-6">

                    <div
                        class="mb-2 px-3 text-[11px] font-black uppercase
                               tracking-widest text-slate-400"
                    >
                        ÉCOLAGE
                    </div>

                    <a
                        href="{{ route('parent.paiements') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-3
                               text-sm font-bold text-slate-700
                               transition hover:bg-green-50
                               hover:text-green-800"
                    >

                        <svg
                            class="h-5 w-5 shrink-0 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <rect
                                x="2"
                                y="5"
                                width="20"
                                height="14"
                                rx="2"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2 10h20"
                            />
                        </svg>

                        <span>Paiements</span>

                    </a>

                </div>


                {{-- INFORMATIONS --}}
                <div class="mb-6">

                    <div
                        class="mb-2 px-3 text-[11px] font-black uppercase
                               tracking-widest text-slate-400"
                    >
                        INFORMATIONS
                    </div>

                    <a
                        href="{{ route('parent.dashboard') }}"
                        class="flex items-center gap-3 rounded-xl px-3 py-3
                               text-sm font-semibold text-slate-700
                               transition hover:bg-blue-50
                               hover:text-blue-900"
                    >

                        <svg
                            class="h-5 w-5 shrink-0 text-blue-700"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <circle cx="12" cy="12" r="9" />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 11v5M12 8h.01"
                            />
                        </svg>

                        <span>Informations scolaires</span>

                    </a>

                </div>

            </nav>


            {{-- =====================================================
                 PROFIL / DÉCONNEXION
            ====================================================== --}}
            @auth
                <div class="border-t border-slate-200 p-4">

                    <a
                        href="{{ route('profile.edit') }}"
                        class="mb-2 flex items-center gap-3 rounded-xl px-3 py-2.5
                               text-sm font-semibold text-slate-700
                               transition hover:bg-slate-100"
                    >

                        <svg
                            class="h-5 w-5 shrink-0 text-slate-500"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <circle cx="12" cy="8" r="4" />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 21a8 8 0 0 1 16 0"
                            />
                        </svg>

                        <span>Profil</span>

                    </a>


                    <form method="POST" action="{{ route('logout') }}"
                        data-optimaconfirm-logout>
                        @csrf

                        <button
                            type="submit"
                            class="flex w-full items-center gap-3 rounded-xl
                                   bg-red-50 px-3 py-2.5 text-sm font-bold
                                   text-red-700 transition
                                   hover:bg-red-100"
                        >

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
                                    d="M15 12H3m0 0 4-4m-4 4 4 4"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 5V4a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2h-3a2 2 0 0 1-2-2v-1"
                                />
                            </svg>

                            <span>Déconnexion</span>

                        </button>

                    </form>

                </div>
            @endauth

        </aside>

        @endif


        {{-- =========================================================
             CONTENU PRINCIPAL
        ========================================================== --}}
        <main
            class="ml-72 flex min-h-screen min-w-0 flex-1 flex-col"
        >

            {{-- =====================================================
                 HEADER
            ====================================================== --}}
            @if(auth()->check() && auth()->user()->role === 'parent')

                <header
                    class="sticky top-0 z-30 flex min-h-20 items-center
                           justify-between gap-4 border-b border-slate-200
                           bg-white px-4 py-3 shadow-sm sm:px-8"
                >
                    <div class="min-w-0">
                        <div class="text-xs font-bold uppercase tracking-wider text-blue-600">
                            OPTIMA SCHOOL
                        </div>
                        <h1 class="mt-1 truncate text-lg font-black text-slate-800 sm:text-xl">
                            @yield('page-title', 'Espace famille')
                        </h1>
                    </div>

                    <div class="flex shrink-0 items-center gap-3">
                        @include('notifications.bell')

                        <div class="hidden text-right sm:block">
                            <div class="text-sm font-bold text-slate-800">
                                {{ auth()->user()->name }}
                            </div>
                            <div class="text-xs capitalize text-slate-500">Parent</div>
                        </div>
                    </div>
                </header>

            @else

                {{-- =================================================
                     HEADER AUTRES RÔLES — INCHANGÉ
                ================================================== --}}
                <header
                    class="sticky top-0 z-30 flex h-20 items-center
                           justify-between border-b border-slate-200
                           bg-white px-8 shadow-sm"
                >

                    <div>

                        <div
                            class="text-xs font-bold uppercase
                                   tracking-wider text-blue-600"
                        >
                            OPTIMA SCHOOL
                        </div>

                        <h1
                            class="mt-1 text-xl font-black text-slate-800"
                        >
                            @yield('page-title', 'Gestion scolaire')
                        </h1>

                    </div>


                    @auth

                        <div class="flex items-center gap-3">

                            @include('notifications.bell')

                            <div class="hidden text-right sm:block">

                                <div
                                    class="text-sm font-bold text-slate-800"
                                >
                                    {{ auth()->user()->name }}
                                </div>

                                <div
                                    class="text-xs capitalize text-slate-500"
                                >
                                    {{ auth()->user()->role }}
                                </div>

                            </div>

                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center
                                       overflow-hidden rounded-full border-2 border-blue-100
                                       bg-blue-600 text-sm font-black text-white shadow-md"
                            >
                                @if (auth()->user()->profilePhotoUrl())
                                    <img
                                        src="{{ auth()->user()->profilePhotoUrl() }}"
                                        alt="Photo de profil de {{ auth()->user()->name }}"
                                        class="h-full w-full object-cover"
                                        loading="eager"
                                    >
                                @else
                                    {{ auth()->user()->initials() }}
                                @endif
                            </div>

                        </div>

                    @endauth

                </header>

            @endif


            {{-- =====================================================
                 ALERTES
            ====================================================== --}}
            <div class="px-8 pt-6">

                {{-- SUCCESS --}}
                @if(session('success'))

                    <div
                        class="mb-4 flex items-start gap-3 rounded-xl
                               border border-green-200 bg-green-50
                               px-4 py-3 text-green-800 shadow-sm"
                    >

                        <svg
                            class="mt-0.5 h-5 w-5 shrink-0 text-green-600"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m5 12 4 4L19 6"
                            />
                        </svg>

                        <div class="text-sm font-medium">
                            {{ session('success') }}
                        </div>

                    </div>

                @endif


                {{-- ERROR SESSION --}}
                @if(session('error'))

                    <div
                        class="mb-4 flex items-start gap-3 rounded-xl
                               border border-red-200 bg-red-50
                               px-4 py-3 text-red-800 shadow-sm"
                    >

                        <svg
                            class="mt-0.5 h-5 w-5 shrink-0 text-red-600"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 8v4M12 16h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"
                            />
                        </svg>

                        <div class="text-sm font-medium">
                            {{ session('error') }}
                        </div>

                    </div>

                @endif


                {{-- VALIDATION ERRORS --}}
                @if($errors->any())

                    <div
                        class="mb-4 rounded-xl border border-red-200
                               bg-red-50 px-4 py-3 text-red-800 shadow-sm"
                    >

                        <div class="mb-2 flex items-center gap-2">

                            <svg
                                class="h-5 w-5 text-red-600"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 8v4M12 16h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"
                                />
                            </svg>

                            <span class="font-bold">
                                Vérifiez les informations saisies.
                            </span>

                        </div>

                        <ul class="ml-7 list-disc space-y-1 text-sm">

                            @foreach($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif

            </div>


            {{-- =====================================================
                 CONTENU DES PAGES
            ====================================================== --}}
            <section class="min-w-0 flex-1 px-3 pb-5 sm:px-5 lg:px-8 lg:pb-8">

                @yield('content')

            </section>


            {{-- =====================================================
                 FOOTER
            ====================================================== --}}
            <footer
                class="border-t border-slate-200 bg-white px-8 py-4"
            >

                <div
                    class="flex flex-col items-center justify-between gap-2
                           text-xs text-slate-500 sm:flex-row"
                >

                    <span>
                        © {{ date('Y') }} OPTIMA SCHOOL
                    </span>

                    <span>
                        Gestion scolaire
                    </span>

                </div>

            </footer>

        </main>

    </div>


    @stack('scripts')


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('optimamobile-toggle');
            const overlay = document.getElementById('optimamobile-overlay');
            const sidebar = document.querySelector('[data-optimamobile-sidebar]');
            const openIcon = document.getElementById('optimamobile-open-icon');
            const closeIcon = document.getElementById('optimamobile-close-icon');

            if (!toggle || !overlay || !sidebar) return;

            function isMobile() {
                return window.matchMedia('(max-width: 1023px)').matches;
            }

            function openMenu() {
                if (!isMobile()) return;
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                toggle.setAttribute('aria-expanded', 'true');
                toggle.setAttribute('aria-label', 'Fermer le menu');
                openIcon?.classList.add('hidden');
                closeIcon?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeMenu() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.setAttribute('aria-label', 'Ouvrir le menu');
                openIcon?.classList.remove('hidden');
                closeIcon?.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            toggle.addEventListener('click', function () {
                toggle.getAttribute('aria-expanded') === 'true'
                    ? closeMenu()
                    : openMenu();
            });

            overlay.addEventListener('click', closeMenu);

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') closeMenu();
            });

            sidebar.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (isMobile()) closeMenu();
                });
            });

            window.addEventListener('resize', function () {
                if (!isMobile()) {
                    closeMenu();
                }
            });

            closeMenu();
        });
    </script>



    {{-- MODALE PROFIL UTILISATEUR --}}
    <div
        id="optimaprofile-modal"
        class="fixed inset-0 z-[11000] hidden items-center justify-center
               bg-slate-950/60 p-4 backdrop-blur-sm"
        role="dialog"
        aria-modal="true"
        aria-hidden="true"
        aria-labelledby="optimaprofile-title"
    >
        <div
            id="optimaprofile-panel"
            class="relative w-full max-w-md overflow-hidden rounded-2xl
                   bg-white shadow-2xl"
        >
            <div class="bg-[#1e3a8a] px-6 py-6 text-center border-b-4 border-[#16a34a]">
                <button
                    type="button"
                    id="optimaprofile-close-top"
                    class="absolute right-4 top-3 rounded-full px-3 py-1
                           text-2xl text-white hover:bg-white/20"
                    aria-label="Fermer"
                >&times;</button>

                <p class="text-xs font-bold tracking-widest text-green-300">
                    OPTIMASCHOOL
                </p>
                <h2 id="optimaprofile-title"
                    class="mt-2 text-xl font-black text-white">
                    Mon profil
                </h2>
            </div>

            <div class="-mt-1 px-6 pb-6 pt-5">
                <div class="mb-4 flex justify-center">
                    <div
                        id="optimaprofile-photo"
                        class="flex h-28 w-28 items-center justify-center
                               overflow-hidden rounded-full border-4
                               border-[#16a34a] bg-[#1e3a8a] text-3xl
                               font-black text-white shadow-lg"
                    >
                        @if (auth()->user()->profilePhotoUrl())
                            <img
                                src="{{ auth()->user()->profilePhotoUrl() }}"
                                alt="Photo de profil de {{ auth()->user()->name }}"
                                class="h-full w-full object-cover"
                                loading="eager"
                            >
                        @else
                            {{ auth()->user()->initials() }}
                        @endif
                    </div>
                </div>

                <div class="text-center">
                    <div class="text-xl font-black text-slate-800">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="mt-1 text-sm capitalize text-[#16a34a]">
                        {{ auth()->user()->role }}
                    </div>
                </div>

                <div class="mt-5 rounded-xl border border-slate-200
                            bg-slate-50 p-4">
                    <div class="text-xs font-bold uppercase text-slate-500">
                        Adresse e-mail
                    </div>
                    <div class="mt-1 break-words text-sm font-semibold
                                text-slate-800">
                        {{ auth()->user()->email }}
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <a
                        href="{{ route('profile.edit') }}"
                        class="flex min-h-11 items-center justify-center
                               rounded-xl bg-[#16a34a] px-4 py-3 text-sm
                               font-bold text-white hover:bg-green-700"
                    >
                        Modifier mon profil
                    </a>

                    <button
                        type="button"
                        id="optimaprofile-close-bottom"
                        class="min-h-11 rounded-xl border border-[#16a34a]
                               px-4 py-3 text-sm font-bold text-slate-700
                               hover:bg-slate-100"
                    >
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('optimaprofile-modal');
            const panel = document.getElementById('optimaprofile-panel');

            if (!modal || !panel) return;

            const closeTop = document.getElementById(
                'optimaprofile-close-top'
            );
            const closeBottom = document.getElementById(
                'optimaprofile-close-bottom'
            );

            let previousFocus = null;

            function openModal() {
                previousFocus = document.activeElement;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                if (closeTop) closeTop.focus();
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';

                if (previousFocus && previousFocus.focus) {
                    previousFocus.focus();
                }
            }

            // Repérer les photos de profil existantes sans modifier
            // les autres éléments ni dépendre des espaces du HTML.
            document.querySelectorAll(
                'img[alt^="Photo de profil"]'
            ).forEach(function (photo) {
                const trigger = photo.parentElement &&
                    photo.parentElement.parentElement;

                if (!trigger) return;

                trigger.style.cursor = 'pointer';
                trigger.setAttribute('role', 'button');
                trigger.setAttribute('tabindex', '0');
                trigger.setAttribute('aria-label', 'Afficher mon profil');

                trigger.addEventListener('click', openModal);
                trigger.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        openModal();
                    }
                });
            });

            if (closeTop) closeTop.addEventListener('click', closeModal);
            if (closeBottom) {
                closeBottom.addEventListener('click', closeModal);
            }

            modal.addEventListener('click', function (event) {
                if (!panel.contains(event.target)) closeModal();
            });

            document.addEventListener('keydown', function (event) {
                if (
                    event.key === 'Escape' &&
                    !modal.classList.contains('hidden')
                ) {
                    closeModal();
                }
            });
        });
    </script>

    {{-- =========================================================
         MODALE DE CONFIRMATION DE DECONNEXION
         Admin, Secretaire, Comptable et Parent
    ========================================================== --}}
    <div
        id="optimaconfirm-logout-modal"
        class="fixed inset-0 z-[10000] hidden items-center justify-center
               bg-slate-950/60 p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="optimaconfirm-logout-title"
        aria-describedby="optimaconfirm-logout-description"
    >
        <div
            class="w-full max-w-md overflow-hidden rounded-2xl border border-blue-600
                   bg-black text-white shadow-2xl"
        >
            <div class="flex items-start gap-4 border-b border-blue-900 p-6">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                           rounded-full bg-red-600/20 text-red-500"
                    aria-hidden="true"
                >
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
                            d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"
                        />
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
                        Voulez-vous vraiment vous déconnecter de votre compte ?
                    </p>
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-blue-900 bg-black p-5 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    id="optimaconfirm-logout-cancel"
                    class="inline-flex items-center justify-center rounded-xl
                           border border-green-500 bg-green-600 px-5 py-3
                           text-sm font-bold text-white
                           transition hover:bg-green-700
                           focus:outline-none focus:ring-2
                           focus:ring-green-400"
                >
                    Annuler
                </button>

                <button
                    type="button"
                    id="optimaconfirm-logout-confirm"
                    class="inline-flex items-center justify-center gap-2
                           rounded-xl border border-red-500 bg-red-600 px-5 py-3 text-sm
                           font-bold text-white transition
                           hover:bg-red-700 focus:outline-none
                           focus:ring-2 focus:ring-red-500
                           focus:ring-offset-2 focus:ring-offset-black"
                >
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3"
                        />
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

                // Soumission native : conserve la méthode POST et le jeton CSRF.
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
