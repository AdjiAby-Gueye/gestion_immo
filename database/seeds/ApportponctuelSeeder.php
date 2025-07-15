<?php

namespace Database\Seeders;

use App\Apportponctuel;
use Illuminate\Database\Seeder;

class ApportponctuelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $apportponctuels = [
            array(
                "montant" => 5000000,
                "date" => now(),
                "contrat_id" => 98,
                "observations" => "lorem ipsum...",
                "typeapportponctuel_id" => "1",

            ) ];

        foreach ($apportponctuels as $apportponctuel)
        {
            $newapportponctuel = new Apportponctuel();
            $newapportponctuel->montant = $apportponctuel['montant'];
            $newapportponctuel->date = $apportponctuel['date'];
            $newapportponctuel->contrat_id = $apportponctuel['contrat_id'];
            $newapportponctuel->observations = $apportponctuel['observations'];
            $newapportponctuel->typeapportponctuel_id = $apportponctuel['typeapportponctuel_id'];
            $newapportponctuel->save();
        }
    }
}
