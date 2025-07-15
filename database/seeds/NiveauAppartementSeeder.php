<?php

use App\Niveauappartement;
use Illuminate\Database\Seeder;

class NiveauAppartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          //
          $niveaux = [
            array(
                "designation" => "rez-de-chaussée",
                "nombre" => 0,
            ),
            array(
                "designation" => "1er étage ",
                "nombre" => 1,
            ),
           
            array(
                "designation" => "2e étage ",
                "nombre" => 2,
            ),
            array(
                "designation" => "3e étage ",
                "nombre" => 3,
            ),
            array(
                "designation" => "4e étage ",
                "nombre" => 4,
            ),
            array(
                "designation" => "5e étage ",
                "nombre" => 5,
            ),


        ];

        foreach ($niveaux as $peri) {
            $newperio = Niveauappartement::where('designation', $peri['designation'])->first();
            if (!$newperio) {
                $newperio = new Niveauappartement;
            }
            $newperio->designation = $peri['designation'];
            $newperio->nombre = $peri['nombre'];
            $newperio->save();
        }
    }
}
