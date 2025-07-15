<?php

use App\Rapportintervention;
use Illuminate\Database\Seeder;

class RapportinterventionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rapportinterventions = [
            array(
                "prenom" => "Thierno Fall",
                "compagnietechnicien" => "Odca",
                "debut" => "2021/12/10",
                "fin" => "2021/12/11",
                "duree" => "1 jour",
                "observations" => "operation reussi",
                "etat" => "1",
                "recommandations" => "eviter les coupures",

            ) ];

        foreach ($rapportinterventions as $rapportintervention)
        {
            $newrapportintervention = new Rapportintervention();
            $newrapportintervention->prenom = $rapportintervention['prenom'];
            $newrapportintervention->compagnietechnicien = $rapportintervention['compagnietechnicien'];
            $newrapportintervention->debut = $rapportintervention['debut'];
            $newrapportintervention->fin = $rapportintervention['fin'];
            $newrapportintervention->duree = $rapportintervention['duree'];
            $newrapportintervention->observations = $rapportintervention['observations'];
            $newrapportintervention->etat = $rapportintervention['etat'];
            $newrapportintervention->recommandations = $rapportintervention['recommandations'];

            $newrapportintervention->save();
        }
    }
}
