<?php

use App\Activite;
use App\Annonce;
use App\Assurance;
use App\Appartement;
use App\Contratproprietaire;
use App\Modelcontrat;
use App\Outil;
use App\Puhtva;
use App\Quantite;
use App\Soustypeintervention;
use App\Taxe;
use App\Unite;
use Illuminate\Database\Seeder;

class InitialisationBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Seeder taxe

        $taxes = [
            array(
                "designation" => "TVA",
                "description" => "TVA",
                "valeur" => 18
            ),
            array(
                "designation" => "BRS",
                "description" => "BRS",
                "valeur" => 5
            ),
            array(
                "designation" => "TLV",
                "description" => "TLV",
                "valeur" => 2
            )
        ];

        foreach ($taxes as $taxe)
        {
            $newtaxe = Taxe::where('designation', Outil::getOperateurLikeDB(), '%'. $taxe['designation'] . '%')->first();
            if(!isset($newtaxe)){
                $newtaxe = new Taxe();
            }

            $newtaxe->designation = $taxe['designation'];
            $newtaxe->description = $taxe['description'];
            $newtaxe->valeur = $taxe['valeur'];
            $newtaxe->save();
        }


        //Seeder Activite

        $activites = [
            array(
                "designation" => "Promoteur",
                "description" => "Promoteur",
            ),
            array(
                "designation" => "Agence",
                "description" => "Agence",
            ),
            array(
                "designation" => "Mixte",
                "description" => "Mixte",
            )
        ];

        foreach ($activites as $activite)
        {
            $newactivite = Activite::where('designation', Outil::getOperateurLikeDB(), '%'. $activite['designation'] . '%')->first();
            if(!isset($newactivite)){
                $newactivite = new Activite();
            }

            $newactivite->designation = $activite['designation'];
            $newactivite->description = $activite['description'];
            $newactivite->save();
        }


        //Seeder Model de contrat

        $Modelcontrats = [
            array(
                "designation" => "Commercial",
                "description" => "Commercial",
            ),
            array(
                "designation" => "Habitation",
                "description" => "Habitation",
            ),
            array(
                "designation" => "Mixte",
                "description" => "Mixte",
            )
        ];

        foreach ($Modelcontrats as $Modelcontrat)
        {
            $newModelcontrat = Modelcontrat::where('designation', Outil::getOperateurLikeDB(), '%'. $Modelcontrat['designation'] . '%')->first();
            if(!isset($newModelcontrat)){
                $newModelcontrat = new Modelcontrat();
            }

            $newModelcontrat->designation = $Modelcontrat['designation'];
            $newModelcontrat->description = $Modelcontrat['description'];
            $newModelcontrat->save();
        }


        

    }
}
