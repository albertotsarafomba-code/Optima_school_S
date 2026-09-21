<?php

namespace App\Concerns;

trait PasswordValidationRules
{
    /**
     * Règles de validation du mot de passe.
     *
     * Le mot de passe doit contenir au minimum 8 caractères,
     * avec au moins :
     * - une lettre majuscule
     * - une lettre minuscule
     * - un chiffre
     * - un symbole
     * - une confirmation identique
     */
    protected function passwordRules(): array
    {
        return [
            'required',
            'string',
            'min:8',
            'regex:/[A-Z]/',
            'regex:/[a-z]/',
            'regex:/[0-9]/',
            'regex:/[^A-Za-z0-9]/',
            'confirmed',
        ];
    }

    /**
     * Règles de validation du mot de passe actuel.
     */
    protected function currentPasswordRules(): array
    {
        return [
            'required',
            'string',
            'current_password',
        ];
    }
}