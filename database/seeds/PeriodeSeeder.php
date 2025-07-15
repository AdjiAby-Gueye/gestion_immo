<?php

use App\Periode;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $currentYear = Carbon::now()->year;

        // $periodes = [
        //     array(
        //         "designation" => "Janvier",
        //         "description" => "Janvier",
        //     ),
        //     array(
        //         "designation" => "Février",
        //         "description" => "Février",
        //     ),
        //     array(
        //         "designation" => "Mars",
        //         "description" => "Mars",
        //     ),
        //     array(
        //         "designation" => "Avril",
        //         "description" => "Avril",
        //     ),
        //     array(
        //         "designation" => "Mai",
        //         "description" => "Mai",
        //     ),
        //     array(
        //         "designation" => "Juin",
        //         "description" => "Juin",
        //     ),
        //     array(
        //         "designation" => "Juillet",
        //         "description" => "Juillet",
        //     ),
        //     array(
        //         "designation" => "Août",
        //         "description" => "Août",
        //     ),
        //     array(
        //         "designation" => "Septembre",
        //         "description" => "Septembre",
        //     ),
        //     array(
        //         "designation" => "Octobre",
        //         "description" => "Octobre",
        //     ),
        //     array(
        //         "designation" => "Novembre",
        //         "description" => "Novembre",
        //     ),
        //     array(
        //         "designation" => "Décembre",
        //         "description" => "Décembre",
        //     ),
        // ];


        // foreach ($periodes as $peri) {
        //     // Chercher une période existante avec la désignation et l'année actuelle
        //     $newperio = Periode::where('designation', $peri['designation'])->first();
        //     if (!$newperio) {
        //         $newperio = new Periode;
        //     }
        //     $newperio->designation = $peri['designation'] . ' ' . $currentYear;
        //     $newperio->description = $peri['description'];
        //     $newperio->annee = $currentYear;
        //     $newperio->save();
        // }

        $currentYear = Carbon::now()->year;  
        $nextYear = $currentYear + 1;        

        $months = [
            "Janvier",
            "Février",
            "Mars",
            "Avril",
            "Mai",
            "Juin",
            "Juillet",
            "Août",
            "Septembre",
            "Octobre",
            "Novembre",
            "Décembre"
        ];

        $periodes = [];

        foreach ([$currentYear, $nextYear] as $year) {
            foreach ($months as $month) {
                $periodes[] = [
                    "designation" => $month . ' ' . $year,
                    "description" => $month . ' ' . $year,
                    "annee" => $year
                ];
            }
        }

        // Insertion des périodes dans la base de données
        foreach ($periodes as $peri) {
            $newperio = Periode::where('designation', $peri['designation'])->first();
            if (!$newperio) {
                $newperio = new Periode;
            }
            $newperio->designation = $peri['designation'];
            $newperio->description = $peri['description'];
            $newperio->annee = $peri['annee'];
            $newperio->save();
        }
    }
}
