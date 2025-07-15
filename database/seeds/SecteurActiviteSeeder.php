<?php

use App\Modepaiement;
use App\Secteuractivite;
use Illuminate\Database\Seeder;

class SecteurActiviteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $periodes = [
            array(
                "designation" => "Technologie de l'information",
                "description" => "développement de logiciels, conception de sites web, services cloud, etc.",
            ),
            array(
                "designation" => "Santé",
                "description" => "soins de santé, recherche médicale, industrie pharmaceutique, dispositifs médicaux, etc.",
            ),
            array(
                "designation" => "Éducation",
                "description" => "écoles, universités, etc.",
            ),
            array(
                "designation" => "Finance",
                "description" => "banques, institutions financières, fintech, investissements, etc.",
            ),
            array(
                "designation" => "Immobilier",
                "description" => " achat, vente, location, gestion de biens immobiliers, construction, etc",
            ),
            array(
                "designation" => "Industrie",
                "description" => "fabrication, production, logistique, chaîne d'approvisionnement, etc",
            ),
            array(
                "designation" => "Marketing et publicité",
                "description" => "marketing numérique, agences de publicité, relations publiques, etc.",
            ),
            array(
                "designation" => "Gouvernement et administration publique",
                "description" => "organismes gouvernementaux, administration municipale, etc.",
            ),


        ];

        foreach ($periodes as $peri) {
            $newperio = Secteuractivite::where('designation', $peri['designation'])->first();
            if (!$newperio) {
                $newperio = new Secteuractivite;
            }
            $newperio->designation = $peri['designation'];
            $newperio->description = $peri['description'];
            $newperio->save();
        }
    }
}

