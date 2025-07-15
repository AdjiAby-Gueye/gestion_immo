<?php

use App\Categorieprestataire;
use App\Categorieprestation;
use Illuminate\Database\Seeder;

class CategorieprestataireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorieprestataires = [
            array(
                "designation" => "Nettoiement",
            ),
            array(
                "designation" => "Electricite",
            ),
            array(
                "designation" => "Eau",
            ),
            array(
                "designation" => "Sécurité",
            ),
            array(
                "designation" => "Assurance",
            )
        ];

        foreach ($categorieprestataires as $categorieprestataire)
        {
            $newcategorieprestataire = new Categorieprestataire();
            $newcategorieprestataire->designation = $categorieprestataire['designation'];
            $newcategorieprestataire->save();
        }
    }
}
