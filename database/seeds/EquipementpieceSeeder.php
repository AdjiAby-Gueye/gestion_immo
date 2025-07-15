<?php

use App\Equipementpiece;
use Illuminate\Database\Seeder;

class EquipementpieceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $equipementpieces = [
            array(
                "designation" => "clef",
                "generale" => 0,
            ),
            array(
                "designation" => "chaise anglaise",
                "generale" => 0,
            ),
            array(
                "designation" => "Table",
                "generale" => 0,
            ),
            array(
                "designation" => "climatiseur",
                "generale" => 1,
            ),
            array(
                "designation" => "Lampe principale",
                "generale" => 1,
            ),
            array(
                "designation" => "Lit",
                "generale" => 0,
            ),
            array(
                "designation" => "Television",
                "generale" => 0,
            )

            ];


        foreach ($equipementpieces as $equipementpiece)
        {
            $newequipementpiece = new Equipementpiece();
            $newequipementpiece->designation = $equipementpiece['designation'];
            $newequipementpiece->generale = $equipementpiece['generale'];
            $newequipementpiece->save();
        }
    }
}
