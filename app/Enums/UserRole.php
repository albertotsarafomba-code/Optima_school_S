<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = "admin";
    case ENSEIGNANT = "enseignant";
    case PARENT = "parent";
    case SECRETARIAT = "secretariat";
    case COMPTABLE = "comptable";

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => "Administrateur",
            self::ENSEIGNANT => "Enseignant",
            self::PARENT => "Parent",
            self::SECRETARIAT => "Secrétariat",
            self::COMPTABLE => "Comptabilité",
        };
    }
}
