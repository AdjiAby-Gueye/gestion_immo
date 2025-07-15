<?php

use App\Typelocataire;
use Illuminate\Database\Seeder;

class TypelocataireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typelocataires = [
            array(
                "designation" => "Physique",

            ),
            array(
                "designation" => "Morale",

            ) ];

        foreach ($typelocataires as $typelocataire)
        {
            $newtypelocataire = new Typelocataire();
            $newtypelocataire->designation = $typelocataire['designation'];
            $newtypelocataire->save();
        }
    }
}
