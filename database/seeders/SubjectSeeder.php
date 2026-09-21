<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Catalogue central des matières par type d'établissement.
     */
    public function run(): void
    {
        $now = now();

        $schoolTypes = DB::table('school_types')
            ->pluck('id', 'name');

        /*
        |--------------------------------------------------------------------------
        | TYPES D'ÉTABLISSEMENT OBLIGATOIRES
        |--------------------------------------------------------------------------
        */
        $requiredTypes = [
            'Préscolaire',
            'Primaire',
            'Collège',
            'Lycée',
            'Université',
        ];

        foreach ($requiredTypes as $type) {
            if (!$schoolTypes->has($type)) {
                $this->command->error(
                    "Le type d'établissement « {$type} » est introuvable."
                );

                return;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CATALOGUE DES MATIÈRES
        |--------------------------------------------------------------------------
        */
        $subjects = [

            /*
            |--------------------------------------------------------------------------
            | PRÉSCOLAIRE
            |--------------------------------------------------------------------------
            */
            'Préscolaire' => [
                [
                    'code' => 'PRE-EVEIL',
                    'nom' => 'Éveil et découverte',
                ],
                [
                    'code' => 'PRE-LANG',
                    'nom' => 'Langage et communication',
                ],
                [
                    'code' => 'PRE-PMATH',
                    'nom' => 'Pré-mathématiques',
                ],
                [
                    'code' => 'PRE-GRAPH',
                    'nom' => 'Graphisme',
                ],
                [
                    'code' => 'PRE-MOT',
                    'nom' => 'Motricité',
                ],
                [
                    'code' => 'PRE-ART',
                    'nom' => 'Activités artistiques',
                ],
                [
                    'code' => 'PRE-MUS',
                    'nom' => 'Éducation musicale',
                ],
                [
                    'code' => 'PRE-CIV',
                    'nom' => 'Éducation civique',
                ],
                [
                    'code' => 'PRE-PRAT',
                    'nom' => 'Activités pratiques',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | PRIMAIRE
            |--------------------------------------------------------------------------
            */
            'Primaire' => [
                [
                    'code' => 'PRI-FR',
                    'nom' => 'Français',
                ],
                [
                    'code' => 'PRI-MG',
                    'nom' => 'Malagasy',
                ],
                [
                    'code' => 'PRI-MATH',
                    'nom' => 'Mathématiques',
                ],
                [
                    'code' => 'PRI-ANG',
                    'nom' => 'Anglais',
                ],
                [
                    'code' => 'PRI-SC',
                    'nom' => 'Sciences',
                ],
                [
                    'code' => 'PRI-HG',
                    'nom' => 'Histoire-Géographie',
                ],
                [
                    'code' => 'PRI-EC',
                    'nom' => 'Éducation civique',
                ],
                [
                    'code' => 'PRI-EPS',
                    'nom' => 'Éducation physique et sportive',
                ],
                [
                    'code' => 'PRI-ART',
                    'nom' => 'Arts',
                ],
                [
                    'code' => 'PRI-MUS',
                    'nom' => 'Éducation musicale',
                ],
                [
                    'code' => 'PRI-INF',
                    'nom' => 'Informatique',
                ],
                [
                    'code' => 'PRI-REL',
                    'nom' => 'Éducation religieuse',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | COLLÈGE
            |--------------------------------------------------------------------------
            */
            'Collège' => [
                [
                    'code' => 'COL-FR',
                    'nom' => 'Français',
                ],
                [
                    'code' => 'COL-MG',
                    'nom' => 'Malagasy',
                ],
                [
                    'code' => 'COL-MATH',
                    'nom' => 'Mathématiques',
                ],
                [
                    'code' => 'COL-ANG',
                    'nom' => 'Anglais',
                ],
                [
                    'code' => 'COL-ALL',
                    'nom' => 'Allemand',
                ],
                [
                    'code' => 'COL-ESP',
                    'nom' => 'Espagnol',
                ],
                [
                    'code' => 'COL-PC',
                    'nom' => 'Physique-Chimie',
                ],
                [
                    'code' => 'COL-SVT',
                    'nom' => 'Sciences de la Vie et de la Terre',
                ],
                [
                    'code' => 'COL-HIST',
                    'nom' => 'Histoire',
                ],
                [
                    'code' => 'COL-GEO',
                    'nom' => 'Géographie',
                ],
                [
                    'code' => 'COL-HG',
                    'nom' => 'Histoire-Géographie',
                ],
                [
                    'code' => 'COL-EC',
                    'nom' => 'Éducation civique',
                ],
                [
                    'code' => 'COL-INF',
                    'nom' => 'Informatique',
                ],
                [
                    'code' => 'COL-EPS',
                    'nom' => 'Éducation physique et sportive',
                ],
                [
                    'code' => 'COL-ART',
                    'nom' => 'Arts plastiques',
                ],
                [
                    'code' => 'COL-MUS',
                    'nom' => 'Éducation musicale',
                ],
                [
                    'code' => 'COL-TECH',
                    'nom' => 'Technologie',
                ],
                [
                    'code' => 'COL-REL',
                    'nom' => 'Éducation religieuse',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | LYCÉE
            |--------------------------------------------------------------------------
            */
            'Lycée' => [
                [
                    'code' => 'LYC-FR',
                    'nom' => 'Français',
                ],
                [
                    'code' => 'LYC-MG',
                    'nom' => 'Malagasy',
                ],
                [
                    'code' => 'LYC-MATH',
                    'nom' => 'Mathématiques',
                ],
                [
                    'code' => 'LYC-ANG',
                    'nom' => 'Anglais',
                ],
                [
                    'code' => 'LYC-ALL',
                    'nom' => 'Allemand',
                ],
                [
                    'code' => 'LYC-ESP',
                    'nom' => 'Espagnol',
                ],
                [
                    'code' => 'LYC-PHILO',
                    'nom' => 'Philosophie',
                ],
                [
                    'code' => 'LYC-PC',
                    'nom' => 'Physique-Chimie',
                ],
                [
                    'code' => 'LYC-SVT',
                    'nom' => 'Sciences de la Vie et de la Terre',
                ],
                [
                    'code' => 'LYC-HIST',
                    'nom' => 'Histoire',
                ],
                [
                    'code' => 'LYC-GEO',
                    'nom' => 'Géographie',
                ],
                [
                    'code' => 'LYC-HG',
                    'nom' => 'Histoire-Géographie',
                ],
                [
                    'code' => 'LYC-EC',
                    'nom' => 'Éducation civique',
                ],
                [
                    'code' => 'LYC-INF',
                    'nom' => 'Informatique',
                ],
                [
                    'code' => 'LYC-ECO',
                    'nom' => 'Économie',
                ],
                [
                    'code' => 'LYC-GEST',
                    'nom' => 'Gestion',
                ],
                [
                    'code' => 'LYC-COMPTA',
                    'nom' => 'Comptabilité',
                ],
                [
                    'code' => 'LYC-EPS',
                    'nom' => 'Éducation physique et sportive',
                ],
                [
                    'code' => 'LYC-TECH',
                    'nom' => 'Technologie',
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | UNIVERSITÉ
            |--------------------------------------------------------------------------
            */
            'Université' => [
                [
                    'code' => 'UNI-INFO',
                    'nom' => 'Informatique',
                ],
                [
                    'code' => 'UNI-ALGO',
                    'nom' => 'Algorithmique',
                ],
                [
                    'code' => 'UNI-PROG',
                    'nom' => 'Programmation',
                ],
                [
                    'code' => 'UNI-PHP',
                    'nom' => 'Programmation PHP',
                ],
                [
                    'code' => 'UNI-JAVA',
                    'nom' => 'Programmation Java',
                ],
                [
                    'code' => 'UNI-WEB',
                    'nom' => 'Développement Web',
                ],
                [
                    'code' => 'UNI-BDD',
                    'nom' => 'Base de données',
                ],
                [
                    'code' => 'UNI-SQL',
                    'nom' => 'SQL',
                ],
                [
                    'code' => 'UNI-RESEAUX',
                    'nom' => 'Réseaux informatiques',
                ],
                [
                    'code' => 'UNI-SYS',
                    'nom' => 'Systèmes d’exploitation',
                ],
                [
                    'code' => 'UNI-ARCHI',
                    'nom' => 'Architecture des ordinateurs',
                ],
                [
                    'code' => 'UNI-GENIE',
                    'nom' => 'Génie logiciel',
                ],
                [
                    'code' => 'UNI-POO',
                    'nom' => 'Programmation orientée objet',
                ],
                [
                    'code' => 'UNI-SEC',
                    'nom' => 'Sécurité informatique',
                ],
                [
                    'code' => 'UNI-IA',
                    'nom' => 'Intelligence artificielle',
                ],
                [
                    'code' => 'UNI-ML',
                    'nom' => 'Machine Learning',
                ],
                [
                    'code' => 'UNI-MATH',
                    'nom' => 'Mathématiques',
                ],
                [
                    'code' => 'UNI-STAT',
                    'nom' => 'Statistiques',
                ],
                [
                    'code' => 'UNI-ANALYSE',
                    'nom' => 'Analyse',
                ],
                [
                    'code' => 'UNI-ALGEBRE',
                    'nom' => 'Algèbre',
                ],
                [
                    'code' => 'UNI-ANG',
                    'nom' => 'Anglais',
                ],
                [
                    'code' => 'UNI-FR',
                    'nom' => 'Français',
                ],
                [
                    'code' => 'UNI-GESTION',
                    'nom' => 'Gestion',
                ],
                [
                    'code' => 'UNI-ECO',
                    'nom' => 'Économie',
                ],
                [
                    'code' => 'UNI-COMPTA',
                    'nom' => 'Comptabilité',
                ],
                [
                    'code' => 'UNI-DROIT',
                    'nom' => 'Droit',
                ],
                [
                    'code' => 'UNI-COM',
                    'nom' => 'Communication',
                ],
                [
                    'code' => 'UNI-METHODO',
                    'nom' => 'Méthodologie',
                ],
                [
                    'code' => 'UNI-ENTREPREN',
                    'nom' => 'Entrepreneuriat',
                ],
                [
                    'code' => 'UNI-PROJET',
                    'nom' => 'Gestion de projet',
                ],
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | INSTALLATION / SYNCHRONISATION
        |--------------------------------------------------------------------------
        */
        $total = 0;

        foreach ($subjects as $typeName => $typeSubjects) {

            $schoolTypeId = $schoolTypes[$typeName];

            foreach ($typeSubjects as $subjectData) {

                Subject::updateOrCreate(
                    [
                        'school_type_id' => $schoolTypeId,
                        'code' => $subjectData['code'],
                    ],
                    [
                        'nom' => $subjectData['nom'],
                        'description' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );

                $total++;
            }
        }

        $this->command->info(
            "Catalogue des matières installé : {$total} matières."
        );
    }
}
