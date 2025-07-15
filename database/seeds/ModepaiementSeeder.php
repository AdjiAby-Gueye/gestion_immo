<?php

use App\Modepaiement;
use Illuminate\Database\Seeder;

class ModepaiementSeeder extends Seeder
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
                "designation" => "Espèces",
                "description" => "Especes",
                "code" => "ES",
            ),
            array(
                "designation" => "Chèque",
                "description" => "Cheque",
                "code" => "CH",
            ),
            array(
                "designation" => "Carte bancaire",
                "description" => "Carte bancaire",
                "code" => "CB",
            ),
            array(
                "designation" => "Autres",
                "description" => "Autres",
                "code" => "AUTRES",
            ),


        ];

        foreach ($periodes as $peri) {
            $newperio = Modepaiement::where('designation', $peri['designation'])->first();
            if (!$newperio) {
                $newperio = new Modepaiement;
            }
            $newperio->designation = $peri['designation'];
            $newperio->description = $peri['description'];
            $newperio->code = $peri['code'];
            $newperio->save();
        }
    }
}
