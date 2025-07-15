<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TypeapportponctuelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typeapportponctuels = [
            array(
                "designation" => "Paiement complet",
                "description" => "Pour des apports qui permettent de payer entierement la somme due sur une échéance.",


            ) ];

        foreach ($typeapportponctuels as $typeapportponctuel)
        {
            $newtypeapportponctuel = new $typeapportponctuels();
            $newtypeapportponctuel->designation = $typeapportponctuels['designation'];
            $newtypeapportponctuel->description = $typeapportponctuels['description'];
            $newtypeapportponctuel->save();
        }
    }
}
