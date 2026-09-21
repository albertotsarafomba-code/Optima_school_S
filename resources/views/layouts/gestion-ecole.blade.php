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
</head>

<body class="min-h-screen bg-slate-100 text-slate-800">

    <div class="flex min-h-screen">

        {{-- =========================================================
             SIDEBAR
        ========================================================== --}}
        <aside
            class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col
                   bg-blue-950 text-white shadow-2xl"
        >

            {{-- =====================================================
                 LOGO
            ====================================================== --}}
            <div class="border-b border-blue-800 px-6 py-5">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center
                               rounded-2xl bg-blue-600 text-lg font-black
                               text-white shadow-lg"
                    >
                        OS
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

                <div class="px-4 pt-4">

                    <div
                        class="rounded-2xl border border-blue-800
                               bg-blue-900 p-4 shadow-inner"
                    >

                        <div class="mb-2 flex items-center gap-2">

                            <div
                                class="flex h-9 w-9 items-center justify-center
                                       rounded-xl bg-green-600 text-white"
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
                class="border-t border-blue-800 bg-blue-950 p-4"
            >

                @auth

                    <div class="mb-3 flex items-center gap-3">

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center
                                   rounded-full bg-blue-600 text-sm font-black
                                   text-white"
                        >
                            {{ auth()->user()->initials() }}
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


                    {{-- DECONNEXION --}}
                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="flex w-full items-center gap-3 rounded-xl
                                   border border-red-500/50 bg-red-600/10
                                   px-4 py-3 text-sm font-semibold text-red-200
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


        {{-- =========================================================
             CONTENU PRINCIPAL
        ========================================================== --}}
        <main
            class="ml-72 flex min-h-screen min-w-0 flex-1 flex-col"
        >

            {{-- =====================================================
                 HEADER
            ====================================================== --}}
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
                            class="flex h-11 w-11 items-center justify-center
                                   rounded-full bg-blue-600 text-sm font-black
                                   text-white shadow-md"
                        >
                            {{ auth()->user()->initials() }}
                        </div>

                    </div>

                @endauth

            </header>


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
            <section class="flex-1 px-8 pb-8">

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

</body>

</html>
