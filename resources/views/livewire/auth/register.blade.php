<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Créer un compte - OPTIMASCHOOL</title>

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

<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">

<div class="w-full max-w-5xl">

    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

        <div class="grid grid-cols-1 md:grid-cols-2 min-h-[600px]">


            <!-- =========================
                 PARTIE GAUCHE
            ========================== -->

            <div class="bg-blue-900 text-white p-8 flex flex-col justify-between">

                <div>

                    <!-- Logo -->

                    <div class="flex items-center gap-3 mb-8">

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



                    <!-- Introduction -->

                    <div>

                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full
                                    bg-green-500/20 border border-green-400/30
                                    text-green-300 text-xs font-semibold mb-4">

                            <svg
                                class="w-4 h-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <path d="M5 12l4 4L19 6"/>

                            </svg>

                            Création de compte

                        </div>


                        <h2 class="text-3xl font-extrabold leading-tight">

                            Rejoignez
                            <span class="text-green-400">
                                OPTIMASCHOOL
                            </span>

                        </h2>


                        <p class="mt-4 text-blue-100 text-sm leading-6 max-w-sm">

                            Créez votre compte administrateur pour
                            commencer à gérer votre établissement
                            avec une plateforme moderne et sécurisée.

                        </p>

                    </div>



                    <!-- Sécurité -->

                    <div class="mt-8">

                        <div class="flex items-start gap-3">

                            <div class="w-10 h-10 rounded-lg bg-blue-800 flex items-center justify-center shrink-0">

                                <svg
                                    class="w-5 h-5 text-green-400"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >

                                    <rect x="4" y="10" width="16" height="11" rx="2"/>
                                    <path d="M8 10V7a4 4 0 0 1 8 0v3"/>

                                </svg>

                            </div>


                            <div>

                                <p class="font-semibold text-sm">
                                    Compte sécurisé
                                </p>

                                <p class="text-xs text-blue-200 mt-1 leading-5">

                                    Votre mot de passe doit respecter
                                    plusieurs règles de sécurité.

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- Footer -->

                <div class="text-xs text-blue-200 pt-6">

                    © {{ date('Y') }} OPTIMASCHOOL

                </div>

            </div>



            <!-- =========================
                 PARTIE DROITE
            ========================== -->

            <div class="p-7 md:p-8 flex flex-col justify-center">

                <div class="max-w-md w-full mx-auto">


                    <!-- Titre -->

                    <div class="mb-5">

                        <h2 class="text-2xl font-bold text-slate-800">
                            Créer un compte
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Remplissez les informations ci-dessous
                        </p>

                    </div>



                    <!-- Erreurs Laravel -->

                    @if ($errors->any())

                        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-3">

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



                    <form
                        id="registerForm"
                        method="POST"
                        action="{{ route('register') }}"
                        class="space-y-4"
                    >

                        @csrf



                        <!-- =========================
                             NOM
                        ========================== -->

                        <div>

                            <label
                                for="name"
                                class="block text-sm font-semibold text-slate-700 mb-1.5"
                            >
                                Nom complet
                            </label>


                            <div class="relative">

                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                                    <svg
                                        class="w-5 h-5 text-slate-400"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >

                                        <circle cx="12" cy="7" r="4"/>
                                        <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>

                                    </svg>

                                </div>


                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    autofocus
                                    autocomplete="name"
                                    placeholder="Votre nom complet"
                                    class="w-full h-10 pl-10 pr-4 rounded-lg border border-slate-300
                                           focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                           outline-none text-sm transition"
                                >

                            </div>

                        </div>



                        <!-- =========================
                             EMAIL
                        ========================== -->

                        <div>

                            <label
                                for="email"
                                class="block text-sm font-semibold text-slate-700 mb-1.5"
                            >
                                Adresse e-mail
                            </label>


                            <div class="relative">

                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                                    <svg
                                        class="w-5 h-5 text-slate-400"
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
                                    autocomplete="email"
                                    placeholder="admin@example.com"
                                    class="w-full h-10 pl-10 pr-4 rounded-lg border border-slate-300
                                           focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                           outline-none text-sm transition"
                                >

                            </div>

                        </div>



                        <!-- =========================
                             PASSWORD
                        ========================== -->

                        <div>

                            <label
                                for="password"
                                class="block text-sm font-semibold text-slate-700 mb-1.5"
                            >
                                Mot de passe
                            </label>


                            <div class="relative">

                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                                    <svg
                                        class="w-5 h-5 text-slate-400"
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
                                    minlength="8"
                                    autocomplete="new-password"
                                    placeholder="Minimum 8 caractères"
                                    class="w-full h-10 pl-10 pr-11 rounded-lg border border-slate-300
                                           focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                           outline-none text-sm transition"
                                >


                                <button
                                    type="button"
                                    id="togglePassword"
                                    class="absolute inset-y-0 right-0 px-3 text-slate-400 hover:text-blue-700 transition"
                                    aria-label="Afficher le mot de passe"
                                >

                                    <svg
                                        id="eyePassword"
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



                            <!-- Requirements -->

                            <div
                                id="passwordRequirements"
                                class="mt-2 p-3 rounded-lg bg-slate-50 border border-slate-200"
                            >

                                <p class="text-xs font-semibold text-slate-600 mb-2">
                                    Exigences du mot de passe
                                </p>


                                <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 text-xs">


                                    <!-- Longueur -->

                                    <div
                                        id="ruleLength"
                                        class="password-rule flex items-center gap-1.5 text-slate-500"
                                    >

                                        <svg
                                            class="rule-icon w-4 h-4 shrink-0"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >

                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="M12 8v4"/>
                                            <path d="M12 16h.01"/>

                                        </svg>


                                        <span>
                                            8 caractères minimum
                                        </span>

                                    </div>



                                    <!-- Majuscule -->

                                    <div
                                        id="ruleUppercase"
                                        class="password-rule flex items-center gap-1.5 text-slate-500"
                                    >

                                        <svg
                                            class="rule-icon w-4 h-4 shrink-0"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >

                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="M12 8v4"/>
                                            <path d="M12 16h.01"/>

                                        </svg>


                                        <span>
                                            Une majuscule
                                        </span>

                                    </div>



                                    <!-- Minuscule -->

                                    <div
                                        id="ruleLowercase"
                                        class="password-rule flex items-center gap-1.5 text-slate-500"
                                    >

                                        <svg
                                            class="rule-icon w-4 h-4 shrink-0"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >

                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="M12 8v4"/>
                                            <path d="M12 16h.01"/>

                                        </svg>


                                        <span>
                                            Une minuscule
                                        </span>

                                    </div>



                                    <!-- Chiffre -->

                                    <div
                                        id="ruleNumber"
                                        class="password-rule flex items-center gap-1.5 text-slate-500"
                                    >

                                        <svg
                                            class="rule-icon w-4 h-4 shrink-0"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >

                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="M12 8v4"/>
                                            <path d="M12 16h.01"/>

                                        </svg>


                                        <span>
                                            Un chiffre
                                        </span>

                                    </div>



                                    <!-- Symbole -->

                                    <div
                                        id="ruleSymbol"
                                        class="password-rule flex items-center gap-1.5 text-slate-500"
                                    >

                                        <svg
                                            class="rule-icon w-4 h-4 shrink-0"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >

                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="M12 8v4"/>
                                            <path d="M12 16h.01"/>

                                        </svg>


                                        <span>
                                            Un symbole
                                        </span>

                                    </div>

                                </div>

                            </div>



                            <!-- Erreur longueur -->

                            <p
                                id="passwordLengthError"
                                class="hidden text-xs text-red-600 mt-1.5"
                            >
                                Le mot de passe doit contenir au minimum 8 caractères.
                            </p>

                        </div>



                        <!-- =========================
                             CONFIRMATION
                        ========================== -->

                        <div>

                            <label
                                for="password_confirmation"
                                class="block text-sm font-semibold text-slate-700 mb-1.5"
                            >
                                Confirmer le mot de passe
                            </label>


                            <div class="relative">

                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                                    <svg
                                        class="w-5 h-5 text-slate-400"
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
                                    id="password_confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    required
                                    minlength="8"
                                    autocomplete="new-password"
                                    placeholder="Confirmez votre mot de passe"
                                    class="w-full h-10 pl-10 pr-11 rounded-lg border border-slate-300
                                           focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                                           outline-none text-sm transition"
                                >


                                <button
                                    type="button"
                                    id="togglePasswordConfirmation"
                                    class="absolute inset-y-0 right-0 px-3 text-slate-400 hover:text-blue-700 transition"
                                    aria-label="Afficher la confirmation du mot de passe"
                                >

                                    <svg
                                        id="eyePasswordConfirmation"
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



                            <!-- Match -->

                            <p
                                id="passwordMatch"
                                class="hidden text-xs mt-1.5 flex items-center gap-1.5"
                            >

                                <svg
                                    id="passwordMatchIcon"
                                    class="w-4 h-4"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                </svg>


                                <span id="passwordMatchText"></span>

                            </p>

                        </div>



                        <!-- =========================
                             BOUTON
                        ========================== -->

                        <button
                            id="registerButton"
                            type="submit"
                            class="w-full h-10 rounded-lg bg-blue-900
                                   hover:bg-blue-950
                                   text-white font-semibold text-sm shadow-md
                                   hover:shadow-lg transition
                                   flex items-center justify-center gap-2"
                        >

                            <svg
                                class="w-5 h-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >

                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M19 8v6"/>
                                <path d="M22 11h-6"/>

                            </svg>

                            Créer mon compte

                        </button>

                    </form>



                    <!-- Login -->

                    <div class="mt-5 text-center">

                        <span class="text-sm text-slate-500">
                            Vous avez déjà un compte ?
                        </span>

                        <a
                            href="{{ route('login') }}"
                            class="text-sm font-bold text-blue-700 hover:text-green-600 transition ml-1"
                        >
                            Se connecter
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | Afficher / masquer mot de passe
    |--------------------------------------------------------------------------
    */

    function setupPasswordToggle(inputId, buttonId, eyeId) {

        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);
        const eye = document.getElementById(eyeId);

        if (!input || !button || !eye) {
            return;
        }


        button.addEventListener('click', function () {

            const visible = input.type === 'text';

            input.type = visible ? 'password' : 'text';


            button.setAttribute(
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

    }


    setupPasswordToggle(
        'password',
        'togglePassword',
        'eyePassword'
    );


    setupPasswordToggle(
        'password_confirmation',
        'togglePasswordConfirmation',
        'eyePasswordConfirmation'
    );



    /*
    |--------------------------------------------------------------------------
    | Elements
    |--------------------------------------------------------------------------
    */

    const password = document.getElementById('password');
    const confirmation = document.getElementById('password_confirmation');

    const lengthError = document.getElementById('passwordLengthError');

    const matchBox = document.getElementById('passwordMatch');
    const matchIcon = document.getElementById('passwordMatchIcon');
    const matchText = document.getElementById('passwordMatchText');

    const form = document.getElementById('registerForm');



    /*
    |--------------------------------------------------------------------------
    | Etat des règles
    |--------------------------------------------------------------------------
    */

    function updateRule(id, valid) {

        const rule = document.getElementById(id);

        if (!rule) {
            return;
        }

        const icon = rule.querySelector('.rule-icon');


        if (valid) {

            rule.classList.remove(
                'text-slate-500',
                'text-red-600'
            );

            rule.classList.add(
                'text-green-600'
            );


            icon.innerHTML = `
                <path d="M5 12l4 4L19 6"/>
            `;

        } else {

            rule.classList.remove(
                'text-green-600'
            );

            rule.classList.add(
                'text-slate-500'
            );


            icon.innerHTML = `
                <circle cx="12" cy="12" r="9"/>
                <path d="M12 8v4"/>
                <path d="M12 16h.01"/>
            `;

        }

    }



    /*
    |--------------------------------------------------------------------------
    | Validation password
    |--------------------------------------------------------------------------
    */

    function validatePassword() {

        const value = password.value;


        const hasLength =
            value.length >= 8;

        const hasUppercase =
            /[A-Z]/.test(value);

        const hasLowercase =
            /[a-z]/.test(value);

        const hasNumber =
            /[0-9]/.test(value);

        const hasSymbol =
            /[^A-Za-z0-9]/.test(value);


        updateRule(
            'ruleLength',
            hasLength
        );


        updateRule(
            'ruleUppercase',
            hasUppercase
        );


        updateRule(
            'ruleLowercase',
            hasLowercase
        );


        updateRule(
            'ruleNumber',
            hasNumber
        );


        updateRule(
            'ruleSymbol',
            hasSymbol
        );


        if (
            value.length > 0 &&
            !hasLength
        ) {

            lengthError.classList.remove(
                'hidden'
            );

        } else {

            lengthError.classList.add(
                'hidden'
            );

        }


        return (
            hasLength &&
            hasUppercase &&
            hasLowercase &&
            hasNumber &&
            hasSymbol
        );

    }



    /*
    |--------------------------------------------------------------------------
    | Validation confirmation
    |--------------------------------------------------------------------------
    */

    function validateConfirmation() {

        const value = confirmation.value;


        if (value === '') {

            matchBox.classList.add(
                'hidden'
            );


            confirmation.classList.remove(
                'border-green-500',
                'border-red-500'
            );


            return false;

        }


        matchBox.classList.remove(
            'hidden'
        );


        if (password.value === value) {

            matchBox.classList.remove(
                'text-red-600'
            );

            matchBox.classList.add(
                'text-green-600'
            );


            matchIcon.innerHTML = `
                <path d="M5 12l4 4L19 6"/>
            `;


            matchText.textContent =
                'Les mots de passe correspondent.';


            confirmation.classList.remove(
                'border-red-500'
            );

            confirmation.classList.add(
                'border-green-500'
            );


            return true;

        }


        matchBox.classList.remove(
            'text-green-600'
        );

        matchBox.classList.add(
            'text-red-600'
        );


        matchIcon.innerHTML = `
            <circle cx="12" cy="12" r="9"/>
            <path d="M15 9l-6 6"/>
            <path d="M9 9l6 6"/>
        `;


        matchText.textContent =
            'Les mots de passe ne correspondent pas.';


        confirmation.classList.remove(
            'border-green-500'
        );

        confirmation.classList.add(
            'border-red-500'
        );


        return false;

    }



    /*
    |--------------------------------------------------------------------------
    | Saisie password
    |--------------------------------------------------------------------------
    */

    password.addEventListener(
        'input',
        function () {

            validatePassword();

            if (confirmation.value !== '') {

                validateConfirmation();

            }

        }
    );



    /*
    |--------------------------------------------------------------------------
    | Saisie confirmation
    |--------------------------------------------------------------------------
    */

    confirmation.addEventListener(
        'input',
        function () {

            validateConfirmation();

        }
    );



    /*
    |--------------------------------------------------------------------------
    | Validation avant envoi
    |--------------------------------------------------------------------------
    */

    form.addEventListener(
        'submit',
        function (event) {

            const passwordValid =
                validatePassword();

            const confirmationValid =
                validateConfirmation();


            if (
                !passwordValid ||
                !confirmationValid
            ) {

                event.preventDefault();


                if (!passwordValid) {

                    password.focus();

                    return;

                }


                if (!confirmationValid) {

                    confirmation.focus();

                }

            }

        }
    );

});

</script>

</body>
</html>
