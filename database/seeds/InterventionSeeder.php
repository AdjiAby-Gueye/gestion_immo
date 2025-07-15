<?php

use App\Intervention;
use Illuminate\Database\Seeder;

class InterventionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $interventions = [
            array(
                "partiecommune" => "Jardin",
                "etat" => "Gazon defectueux",
            ),
            array(
                "partiecommune" => "Terrain sport",
                "etat" => "poteaux brisée",
            )];

        foreach ($interventions as $intervention)
        {
            $newintervention = new Intervention();
            $newintervention->partiecommune = $intervention['partiecommune'];
            $newintervention->etat = $intervention['etat'];
            $newintervention->save();
        }
    }
}
