<?php

use App\Categorieprestation;
use Illuminate\Database\Seeder;

class CategorieprestationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorieprestations = [
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

        foreach ($categorieprestations as $categorieprestation)
        {
            $newcategorieprestation = new Categorieprestation();
            $newcategorieprestation->designation = $categorieprestation['designation'];
            $newcategorieprestation->save();
        }
    }
}
