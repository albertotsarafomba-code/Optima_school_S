@extends('layouts.gestion-ecole')

@section('title', 'Modifier le paiement')

@section('page-title', 'Modifier le paiement')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">


{{-- En-tête --}}
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

    <div>
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center"
                 style="background-color: #eff6ff; color: #1e3a8a;">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-6 h-6"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-8.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 7.5-7.5z"/>
                </svg>
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Modifier le paiement
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Corrigez les informations du paiement puis enregistrez les modifications.
                </p>
            </div>
        </div>
    </div>

    <a href="{{ route('paiements.show', $paiement) }}"
       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg
              border border-gray-300 bg-white text-gray-700 font-semibold
              hover:bg-gray-50 transition">

        <svg xmlns="http://www.w3.org/2000/svg"
             class="w-5 h-5"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor"
             stroke-width="2">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15 19l-7-7 7-7"/>
        </svg>

        Retour au paiement
    </a>

</div>


{{-- Messages d'erreur --}}
@if ($errors->any())

    <div class="rounded-xl border border-red-200 bg-red-50 p-4">

        <div class="flex items-start gap-3">

            <div class="flex-shrink-0 text-red-600 mt-0.5">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="2">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M12 9v4m0 4h.01M10.29 3.86l-7.82 14a2 2 0 001.74 3h15.58a2 2 0 001.74-3l-7.82-14a2 2 0 00-3.42 0z"/>
                </svg>
            </div>

            <div>
                <p class="font-semibold text-red-800">
                    Impossible d'enregistrer les modifications
                </p>

                <ul class="mt-2 list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

        </div>

    </div>

@endif


<form action="{{ route('paiements.update', $paiement) }}"
      method="POST"
      class="space-y-6">

    @csrf
    @method('PUT')


    {{-- Informations générales --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">

            <div class="flex items-center gap-3">

                <div class="w-9 h-9 rounded-lg flex items-center justify-center"
                     style="background-color: #eff6ff; color: #1e3a8a;">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>

                </div>

                <div>
                    <h3 class="font-bold text-gray-800">
                        Bénéficiaire
                    </h3>

                    <p class="text-xs text-gray-500">
                        Personne concernée par ce paiement
                    </p>
                </div>

            </div>

        </div>


        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Type bénéficiaire --}}
                <div>

                    <label for="beneficiaire_type"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Bénéficiaire
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="beneficiaire_type"
                        name="beneficiaire_type"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3
                               focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                               bg-white">

                        <option value="student"
                            {{ old('beneficiaire_type', $beneficiaireType) === 'student' ? 'selected' : '' }}>
                            Élève
                        </option>

                        <option value="teacher"
                            {{ old('beneficiaire_type', $beneficiaireType) === 'teacher' ? 'selected' : '' }}>
                            Enseignant
                        </option>

                    </select>

                </div>


                {{-- Année scolaire --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Année scolaire
                    </label>

                    <div class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-gray-700">

                        <div class="flex items-center gap-2">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5 text-gray-400"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>

                            <span class="font-medium">
                                {{ $schoolYear?->name ?? 'Non définie' }}
                            </span>

                        </div>

                    </div>

                    <p class="text-xs text-gray-500 mt-1">
                        L'année scolaire ne peut pas être modifiée ici.
                    </p>

                </div>


                {{-- Élève --}}
                <div id="student-wrapper"
                     class="md:col-span-2">

                    <label for="student_id"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Élève
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="student_id"
                        name="student_id"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3
                               focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                               bg-white">

                        <option value="">
                            Sélectionner un élève
                        </option>

                        @foreach($students as $student)

                            <option value="{{ $student->id }}"
                                {{ old('student_id', $paiement->student_id) == $student->id ? 'selected' : '' }}>

                                {{ $student->matricule }}
                                —
                                {{ $student->nom }} {{ $student->prenom }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Enseignant --}}
                <div id="teacher-wrapper"
                     class="md:col-span-2">

                    <label for="teacher_id"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Enseignant
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="teacher_id"
                        name="teacher_id"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3
                               focus:border-blue-600 focus:ring-2 focus:ring-blue-100
                               bg-white">

                        <option value="">
                            Sélectionner un enseignant
                        </option>

                        @foreach($teachers as $teacher)

                            <option value="{{ $teacher->id }}"
                                {{ old('teacher_id', $paiement->teacher_id) == $teacher->id ? 'selected' : '' }}>

                                {{ $teacher->nom }} {{ $teacher->prenom }}

                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

        </div>

    </div>


    {{-- Informations du paiement --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">

            <div class="flex items-center gap-3">

                <div class="w-9 h-9 rounded-lg flex items-center justify-center"
                     style="background-color: #f0fdf4; color: #15803d;">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.657 0 3 .895 3 2s-1.343 2-3 2-3-.895-3-2 1.343-2 3-2zm0 0V5m0 14v-3m9-4a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>

                </div>

                <div>
                    <h3 class="font-bold text-gray-800">
                        Informations du paiement
                    </h3>

                    <p class="text-xs text-gray-500">
                        Montant et nature de l'opération
                    </p>
                </div>

            </div>

        </div>


        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Montant --}}
                <div>

                    <label for="montant"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Montant
                        <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">

                        <input
                            id="montant"
                            type="number"
                            name="montant"
                            value="{{ old('montant', $paiement->montant) }}"
                            min="1"
                            step="0.01"
                            required
                            class="w-full rounded-lg border border-gray-300 px-4 py-3 pr-16
                                   focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                        <span class="absolute right-4 top-1/2 -translate-y-1/2
                                     text-sm font-semibold text-gray-500">
                            Ar
                        </span>

                    </div>

                </div>


                {{-- Type de paiement --}}
                <div>

                    <label for="type_paiement"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Type de paiement
                        <span class="text-red-500">*</span>
                    </label>

                    <select
                        id="type_paiement"
                        name="type_paiement"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3
                               focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                        @foreach($typesPaiement as $type)

                            <option value="{{ $type }}"
                                {{ old('type_paiement', $paiement->type_paiement) === $type ? 'selected' : '' }}>

                                {{ $type }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Mois --}}
                <div id="mois-wrapper">

                    <label for="mois"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Mois de l'écolage
                    </label>

                    <select
                        id="mois"
                        name="mois"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3
                               focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                        <option value="">
                            Sélectionner le mois
                        </option>

                        @foreach($moisDisponibles as $numero => $nom)

                            <option value="{{ $numero }}"
                                {{ old('mois', $paiement->mois) == $numero ? 'selected' : '' }}>

                                {{ $nom }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Année --}}
                <div id="annee-wrapper">

                    <label for="annee"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Année du mois payé
                    </label>

                    <input
                        id="annee"
                        type="number"
                        name="annee"
                        value="{{ old('annee', $paiement->annee ?? now()->year) }}"
                        min="2000"
                        max="2100"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3
                               focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                </div>

            </div>

        </div>

    </div>


    {{-- Mode de paiement --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">

            <div class="flex items-center gap-3">

                <div class="w-9 h-9 rounded-lg flex items-center justify-center"
                     style="background-color: #eff6ff; color: #1e3a8a;">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M3 10h18M7 15h1m3 0h2m2 0h2M5 6h14a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"/>
                    </svg>

                </div>

                <div>
                    <h3 class="font-bold text-gray-800">
                        Règlement
                    </h3>

                    <p class="text-xs text-gray-500">
                        Mode, date et référence de la transaction
                    </p>
                </div>

            </div>

        </div>


        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Mode --}}
                <div>

                    <label for="mode_paiement"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Mode de paiement
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="text"
                        value="Espèces"
                        readonly
                        class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-3
                               font-semibold text-gray-800">

                    <input
                        type="hidden"
                        name="mode_paiement"
                        value="Espèces">

                </div>


                {{-- Date --}}
                <div>

                    <label for="date_paiement"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Date du paiement
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="date_paiement"
                        type="date"
                        name="date_paiement"
                        value="{{ old('date_paiement', optional($paiement->date_paiement)->format('Y-m-d')) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3
                               focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                </div>


                {{-- Référence --}}
                <div>

                    <label for="reference"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Référence de transaction
                    </label>

                    <input
                        id="reference"
                        type="text"
                        name="reference"
                        value="{{ old('reference', $paiement->reference) }}"
                        maxlength="255"
                        placeholder="Référence facultative"
                        class="w-full rounded-lg border border-gray-300 px-4 py-3
                               focus:border-blue-600 focus:ring-2 focus:ring-blue-100">

                    <p class="text-xs text-gray-500 mt-1">
                        Référence facultative pour identifier le paiement.
                    </p>

                </div>


                {{-- Numéro reçu --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Numéro de reçu
                    </label>

                    <div class="relative">

                        <input
                            type="text"
                            value="{{ $paiement->numero_recu }}"
                            readonly
                            class="w-full rounded-lg border border-gray-200 bg-gray-50
                                   px-4 py-3 text-gray-600 font-semibold">

                        <span class="absolute right-3 top-1/2 -translate-y-1/2
                                     text-green-600">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="w-5 h-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M5 13l4 4L19 7"/>
                            </svg>

                        </span>

                    </div>

                    <p class="text-xs text-gray-500 mt-1">
                        Le numéro de reçu reste inchangé.
                    </p>

                </div>


                {{-- Description --}}
                <div class="md:col-span-2">

                    <label for="description"
                           class="block text-sm font-semibold text-gray-700 mb-2">
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="Informations complémentaires concernant le paiement..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-3
                               focus:border-blue-600 focus:ring-2 focus:ring-blue-100">{{ old('description', $paiement->description) }}</textarea>

                </div>

            </div>

        </div>

    </div>


    {{-- Résumé --}}
    <div class="rounded-xl border border-blue-100 bg-blue-50 p-5">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>

                <p class="text-sm text-blue-700 font-medium">
                    Montant du paiement
                </p>

                <p id="montant-resume"
                   class="text-2xl font-bold text-blue-900 mt-1">
                    {{ number_format((float) old('montant', $paiement->montant), 0, ',', ' ') }} Ar
                </p>

            </div>

            <div class="text-sm text-blue-700">

                <div>
                    Reçu :
                    <span class="font-semibold">
                        {{ $paiement->numero_recu }}
                    </span>
                </div>

                @if($schoolYear)
                    <div class="mt-1">
                        Année :
                        <span class="font-semibold">
                            {{ $schoolYear->name }}
                        </span>
                    </div>
                @endif

            </div>

        </div>

    </div>


    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row sm:justify-end gap-3 pt-2">

        <a href="{{ route('paiements.show', $paiement) }}"
           class="inline-flex items-center justify-center gap-2 px-6 py-3
                  rounded-lg border border-gray-300 bg-white text-gray-700
                  font-semibold hover:bg-gray-50 transition">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>

            Annuler
        </a>


        <button
            type="submit"
            class="inline-flex items-center justify-center gap-2 px-7 py-3
                   rounded-lg text-white font-semibold shadow-sm
                   hover:opacity-95 transition"
            style="background-color: #1e3a8a;">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-5 h-5"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="2">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M5 13l4 4L19 7"/>
            </svg>

            Enregistrer les modifications
        </button>

    </div>

</form>


</div>

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    const beneficiaireType = document.getElementById('beneficiaire_type');

    const studentWrapper = document.getElementById('student-wrapper');
    const teacherWrapper = document.getElementById('teacher-wrapper');

    const studentSelect = document.getElementById('student_id');
    const teacherSelect = document.getElementById('teacher_id');

    const typePaiement = document.getElementById('type_paiement');

    const moisWrapper = document.getElementById('mois-wrapper');
    const anneeWrapper = document.getElementById('annee-wrapper');

    const moisSelect = document.getElementById('mois');
    const anneeInput = document.getElementById('annee');

    const montantInput = document.getElementById('montant');
    const montantResume = document.getElementById('montant-resume');


    function updateBeneficiaire() {

        const type = beneficiaireType.value;

        if (type === 'teacher') {

            studentWrapper.classList.add('hidden');
            teacherWrapper.classList.remove('hidden');

            studentSelect.removeAttribute('required');
            teacherSelect.setAttribute('required', 'required');

        } else {

            teacherWrapper.classList.add('hidden');
            studentWrapper.classList.remove('hidden');

            teacherSelect.removeAttribute('required');
            studentSelect.setAttribute('required', 'required');
        }
    }


    function updateEcolageFields() {

        const isEcolage = typePaiement.value === 'Écolage mensuel';

        if (isEcolage) {

            moisWrapper.classList.remove('hidden');
            anneeWrapper.classList.remove('hidden');

            moisSelect.setAttribute('required', 'required');
            anneeInput.setAttribute('required', 'required');

        } else {

            moisWrapper.classList.add('hidden');
            anneeWrapper.classList.add('hidden');

            moisSelect.removeAttribute('required');
            anneeInput.removeAttribute('required');

        }
    }


    function updateMontantResume() {

        const value = parseFloat(montantInput.value);

        if (isNaN(value)) {
            montantResume.textContent = '0 Ar';
            return;
        }

        montantResume.textContent =
            new Intl.NumberFormat('fr-FR', {
                maximumFractionDigits: 2
            }).format(value) + ' Ar';
    }


    beneficiaireType.addEventListener('change', updateBeneficiaire);
    typePaiement.addEventListener('change', updateEcolageFields);
    montantInput.addEventListener('input', updateMontantResume);


    updateBeneficiaire();
    updateEcolageFields();
    updateMontantResume();

});
</script>

@endpush

@endsection
