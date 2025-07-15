<?php

use App\Categorieprestataire;
use App\Categorieprestation;
use App\Horaire;
use Illuminate\Database\Seeder;

class HoraireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $horaires = [
            array(
                "designation" => "Toute la journée",
                "debut" => "07:00",
                "fin" => "19:00",
            ),
            array(
                "designation" => "Mis journée",
                "debut" => "08:00",
                "fin" => "14:00",
            )];

        foreach ($horaires as $horaire)
        {
            $newhoraire = new Horaire();
            $newhoraire->designation = $horaire['designation'];
            $newhoraire->debut = $horaire['debut'];
            $newhoraire->fin = $horaire['fin'];
            $newhoraire->save();
        }
    }
}
