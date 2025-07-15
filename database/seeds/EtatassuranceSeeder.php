<?php

use App\Etatassurance;
use Illuminate\Database\Seeder;

class EtatassuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $etatassurances = [
            array(
                "designation" => "En cours",
            ),
            array(
                "designation" => "renouvelle",
            )];

        foreach ($etatassurances as $etatassurance)
        {
            $newetatassurance = new Etatassurance();
            $newetatassurance->designation = $etatassurance['designation'];
            $newetatassurance->save();
        }
    }
}
