<?php

use App\Periodicite;
use Illuminate\Database\Seeder;

class PeriodiciteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $periodicites = [
            array(
                "designation" => "Mensuelle",
                "description" => "Mensuelle",
                "nbr_mois" => 1
            ),
            array(
                "designation" => "Trimestrielle",
                "description" => "Trimestrielle",
                "nbr_mois" => 3
            ),
            array(
                "designation" => "Bimensuelle",
                "description" => "Bimensuelle",
                "nbr_mois" => 2
            )
        ];

        foreach ($periodicites as $perio)
        {
            $newperio = Periodicite::where('designation', $perio['designation'])->first();
            if (!$newperio) {
                $newperio = new Periodicite();
            }
           
            $newperio->designation = $perio['designation'];
            $newperio->description = $perio['description'];
            $newperio->nbr_mois = $perio['nbr_mois'];
            $newperio->save();
        }
    }
}
