<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Créer un établissement - OPTIMASCHOOL</title>

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

<body class="min-h-screen bg-slate-100">

<div class="min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-5xl">

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

            <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[610px]">


                <!-- =====================================================
                     PARTIE GAUCHE
                ====================================================== -->

                <div class="bg-blue-900 text-white p-8 lg:p-10 flex flex-col justify-between">

                    <div>

                        <!-- LOGO -->

                        <div class="flex items-center gap-3 mb-10">

                            <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center shadow-md">

                                <span class="text-xl font-extrabold text-blue-900">
                                    OS
                                </span>

                            </div>

                            <div>

                                <h1 class="text-xl font-extrabold tracking-wide">
                                    OPTIMASCHOOL
                                </h1>

                                <p class="text-blue-200 text-xs">
                                    Gestion d'école
                                </p>

                            </div>

                        </div>


                        <!-- ICONE ETABLISSEMENT -->

                        <div class="w-14 h-14 rounded-xl bg-blue-800 flex items-center justify-center mb-6">

                            <svg
                                class="w-7 h-7 text-green-400"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >

                                <path d="M3 21h18"/>

                                <path d="M5 21V7l7-4 7 4v14"/>

                                <path d="M9 21v-5h6v5"/>

                                <path d="M8 10h1"/>
                                <path d="M15 10h1"/>

                                <path d="M8 13h1"/>
                                <path d="M15 13h1"/>

                            </svg>

                        </div>


                        <!-- TITRE -->

                        <h2 class="text-3xl font-extrabold leading-tight">

                            Créer votre
                            <span class="text-green-400">
                                établissement
                            </span>

                        </h2>


                        <p class="mt-4 text-blue-100 text-sm leading-6 max-w-sm">

                            Configurez votre établissement avant
                            d'accéder au tableau de bord
                            d'OPTIMASCHOOL.

                        </p>


                        <!-- INFORMATIONS -->

                        <div class="mt-10 space-y-5">


                            <!-- INFORMATIONS ETABLISSEMENT -->

                            <div class="flex items-start gap-3">

                                <div class="w-9 h-9 rounded-lg bg-blue-800 flex items-center justify-center shrink-0">

                                    <svg
                                        class="w-5 h-5 text-green-400"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >

                                        <path d="M3 21h18"/>

                                        <path d="M5 21V7l7-4 7 4v14"/>

                                        <path d="M9 21v-5h6v5"/>

                                        <path d="M8 10h1"/>
                                        <path d="M15 10h1"/>

                                        <path d="M8 13h1"/>
                                        <path d="M15 13h1"/>

                                    </svg>

                                </div>


                                <div>

                                    <p class="text-sm font-semibold">
                                        Informations de l'établissement
                                    </p>

                                    <p class="text-xs text-blue-200 mt-1">
                                        Nom, type et coordonnées
                                    </p>

                                </div>

                            </div>


                            <!-- SECURITE -->

                            <div class="flex items-start gap-3">

                                <div class="w-9 h-9 rounded-lg bg-blue-800 flex items-center justify-center shrink-0">

                                    <svg
                                        class="w-5 h-5 text-green-400"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >

                                        <rect
                                            x="4"
                                            y="10"
                                            width="16"
                                            height="11"
                                            rx="2"
                                        />

                                        <path d="M8 10V7a4 4 0 0 1 8 0v3"/>

                                    </svg>

                                </div>


                                <div>

                                    <p class="text-sm font-semibold">
                                        Gestion sécurisée
                                    </p>

                                    <p class="text-xs text-blue-200 mt-1">
                                        Votre établissement sera lié à votre compte
                                    </p>

                                </div>

                            </div>


                            <!-- DASHBOARD -->

                            <div class="flex items-start gap-3">

                                <div class="w-9 h-9 rounded-lg bg-blue-800 flex items-center justify-center shrink-0">

                                    <svg
                                        class="w-5 h-5 text-green-400"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >

                                        <rect
                                            x="3"
                                            y="3"
                                            width="7"
                                            height="7"
                                            rx="1"
                                        />

                                        <rect
                                            x="14"
                                            y="3"
                                            width="7"
                                            height="7"
                                            rx="1"
                                        />

                                        <rect
                                            x="3"
                                            y="14"
                                            width="7"
                                            height="7"
                                            rx="1"
                                        />

                                        <rect
                                            x="14"
                                            y="14"
                                            width="7"
                                            height="7"
                                            rx="1"
                                        />

                                    </svg>

                                </div>


                                <div>

                                    <p class="text-sm font-semibold">
                                        Accès au tableau de bord
                                    </p>

                                    <p class="text-xs text-blue-200 mt-1">
                                        Vous serez automatiquement redirigé
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- =================================================
                         BAS PARTIE GAUCHE
                    ================================================== -->

                    <div class="pt-8">


                        <!-- BOUTON DECONNEXION -->

                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                            class="mb-5"
                        >

                            @csrf

                            <button
                                type="submit"
                                title="Déconnexion"
                                class="group w-full h-11 px-4 rounded-lg
                                       border border-blue-700
                                       bg-blue-800
                                       hover:bg-red-500
                                       hover:border-red-400
                                       text-white
                                       text-sm font-semibold
                                       flex items-center justify-center gap-2
                                       shadow-sm
                                       transition-all duration-200"
                            >

                                <!-- ICONE LOGOUT -->

                                <svg
                                    class="w-5 h-5 transition-transform duration-200 group-hover:translate-x-0.5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >

                                    <path
                                        d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"
                                    />

                                    <polyline
                                        points="16 17 21 12 16 7"
                                    />

                                    <line
                                        x1="21"
                                        y1="12"
                                        x2="9"
                                        y2="12"
                                    />

                                </svg>


                                <span>
                                    Déconnexion
                                </span>

                            </button>

                        </form>


                        <!-- COPYRIGHT -->

                        <div class="text-xs text-blue-200">

                            © {{ date('Y') }} OPTIMASCHOOL

                        </div>

                    </div>

                </div>


                <!-- =====================================================
                     PARTIE DROITE
                ====================================================== -->

                <div class="p-7 md:p-9 flex flex-col justify-center">

                    <div class="max-w-md w-full mx-auto">


                        <!-- HEADER -->

                        <div class="mb-7">

                            <div class="flex items-center gap-2 mb-2">

                                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">

                                    <svg
                                        class="w-4 h-4 text-green-600"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >

                                        <path d="M5 12l4 4L19 6"/>

                                    </svg>

                                </div>


                                <span class="text-xs font-bold uppercase tracking-wider text-green-600">
                                    Configuration
                                </span>

                            </div>


                            <h2 class="text-2xl font-bold text-slate-800">
                                Nouvel établissement
                            </h2>


                            <p class="text-sm text-slate-500 mt-1">
                                Renseignez les informations de votre établissement.
                            </p>

                        </div>


                        <!-- ERREURS -->

                        @if ($errors->any())

                            <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-3">

                                <div class="flex items-start gap-2">

                                    <svg
                                        class="w-5 h-5 text-red-500 shrink-0 mt-0.5"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >

                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="9"
                                        />

                                        <path d="M12 8v4"/>

                                        <path d="M12 16h.01"/>

                                    </svg>


                                    <div class="text-sm text-red-700 space-y-1">

                                        @foreach ($errors->all() as $error)

                                            <p>
                                                {{ $error }}
                                            </p>

                                        @endforeach

                                    </div>

                                </div>

                            </div>

                        @endif


                        <!-- FORMULAIRE -->

                        <form
                            method="POST"
                            action="{{ route('establishments.store') }}"
                            enctype="multipart/form-data"
                            class="space-y-4"
                        >

                            @csrf


                            <!-- TYPE ETABLISSEMENT -->

                            <div>

                                <label
                                    for="school_type_id"
                                    class="block text-sm font-semibold text-slate-700 mb-1.5"
                                >

                                    Type d'établissement

                                    <span class="text-red-500">
                                        *
                                    </span>

                                </label>


                                <div class="relative">

                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                                        <svg
                                            class="w-5 h-5 text-slate-400"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >

                                            <path d="M3 21h18"/>

                                            <path d="M5 21V7l7-4 7 4v14"/>

                                            <path d="M9 21v-5h6v5"/>

                                            <path d="M8 10h1"/>
                                            <path d="M15 10h1"/>

                                            <path d="M8 13h1"/>
                                            <path d="M15 13h1"/>

                                        </svg>

                                    </div>


                                    <select
                                        id="school_type_id"
                                        name="school_type_id"
                                        required
                                        class="w-full h-11 pl-10 pr-10 rounded-lg border border-slate-300 bg-white text-sm text-slate-700 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none transition appearance-none"
                                    >

                                        <option value="">
                                            Sélectionnez le type
                                        </option>


                                        @foreach ($schoolTypes as $schoolType)

                                            <option
                                                value="{{ $schoolType->id }}"
                                                {{ old('school_type_id') == $schoolType->id ? 'selected' : '' }}
                                            >

                                                {{ $schoolType->name }}

                                            </option>

                                        @endforeach

                                    </select>


                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">

                                        <svg
                                            class="w-4 h-4 text-slate-400"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >

                                            <path d="M6 9l6 6 6-6"/>

                                        </svg>

                                    </div>

                                </div>

                            </div>


                            <!-- NOM -->

                            <div>

                                <label
                                    for="nom"
                                    class="block text-sm font-semibold text-slate-700 mb-1.5"
                                >

                                    Nom de l'établissement

                                    <span class="text-red-500">
                                        *
                                    </span>

                                </label>


                                <div class="relative">

                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                                        <svg
                                            class="w-5 h-5 text-slate-400"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >

                                            <path d="M3 21h18"/>

                                            <path d="M5 21V7l7-4 7 4v14"/>

                                            <path d="M9 21v-5h6v5"/>

                                            <path d="M8 10h1"/>
                                            <path d="M15 10h1"/>

                                            <path d="M8 13h1"/>
                                            <path d="M15 13h1"/>

                                        </svg>

                                    </div>


                                    <input
                                        id="nom"
                                        type="text"
                                        name="nom"
                                        value="{{ old('nom') }}"
                                        required
                                        maxlength="255"
                                        placeholder="Ex : ISSTM"
                                        class="w-full h-11 pl-10 pr-4 rounded-lg border border-slate-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition"
                                    >

                                </div>

                            </div>


                            <!-- ADRESSE -->

                            <div>

                                <label
                                    for="adresse"
                                    class="block text-sm font-semibold text-slate-700 mb-1.5"
                                >
                                    Adresse
                                </label>


                                <div class="relative">

                                    <div class="absolute top-3 left-0 pl-3 flex items-center pointer-events-none">

                                        <svg
                                            class="w-5 h-5 text-slate-400"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >

                                            <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0z"/>

                                            <circle
                                                cx="12"
                                                cy="10"
                                                r="2.5"
                                            />

                                        </svg>

                                    </div>


                                    <textarea
                                        id="adresse"
                                        name="adresse"
                                        rows="2"
                                        maxlength="255"
                                        placeholder="Ex : Mahajanga Be"
                                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-slate-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition resize-none"
                                    >{{ old('adresse') }}</textarea>

                                </div>

                            </div>


                            <!-- TELEPHONE + EMAIL -->

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">


                                <!-- TELEPHONE -->

                                <div>

                                    <label
                                        for="telephone"
                                        class="block text-sm font-semibold text-slate-700 mb-1.5"
                                    >
                                        Téléphone
                                    </label>


                                    <div class="relative">

                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                                            <svg
                                                class="w-5 h-5 text-slate-400"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >

                                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>

                                            </svg>

                                        </div>


                                        <input
                                            id="telephone"
                                            type="text"
                                            name="telephone"
                                            value="{{ old('telephone') }}"
                                            maxlength="30"
                                            placeholder="+261..."
                                            class="w-full h-11 pl-10 pr-3 rounded-lg border border-slate-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition"
                                        >

                                    </div>

                                </div>


                                <!-- EMAIL -->

                                <div>

                                    <label
                                        for="email"
                                        class="block text-sm font-semibold text-slate-700 mb-1.5"
                                    >
                                        E-mail
                                    </label>


                                    <div class="relative">

                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                                            <svg
                                                class="w-5 h-5 text-slate-400"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >

                                                <rect
                                                    x="3"
                                                    y="5"
                                                    width="18"
                                                    height="14"
                                                    rx="2"
                                                />

                                                <path d="M3 7l9 6 9-6"/>

                                            </svg>

                                        </div>


                                        <input
                                            id="email"
                                            type="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            maxlength="255"
                                            placeholder="contact@ecole.com"
                                            class="w-full h-11 pl-10 pr-3 rounded-lg border border-slate-300 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 outline-none text-sm transition"
                                        >

                                    </div>

                                </div>

                            </div>


                            <!-- LOGO -->

                            <div>

                                <label
                                    for="logo"
                                    class="block text-sm font-semibold text-slate-700 mb-1.5"
                                >
                                    Logo de l'établissement
                                </label>

                                <div class="flex flex-col sm:flex-row sm:items-center gap-4">

                                    <div
                                        id="logo-preview-container"
                                        class="hidden w-20 h-20 rounded-lg border border-slate-300 bg-slate-50 overflow-hidden shrink-0"
                                    >
                                        <img
                                            id="logo-preview"
                                            src=""
                                            alt="Aperçu du logo"
                                            class="w-full h-full object-contain"
                                        >
                                    </div>

                                    <div class="flex-1">

                                        <input
                                            id="logo"
                                            type="file"
                                            name="logo"
                                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                            class="block w-full text-sm text-slate-600
                                                   file:mr-4 file:py-2.5 file:px-4
                                                   file:rounded-lg file:border-0
                                                   file:bg-blue-50 file:text-blue-700
                                                   file:font-semibold
                                                   hover:file:bg-blue-100
                                                   cursor-pointer"
                                        >

                                        <p class="mt-1.5 text-xs text-slate-500">
                                            JPG, JPEG, PNG ou WEBP — 5 Mo maximum.
                                        </p>

                                        @error('logo')
                                            <p class="mt-1 text-xs text-red-600">
                                                {{ $message }}
                                            </p>
                                        @enderror

                                    </div>

                                </div>

                            </div>


                            <!-- INFORMATION -->

                            <div class="flex items-start gap-2 p-3 rounded-lg bg-blue-50 border border-blue-100">

                                <svg
                                    class="w-5 h-5 text-blue-600 shrink-0 mt-0.5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="9"
                                    />

                                    <path d="M12 11v5"/>

                                    <path d="M12 8h.01"/>

                                </svg>


                                <p class="text-xs text-blue-700 leading-5">

                                    Après la création, cet établissement sera automatiquement
                                    sélectionné pour votre session.

                                </p>

                            </div>


                            <!-- BOUTONS -->

                            <div class="flex flex-col sm:flex-row gap-3 pt-1">


                                <!-- RETOUR -->

                                <a
                                    href="{{ route('dashboard') }}"
                                    class="sm:w-1/3 h-11 rounded-lg border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 font-semibold text-sm flex items-center justify-center gap-2 transition"
                                >

                                    <svg
                                        class="w-4 h-4"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >

                                        <path d="M19 12H5"/>

                                        <path d="M12 19l-7-7 7-7"/>

                                    </svg>

                                    Retour

                                </a>


                                <!-- CREER -->

                                <button
                                    type="submit"
                                    class="sm:flex-1 h-11 rounded-lg bg-blue-900 hover:bg-blue-950 text-white font-semibold text-sm shadow-md hover:shadow-lg flex items-center justify-center gap-2 transition"
                                >

                                    <svg
                                        class="w-5 h-5"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >

                                        <path d="M12 5v14"/>

                                        <path d="M5 12h14"/>

                                    </svg>

                                    Créer l'établissement

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const logoInput = document.getElementById('logo');
            const previewContainer = document.getElementById('logo-preview-container');
            const preview = document.getElementById('logo-preview');

            if (!logoInput || !previewContainer || !preview) {
                return;
            }

            logoInput.addEventListener('change', function () {
                const file = this.files && this.files[0];

                if (!file) {
                    preview.src = '';
                    previewContainer.classList.add('hidden');
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    preview.src = '';
                    previewContainer.classList.add('hidden');
                    return;
                }

                const reader = new FileReader();

                reader.onload = function (event) {
                    preview.src = event.target.result;
                    previewContainer.classList.remove('hidden');
                };

                reader.readAsDataURL(file);
            });
        });
    </script>

</body>
</html>