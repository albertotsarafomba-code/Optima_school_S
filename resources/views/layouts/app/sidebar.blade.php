<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">

    {{-- ============================================================
         SIDEBAR
    ============================================================ --}}

    <flux:sidebar
        sticky
        collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900"
    >

        {{-- Logo --}}
        <flux:sidebar.header>

            <x-app-logo
                :sidebar="true"
                href="{{ route('dashboard') }}"
                wire:navigate
            />

            <flux:sidebar.collapse class="lg:hidden" />

        </flux:sidebar.header>


        {{-- ========================================================
             MENU PRINCIPAL
        ========================================================= --}}

        <flux:sidebar.nav>

            {{-- Dashboard --}}
            <flux:sidebar.group
                :heading="__('Gestion scolaire')"
                class="grid"
            >

                <flux:sidebar.item
                    icon="home"
                    :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')"
                    wire:navigate
                >
                    {{ __('Dashboard') }}
                </flux:sidebar.item>


                {{-- =================================================
                     ÉTUDIANTS
                ================================================== --}}

                <flux:sidebar.item
                    icon="academic-cap"
                    :href="route('students.index')"
                    :current="request()->routeIs('students.*')"
                    wire:navigate
                >
                    {{ __('Étudiants') }}
                </flux:sidebar.item>


                {{-- =================================================
                     ENSEIGNANTS
                ================================================== --}}

                <flux:sidebar.item
                    icon="user-group"
                    :href="route('teachers.index')"
                    :current="request()->routeIs('teachers.*')"
                    wire:navigate
                >
                    {{ __('Enseignants') }}
                </flux:sidebar.item>


                {{-- =================================================
                     CLASSES
                ================================================== --}}

                <flux:sidebar.item
                    icon="building-office"
                    :href="route('class_rooms.index')"
                    :current="request()->routeIs('class_rooms.*')"
                    wire:navigate
                >
                    {{ __('Classes') }}
                </flux:sidebar.item>


                {{-- =================================================
                     MATIÈRES
                ================================================== --}}

                <flux:sidebar.item
                    icon="book-open"
                    :href="route('subjects.index')"
                    :current="request()->routeIs('subjects.*')"
                    wire:navigate
                >
                    {{ __('Matières') }}
                </flux:sidebar.item>


                {{-- =================================================
                     AFFECTATIONS ENSEIGNANTS
                ================================================== --}}

                <flux:sidebar.item
                    icon="link"
                    :href="route('teacher_subjects.index')"
                    :current="request()->routeIs('teacher_subjects.*')"
                    wire:navigate
                >
                    {{ __('Affectations enseignants') }}
                </flux:sidebar.item>


                {{-- =================================================
                     INSCRIPTIONS
                ================================================== --}}

                <flux:sidebar.item
                    icon="clipboard-document-list"
                    :href="route('inscriptions.index')"
                    :current="request()->routeIs('inscriptions.*')"
                    wire:navigate
                >
                    {{ __('Inscriptions') }}
                </flux:sidebar.item>


                {{-- =================================================
                     NOTES
                ================================================== --}}

                <flux:sidebar.item
                    icon="document-chart-bar"
                    :href="route('notes.index')"
                    :current="request()->routeIs('notes.*')"
                    wire:navigate
                >
                    {{ __('Notes') }}
                </flux:sidebar.item>


                {{-- =================================================
                     PAIEMENTS
                ================================================== --}}

                <flux:sidebar.item
                    icon="banknotes"
                    :href="route('paiements.index')"
                    :current="request()->routeIs('paiements.*')"
                    wire:navigate
                >
                    {{ __('Paiements') }}
                </flux:sidebar.item>


                {{-- =================================================
                     ANNÉES SCOLAIRES
                ================================================== --}}

                <flux:sidebar.item
                    icon="calendar-days"
                    :href="route('school_years.index')"
                    :current="request()->routeIs('school_years.*')"
                    wire:navigate
                >
                    {{ __('Années scolaires') }}
                </flux:sidebar.item>


                {{-- =================================================
                     ÉTABLISSEMENTS
                ================================================== --}}

                <flux:sidebar.item
                    icon="building-library"
                    :href="route('establishments.index')"
                    :current="request()->routeIs('establishments.*')"
                    wire:navigate
                >
                    {{ __('Établissements') }}
                </flux:sidebar.item>


                {{-- =================================================
                     TYPES D'ÉTABLISSEMENT
                ================================================== --}}

                <flux:sidebar.item
                    icon="adjustments-horizontal"
                    :href="route('school_types.index')"
                    :current="request()->routeIs('school_types.*')"
                    wire:navigate
                >
                    {{ __('Types d’établissement') }}
                </flux:sidebar.item>

            </flux:sidebar.group>

        </flux:sidebar.nav>


        {{-- ========================================================
             ESPACEUR
        ========================================================= --}}

        <flux:spacer />


        {{-- ========================================================
             MENU INFÉRIEUR
        ========================================================= --}}

        <flux:sidebar.nav>

            <flux:sidebar.item
                icon="book-open-text"
                href="https://laravel.com/docs/starter-kits#livewire"
                target="_blank"
            >
                {{ __('Documentation') }}
            </flux:sidebar.item>

        </flux:sidebar.nav>


        {{-- ========================================================
             MENU UTILISATEUR
        ========================================================= --}}

        <x-desktop-user-menu
            class="hidden lg:block"
            :name="auth()->user()->name"
        />

    </flux:sidebar>


    {{-- ============================================================
         MOBILE HEADER
    ============================================================ --}}

    <flux:header class="lg:hidden">

        <flux:sidebar.toggle
            class="lg:hidden"
            icon="bars-2"
            inset="left"
        />

        <flux:spacer />


        <flux:dropdown
            position="top"
            align="end"
        >

            <flux:profile
                :initials="auth()->user()->initials()"
                icon-trailing="chevron-down"
            />


            <flux:menu>

                <flux:menu.radio.group>

                    <div class="p-0 text-sm font-normal">

                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">

                            <flux:avatar
                                :name="auth()->user()->name"
                                :initials="auth()->user()->initials()"
                            />

                            <div class="grid flex-1 text-start text-sm leading-tight">

                                <flux:heading class="truncate">
                                    {{ auth()->user()->name }}
                                </flux:heading>

                                <flux:text class="truncate">
                                    {{ auth()->user()->email }}
                                </flux:text>

                            </div>

                        </div>

                    </div>

                </flux:menu.radio.group>


                <flux:menu.separator />


                {{-- Settings --}}
                <flux:menu.radio.group>

                    <flux:menu.item
                        :href="route('profile.edit')"
                        icon="cog"
                        wire:navigate
                    >
                        {{ __('Settings') }}
                    </flux:menu.item>

                </flux:menu.radio.group>


                <flux:menu.separator />


                {{-- Logout --}}
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="w-full"
                >

                    @csrf

                    <flux:menu.item
                        as="button"
                        type="submit"
                        icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer"
                        data-test="logout-button"
                    >
                        {{ __('Log out') }}
                    </flux:menu.item>

                </form>

            </flux:menu>

        </flux:dropdown>

    </flux:header>


    {{-- ============================================================
         CONTENU DE LA PAGE
    ============================================================ --}}

    {{ $slot }}


    {{-- ============================================================
         TOAST
    ============================================================ --}}

    @persist('toast')

        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>

    @endpersist


    {{-- Flux --}}
    @fluxScripts

</body>

</html>
