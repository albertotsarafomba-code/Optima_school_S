<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SuperAdminConfigurationController extends Controller
{
    public function index(): View
    {
        $settings = DB::table('platform_settings')
            ->pluck('value', 'key')
            ->all();

        return view('super-admin.configuration', [
            'admin' => auth()->user(),
            'settings' => $settings,
        ]);
    }

    public function updatePlatform(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'platform_name' => ['required', 'string', 'max:120'],
            'platform_logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        DB::transaction(function () use ($request, $validated) {
            $this->saveSetting(
                'platform_name',
                $validated['platform_name']
            );

            if ($request->hasFile('platform_logo')) {
                $path = $request->file('platform_logo')
                    ->store('platform', 'public');

                $this->saveSetting('platform_logo', $path);
            }
        });

        return redirect()
            ->route('super-admin.configuration.index')
            ->with('success', 'Les paramètres de la plateforme ont été enregistrés.');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $admin = $request->user();

        abort_unless($admin->role === 'super_admin', 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($admin->id),
            ],
            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        if ($request->hasFile('profile_photo')) {
            $admin->profile_photo_path = $request->file('profile_photo')
                ->store('profile-photos', 'public');
        }

        $admin->save();

        return redirect()
            ->route('super-admin.configuration.index')
            ->with('success', 'Votre profil a été mis à jour.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],
            'password' => [
                'required',
                'string',
                'min:12',
                'confirmed',
                'different:current_password',
            ],
        ], [
            'current_password.current_password' =>
                'Le mot de passe actuel est incorrect.',
            'password.min' =>
                'Le nouveau mot de passe doit contenir au moins 12 caractères.',
            'password.confirmed' =>
                'La confirmation du mot de passe ne correspond pas.',
            'password.different' =>
                'Le nouveau mot de passe doit être différent de l’ancien.',
        ]);

        $admin = $request->user();

        abort_unless($admin->role === 'super_admin', 403);

        $admin->password = $validated['password'];
        $admin->save();

        return redirect()
            ->route('super-admin.configuration.index')
            ->with('success', 'Votre mot de passe a été modifié.');
    }

    private function saveSetting(string $key, ?string $value): void
    {
        DB::table('platform_settings')->updateOrInsert(
            ['key' => $key],
            [
                'value' => $value,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
