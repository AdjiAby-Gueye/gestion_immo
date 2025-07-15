<?php

use App\Typepiece;
use Illuminate\Database\Seeder;

class TypepieceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typepieces = [
            array(
                "designation" => "Chambre",
                "iscommun" => "0",

            ),
            array(
                "designation" => "Salon",
                "iscommun" => "0",

            ),
            array(
                "designation" => "Chambre salle de bain",
                "iscommun" => "0",

            ),
            array(
                "designation" => "Piscine",
                "iscommun" => "1",

            ),
            array(
                "designation" => "Salle de gym",
                "iscommun" => "1",

            ),
            array(
                "designation" => "Escalier",
                "iscommun" => "1",

            ),
            array(
                "designation" => "Jardin",
                "iscommun" => "1",

            ),
            array(
                "designation" => "Espace familliale",
                "iscommun" => "0",

            ),
            array(
                "designation" => "Cuisine",
                "iscommun" => "0",

            ),
            array(
                "designation" => "Douche externe",
                "iscommun" => "0",

            ),
            array(
                "designation" => "Parking interne",
                "iscommun" => "1",

            ),
            array(
                "designation" => "Parking externe",
                "iscommun" => "1",

            ),
            array(
                "designation" => "Salle de fete",
                "iscommun" => "1",

            ),
            array(
                "designation" => "Couloire appartement",
                "iscommun" => "0",

            ),
            array(
                "designation" => "Ascenceur",
                "iscommun" => "1",

            ),
            array(
                "designation" => "Groupe électrogène",
                "iscommun" => "1",

            ),

        ];

        foreach ($typepieces as $typepiece)
        {
            $newtypepiece = new Typepiece();
            $newtypepiece->designation = $typepiece['designation'];
            $newtypepiece->iscommun = $typepiece['iscommun'];
            $newtypepiece->save();
        }
    }
}
