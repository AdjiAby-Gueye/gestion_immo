<?php

use App\Prestataire;
use Illuminate\Database\Seeder;

class PrestataireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prestataires = [
            array(
                "nom" => "Sen'eau",
                "adresse" => "Mariste",
                "email" => "seneau@gmail.com",
                "telephone1" => "784523492",
                "telephone2" => "784523492",

            ),
            array(
                "nom" => "SenPaic",
                "adresse" => "Yoff",
                "email" => "sen@gmail.com",
                "telephone1" => "784523492",
                "telephone2" => "784523492",

            )];

        foreach ($prestataires as $prestataire)
        {
            $newprestataire = new Prestataire();
            $newprestataire->nom = $prestataire['nom'];
            $newprestataire->adresse = $prestataire['adresse'];
            $newprestataire->email = $prestataire['email'];
            $newprestataire->telephone1 = $prestataire['telephone1'];
            $newprestataire->telephone2 = $prestataire['telephone2'];
            $newprestataire->save();
        }
    }
}
