<?php

use App\Assureur;
use Illuminate\Database\Seeder;

class AssureurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $assureurs = [
            array(
                "designation" => "AXA ASSURANCE",
            ),
            array(
                "designation" => "Sunu Assurance",
            )];

        foreach ($assureurs as $assureur)
        {
            $newassureur = new Assureur();
            $newassureur->designation = $assureur['designation'];
            $newassureur->save();
        }
    }
}
