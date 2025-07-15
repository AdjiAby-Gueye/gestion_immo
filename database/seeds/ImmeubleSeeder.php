<?php

use App\Immeuble;
use Illuminate\Database\Seeder;

class ImmeubleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $immeubles = [
            array(
                "nom" => "Immeuble 1",
                "adresse" => "SACRE COEUR",
            ),
            array(
                "nom" => "Immeuble 2",
                "adresse" => "MEDINA",
            )];

        foreach ($immeubles as $immeuble)
        {
            $newimmeuble = new Immeuble();
            $newimmeuble->nom = $immeuble['nom'];
            $newimmeuble->adresse = $immeuble['adresse'];
            $newimmeuble->save();
            
        }
    }
}
