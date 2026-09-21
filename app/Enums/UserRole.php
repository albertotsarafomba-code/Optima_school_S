<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case ENSEIGNANT = 'enseignant';
    case ELEVE = 'eleve';
    case PARENT = 'parent';
    case SECRETARIAT = 'secretariat';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrateur',
            self::ENSEIGNANT => 'Enseignant',
            self::ELEVE => 'Élève',
            self::PARENT => 'Parent',
            self::SECRETARIAT => 'Secrétariat / Comptabilité',
        };
    }
}