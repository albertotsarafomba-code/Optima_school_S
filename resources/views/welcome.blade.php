<!DOCTYPE html>

<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>Gestion d'École</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])


</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">


<!-- Navigation -->
<nav class="border-b border-blue-100 bg-white/95 shadow-sm">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">

        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-xl font-bold text-white shadow">
                GE
            </div>

            <div>
                <h1 class="text-xl font-bold text-blue-700">
                    Gestion d'École
                </h1>
                <p class="text-xs text-gray-500">
                    Système de gestion scolaire
                </p>
            </div>
        </div>

        <a href="/login"
           class="rounded-lg bg-green-600 px-5 py-2.5 font-semibold text-white transition hover:bg-green-700">
            Connexion
        </a>

    </div>
</nav>


<!-- Hero -->
<main>

    <section class="mx-auto max-w-7xl px-6 py-16 text-center">

        <div class="mx-auto max-w-3xl">

            <span class="inline-block rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">
                Plateforme de gestion scolaire
            </span>

            <h2 class="mt-6 text-4xl font-extrabold tracking-tight text-gray-800 md:text-5xl">
                Bienvenue dans votre
                <span class="text-blue-600"> système de gestion </span>
                scolaire
            </h2>

            <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 text-gray-600">
                Une plateforme simple et moderne pour gérer les inscriptions,
                les élèves, les enseignants, les classes, les matières,
                les notes et les informations scolaires.
            </p>

        </div>


        <!-- Types d'établissement -->
        <div class="mt-14">

            <h3 class="text-2xl font-bold text-gray-800">
                Choisissez votre établissement
            </h3>

            <p class="mt-2 text-gray-500">
                Sélectionnez le type d'établissement que vous souhaitez gérer.
            </p>


            <div class="mx-auto mt-10 grid max-w-5xl gap-6 sm:grid-cols-2 lg:grid-cols-4">

                <!-- Primaire -->
                <a href="{{ route('dashboard') }}"
                   class="group rounded-2xl border border-blue-100 bg-white p-6 text-left shadow-md transition duration-300 hover:-translate-y-2 hover:border-blue-400 hover:shadow-xl">

                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-100 text-2xl text-blue-600">
                        
                    </div>

                    <h4 class="mt-5 text-xl font-bold text-gray-800 group-hover:text-blue-600">
                        Primaire
                    </h4>

                    <p class="mt-2 text-sm leading-6 text-gray-500">
                        Gestion des élèves, classes, enseignants et inscriptions.
                    </p>

                    <div class="mt-5 font-semibold text-blue-600">
                        Accéder →
                    </div>
                </a>


                <!-- Collège -->
                <a href="{{ route('dashboard') }}"
                   class="group rounded-2xl border border-green-100 bg-white p-6 text-left shadow-md transition duration-300 hover:-translate-y-2 hover:border-green-400 hover:shadow-xl">

                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-green-100 text-2xl text-green-600">
                        
                    </div>

                    <h4 class="mt-5 text-xl font-bold text-gray-800 group-hover:text-green-600">
                        Collège
                    </h4>

                    <p class="mt-2 text-sm leading-6 text-gray-500">
                        Gestion des élèves, classes, matières, notes et absences.
                    </p>

                    <div class="mt-5 font-semibold text-green-600">
                        Accéder →
                    </div>
                </a>


                <!-- Lycée -->
                <a href="{{ route('dashboard') }}"
                   class="group rounded-2xl border border-blue-100 bg-white p-6 text-left shadow-md transition duration-300 hover:-translate-y-2 hover:border-blue-400 hover:shadow-xl">

                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-100 text-2xl text-blue-600">
                        
                    </div>

                    <h4 class="mt-5 text-xl font-bold text-gray-800 group-hover:text-blue-600">
                        Lycée
                    </h4>

                    <p class="mt-2 text-sm leading-6 text-gray-500">
                        Gestion des classes, matières, enseignants et résultats.
                    </p>

                    <div class="mt-5 font-semibold text-blue-600">
                        Accéder →
                    </div>
                </a>


                <!-- Université -->
                <a href="{{ route('dashboard') }}"
                   class="group rounded-2xl border border-green-100 bg-white p-6 text-left shadow-md transition duration-300 hover:-translate-y-2 hover:border-green-400 hover:shadow-xl">

                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-green-100 text-2xl text-green-600">
                        
                    </div>

                    <h4 class="mt-5 text-xl font-bold text-gray-800 group-hover:text-green-600">
                        Université
                    </h4>

                    <p class="mt-2 text-sm leading-6 text-gray-500">
                        Gestion des étudiants, filières, niveaux, matières et notes.
                    </p>

                    <div class="mt-5 font-semibold text-green-600">
                        Accéder →
                    </div>
                </a>

            </div>

        </div>

    </section>


    <!-- Footer -->
    <footer class="border-t border-blue-100 bg-white">
        <div class="mx-auto max-w-7xl px-6 py-6 text-center text-sm text-gray-500">

            <p>
                © {{ date('Y') }} Gestion d'École.
                Tous droits réservés.
            </p>

        </div>
    </footer>

</main>

</body>

</html>
