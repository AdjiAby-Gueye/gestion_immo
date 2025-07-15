<?php

use App\Fonction;
use Illuminate\Database\Seeder;

class FonctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fonctions = [
            array(
                "designation" => "Comptable",
            ),
            array(
                "designation" => "Courtier",
            ),
            array(
                "designation" => "Technicien",
            ) ];

        foreach ($fonctions as $fonction)
        {
            $newfonction = new Fonction();
            $newfonction->designation = $fonction['designation'];
            $newfonction->save();
        }
    }
}
