<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Compte admin
        User::firstOrCreate(
            ['email' => 'admin@bara-deco.sn'],
            [
                'name'     => 'BARA POUYE',
                'password' => Hash::make('BaraDeco2025!'),
                'is_admin' => true,
            ]
        );
        // Services (données réelles du site)
        $services = [
            [
                'title'       => 'Peinture Intérieure',
                'description' => 'Revitalisation complète de vos intérieurs avec des finitions impeccables. Choix de couleurs modernes et conseils en décoration.',
                'icon'        => 'fa-paint-roller',
                'items'       => ['Appartements & Maisons', 'Bureaux & Commerces', 'Travaux de rénovation'],
                'sort_order'  => 1,
            ],
            [
                'title'       => 'Peinture Extérieure',
                'description' => 'Protection et embellissement durable de vos façades. Préparation soignée des surfaces et peintures adaptées aux intempéries.',
                'icon'        => 'fa-home',
                'items'       => ['Façades & Murs extérieurs', 'Portails & Clôtures', 'Terrasses & Balcons'],
                'sort_order'  => 2,
            ],
            [
                'title'       => 'Faux Plafonds',
                'description' => 'Conception et pose de faux plafonds en plâtre avec ou sans éclairage intégré. Designs modernes pour un rendu élégant.',
                'icon'        => 'fa-border-all',
                'items'       => ['Plafonds simples', 'Plafonds décoratifs', 'Éclairage intégré LED'],
                'sort_order'  => 3,
            ],
            [
                'title'       => 'Décoration Intérieure',
                'description' => 'Harmonisation complète de vos espaces. Conseils en agencement, choix des matériaux et création d\'ambiances uniques.',
                'icon'        => 'fa-palette',
                'items'       => ['Conseil couleurs', 'Agencement d\'espace', 'Finitions décoratives'],
                'sort_order'  => 4,
            ],
        ];

        foreach ($services as $s) {
            Service::firstOrCreate($s);
        }

        // Note: les projets (portfolio) sont ajoutés via l'interface admin
        // en uploadant les vraies photos depuis images/

        // Projets de démonstration
        $projects = [
            [
                'title'       => 'Rénovation complète appartement',
                'description' => 'Peinture et décoration d\'un 3 pièces à Dakar – finitions impeccables.',
                'category'    => 'interieur',
                'image'       => '/images/peinture-interieur.jpeg',
                'is_active'   => true,
                'sort_order'  => 1,
            ],
            [
                'title'       => 'Décoration salon moderne',
                'description' => 'Harmonisation des couleurs et agencement d\'un salon contemporain.',
                'category'    => 'interieur',
                'image'       => '/images/decoration-interieur.jpeg',
                'is_active'   => true,
                'sort_order'  => 2,
            ],
            [
                'title'       => 'Plafond décoratif avec éclairage LED',
                'description' => 'Design moderne avec intégration d\'éclairage LED pour salon contemporain.',
                'category'    => 'plafond',
                'image'       => '/images/faux-plafond.jpeg',
                'is_active'   => true,
                'sort_order'  => 3,
            ],
            [
                'title'       => 'Rénovation façade villa',
                'description' => 'Protection et embellissement durable d\'une façade à Dakar.',
                'category'    => 'exterieur',
                'image'       => '/images/peinture-exterieur.jpeg',
                'is_active'   => true,
                'sort_order'  => 4,
            ],
            [
                'title'       => 'Stand Optiontown – Salon professionnel',
                'description' => 'Habillage complet et finitions premium pour stand d\'exposition.',
                'category'    => 'commercial',
                'image'       => '/images/optiontown-stand.jpeg',
                'is_active'   => true,
                'sort_order'  => 5,
            ],
            [
                'title'       => 'Aménagement bureau Gainde',
                'description' => 'Décoration et peinture d\'un espace professionnel moderne.',
                'category'    => 'commercial',
                'image'       => '/images/gainde.jpeg',
                'is_active'   => true,
                'sort_order'  => 6,
            ],
        ];

        foreach ($projects as $p) {
            Project::firstOrCreate($p);
        }
    }

}
