<?php

namespace App\Livewire\Settings;

use App\Concerns\ProfileValidationRules;
use Flux\Flux;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Profile settings')]
class Profile extends Component
{
    use ProfileValidationRules;
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public $photo = null;

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Met à jour le nom et l'adresse e-mail.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate(
            $this->profileRules($user->id)
        );

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        Flux::toast(
            variant: 'success',
            text: __('Profile updated.')
        );
    }

    /**
     * Enregistre ou remplace la photo du compte connecté.
     */
    public function updateProfilePhoto(): void
    {
        $this->validate([
            'photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $user = Auth::user();

        $newPath = $this->photo->store(
            'profile-photos',
            'public'
        );

        if (! $newPath) {
            $this->addError(
                'photo',
                'Impossible d’enregistrer la photo. Réessayez.'
            );

            return;
        }

        $oldPath = $user->profile_photo_path;

        $user->profile_photo_path = $newPath;
        $user->save();

        if (
            $oldPath &&
            $oldPath !== $newPath
        ) {
            Storage::disk('public')->delete($oldPath);
        }

        $this->photo = null;

        Flux::toast(
            variant: 'success',
            text: 'Photo de profil mise à jour.'
        );
    }

    /**
     * Supprime uniquement la photo du compte connecté.
     */
    public function deleteProfilePhoto(): void
    {
        $user = Auth::user();
        $oldPath = $user->profile_photo_path;

        $user->profile_photo_path = null;
        $user->save();

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        $this->photo = null;

        Flux::toast(
            variant: 'success',
            text: 'Photo de profil supprimée.'
        );
    }

    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(
                default: route('dashboard', absolute: false)
            );

            return;
        }

        $user->sendEmailVerificationNotification();

        Flux::toast(
            text: __(
                'A new verification link has been sent to your email address.'
            )
        );
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        $user = Auth::user();

        return $user instanceof MustVerifyEmail
            && ! $user->hasVerifiedEmail();
    }

    #[Computed]
    public function showDeleteUser(): bool
    {
        $user = Auth::user();

        return ! $user instanceof MustVerifyEmail
            || $user->hasVerifiedEmail();
    }
}
