<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Establishment;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ParentController extends Controller
{
    /**
     * Établissement actuellement sélectionné.
     */
    private function currentEstablishment(): Establishment
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Accès non autorisé.');
        }

        if ($user->role === UserRole::ADMIN->value) {
            $establishmentId = session('establishment_id');

            if (!$establishmentId) {
                abort(403, 'Aucun établissement sélectionné.');
            }

            $establishment = $user->establishments()
                ->whereKey($establishmentId)
                ->first();

            if (!$establishment) {
                abort(403, 'Vous n’avez pas accès à cet établissement.');
            }

            return $establishment;
        }

        $establishmentId = session('establishment_id');

        if (!$establishmentId) {
            abort(403, 'Aucun établissement sélectionné.');
        }

        $establishment = $user->assignedEstablishments()
            ->whereKey($establishmentId)
            ->first();

        if (!$establishment) {
            abort(403, 'Vous n’avez pas accès à cet établissement.');
        }

        return $establishment;
    }

    /**
     * Liste des parents de l'établissement courant.
     */
    public function index(): View
    {
        $establishment = $this->currentEstablishment();

        $parents = ParentModel::query()
            ->with([
                'user',
                'students',
            ])
            ->where('establishment_id', $establishment->id)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->paginate(20);

        return view('parents.index', compact(
            'establishment',
            'parents'
        ));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        $establishment = $this->currentEstablishment();

        // Ne proposer que les élèves sans parent dans cet établissement.
        $students = Student::query()
            ->where('establishment_id', $establishment->id)
            ->whereDoesntHave('parents', function ($query) use ($establishment) {
                $query->where(
                    'establishment_id',
                    $establishment->id
                );
            })
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        return view('parents.create', compact(
            'establishment',
            'students'
        ));
    }

    /**
     * Enregistrer un parent et son compte utilisateur.
     */
    public function store(Request $request): RedirectResponse
    {
        $establishment = $this->currentEstablishment();

        $validated = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:255',
            ],
            'prenom' => [
                'nullable',
                'string',
                'max:255',
            ],
            'telephone' => [
                'nullable',
                'string',
                'max:30',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'adresse' => [
                'nullable',
                'string',
                'max:255',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'student_ids' => [
                'nullable',
                'array',
            ],
            'student_ids.*' => [
                'integer',
                Rule::exists('students', 'id')
                    ->where(
                        fn ($query) => $query->where(
                            'establishment_id',
                            $establishment->id
                        )
                    ),
            ],
        ]);

        if (
            User::where('email', $validated['email'])->exists()
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Cette adresse e-mail est déjà utilisée.',
                ]);
        }

        $studentIds = collect($validated['student_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        DB::transaction(function () use (
            $validated,
            $establishment,
            $studentIds
        ): void {
            // Refuser les élèves déjà rattachés à un parent du même établissement.
            if ($studentIds !== []) {
                $alreadyAssignedIds = Student::query()
                    ->where('establishment_id', $establishment->id)
                    ->whereIn('id', $studentIds)
                    ->whereHas('parents', function ($query) use ($establishment) {
                        $query->where(
                            'establishment_id',
                            $establishment->id
                        );
                    })
                    ->pluck('id');

                if ($alreadyAssignedIds->isNotEmpty()) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'student_ids' => 'Un ou plusieurs élèves sont déjà rattachés à un autre parent. Actualisez le formulaire et réessayez.',
                    ]);
                }
            }

            $user = User::create([
                'name' => trim(
                    ($validated['prenom'] ?? '')
                    . ' '
                    . $validated['nom']
                ),
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => UserRole::PARENT->value,
            ]);

            $user->assignedEstablishments()
                ->sync([$establishment->id]);

            $parent = ParentModel::create([
                'user_id' => $user->id,
                'establishment_id' => $establishment->id,
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'] ?? null,
                'telephone' => $validated['telephone'] ?? null,
                'email' => $validated['email'],
                'adresse' => $validated['adresse'] ?? null,
            ]);

            if ($studentIds !== []) {
                $parent->students()->sync($studentIds);
            }
        });

        return redirect()
            ->route('parents.index')
            ->with(
                'success',
                'Compte parent créé avec succès.'
            );
    }

    /**
     * Afficher un parent.
     */
    public function show(ParentModel $parent): View
    {
        $establishment = $this->currentEstablishment();

        $this->authorizeParent($parent, $establishment);

        $parent->load([
            'user',
            'students.inscriptions',
        ]);

        return view('parents.show', compact(
            'establishment',
            'parent'
        ));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(ParentModel $parent): View
    {
        $establishment = $this->currentEstablishment();

        $this->authorizeParent($parent, $establishment);

        $students = Student::query()
            ->where('establishment_id', $establishment->id)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        $parent->load('students');

        return view('parents.edit', compact(
            'establishment',
            'parent',
            'students'
        ));
    }

    /**
     * Mettre à jour un parent.
     */
    public function update(
        Request $request,
        ParentModel $parent
    ): RedirectResponse {
        $establishment = $this->currentEstablishment();

        $this->authorizeParent($parent, $establishment);

        $validated = $request->validate([
            'nom' => [
                'required',
                'string',
                'max:255',
            ],
            'prenom' => [
                'nullable',
                'string',
                'max:255',
            ],
            'telephone' => [
                'nullable',
                'string',
                'max:30',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($parent->user_id),
            ],
            'adresse' => [
                'nullable',
                'string',
                'max:255',
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
            'student_ids' => [
                'nullable',
                'array',
            ],
            'student_ids.*' => [
                'integer',
                Rule::exists('students', 'id')
                    ->where(
                        fn ($query) => $query->where(
                            'establishment_id',
                            $establishment->id
                        )
                    ),
            ],
        ]);

        $studentIds = collect($validated['student_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        DB::transaction(function () use (
            $validated,
            $parent,
            $establishment,
            $studentIds
        ): void {
            $parent->update([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'] ?? null,
                'telephone' => $validated['telephone'] ?? null,
                'email' => $validated['email'],
                'adresse' => $validated['adresse'] ?? null,
            ]);

            if ($parent->user) {
                $userData = [
                    'name' => trim(
                        ($validated['prenom'] ?? '')
                        . ' '
                        . $validated['nom']
                    ),
                    'email' => $validated['email'],
                    'role' => UserRole::PARENT->value,
                ];

                if (!empty($validated['password'])) {
                    $userData['password'] = Hash::make(
                        $validated['password']
                    );
                }

                $parent->user->update($userData);

                $parent->user->assignedEstablishments()
                    ->sync([$establishment->id]);
            }

            $parent->students()->sync($studentIds);
        });

        return redirect()
            ->route('parents.index')
            ->with(
                'success',
                'Parent mis à jour avec succès.'
            );
    }

    /**
     * Supprimer un parent.
     */
    public function destroy(
        ParentModel $parent
    ): RedirectResponse {
        $establishment = $this->currentEstablishment();

        $this->authorizeParent($parent, $establishment);

        DB::transaction(function () use ($parent): void {
            $user = $parent->user;

            $parent->students()->detach();

            $parent->delete();

            if ($user) {
                $user->delete();
            }
        });

        return redirect()
            ->route('parents.index')
            ->with(
                'success',
                'Parent supprimé avec succès.'
            );
    }

    /**
     * Vérifier qu'un parent appartient bien à l'établissement courant.
     */
    private function authorizeParent(
        ParentModel $parent,
        Establishment $establishment
    ): void {
        if (
            (int) $parent->establishment_id
            !== (int) $establishment->id
        ) {
            abort(
                403,
                'Ce parent n’appartient pas à l’établissement sélectionné.'
            );
        }
    }
}
