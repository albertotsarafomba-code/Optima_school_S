<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Modifier un paiement - Gestion d'école</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <nav style="background-color: #1d4ed8; padding: 16px; color: white;">

        <div class="max-w-7xl mx-auto flex justify-between items-center">

            <h1 style="color: white; font-size: 20px; font-weight: bold;">
                Gestion d'école
            </h1>

            <a href="{{ route('paiements.index') }}"
               style="color: white; text-decoration: none; font-weight: bold;">
                Liste des paiements
            </a>

        </div>

    </nav>

    <main class="max-w-4xl mx-auto p-6">

        <div class="bg-white rounded-lg shadow p-6">

            <h2 class="text-2xl font-bold mb-6">
                Modifier un paiement
            </h2>

            @if ($errors->any())

                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">

                    <ul class="list-disc list-inside">

                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif

            <form action="{{ route('paiements.update', $paiement) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <!-- Étudiant -->

                    <div class="md:col-span-2">

                        <label class="block font-medium mb-2">
                            Étudiant *
                        </label>

                        <select
                            name="student_id"
                            required
                            class="w-full border border-gray-300 rounded-lg p-3">

                            @foreach($students as $student)

                                <option value="{{ $student->id }}"
                                    {{ old('student_id', $paiement->student_id) == $student->id ? 'selected' : '' }}>

                                    {{ $student->matricule }} -
                                    {{ $student->nom }}
                                    {{ $student->prenom }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- Montant -->

                    <div>

                        <label class="block font-medium mb-2">
                            Montant (Ar) *
                        </label>

                        <input
                            type="number"
                            name="montant"
                            value="{{ old('montant', $paiement->montant) }}"
                            min="0"
                            step="0.01"
                            required
                            class="w-full border border-gray-300 rounded-lg p-3">

                    </div>


                    <!-- Type -->

                    <div>

                        <label class="block font-medium mb-2">
                            Type de paiement *
                        </label>

                        <select
                            name="type_paiement"
                            required
                            class="w-full border border-gray-300 rounded-lg p-3">

                            @foreach([
                                'Frais de scolarité',
                                'Inscription',
                                'Examen',
                                'Autre'
                            ] as $type)

                                <option value="{{ $type }}"
                                    {{ old('type_paiement', $paiement->type_paiement) == $type ? 'selected' : '' }}>

                                    {{ $type }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- Mode -->

                    <div>

                        <label class="block font-medium mb-2">
                            Mode de paiement *
                        </label>

                        <select
                            name="mode_paiement"
                            required
                            class="w-full border border-gray-300 rounded-lg p-3">

                            @foreach([
                                'Espèces',
                                'Mobile Money',
                                'Virement bancaire',
                                'Chèque'
                            ] as $mode)

                                <option value="{{ $mode }}"
                                    {{ old('mode_paiement', $paiement->mode_paiement) == $mode ? 'selected' : '' }}>

                                    {{ $mode }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- Date -->

                    <div>

                        <label class="block font-medium mb-2">
                            Date du paiement *
                        </label>

                        <input
                            type="date"
                            name="date_paiement"
                            value="{{ old('date_paiement', $paiement->date_paiement?->format('Y-m-d')) }}"
                            required
                            class="w-full border border-gray-300 rounded-lg p-3">

                    </div>


                    <!-- Référence -->

                    <div>

                        <label class="block font-medium mb-2">
                            Référence
                        </label>

                        <input
                            type="text"
                            name="reference"
                            value="{{ old('reference', $paiement->reference) }}"
                            class="w-full border border-gray-300 rounded-lg p-3">

                    </div>


                    <!-- Description -->

                    <div class="md:col-span-2">

                        <label class="block font-medium mb-2">
                            Description
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            class="w-full border border-gray-300 rounded-lg p-3">{{ old('description', $paiement->description) }}</textarea>

                    </div>

                </div>


                <!-- Boutons -->

                <div class="mt-8 flex gap-4">

                    <button
                        type="submit"
                        style="background-color: #2563eb; color: white; padding: 12px 24px; border-radius: 8px; border: none; cursor: pointer; font-weight: bold;">
                        MODIFIER
                    </button>

                    <a
                        href="{{ route('paiements.index') }}"
                        style="background-color: #6b7280; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                        ANNULER
                    </a>

                </div>

            </form>

        </div>

    </main>

</body>

</html>
