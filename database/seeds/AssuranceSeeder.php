<?php

use App\Assurance;
use Illuminate\Database\Seeder;

class AssuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $assurances = [
            array(
                "montant" => "25000",
                "debut" => "2022/03/12",
                "fin" => "2022/07/12",
                "document" => "lien",
            ),
            array(
                "montant" => "35000",
                "debut" => "2022/05/12",
                "fin" => "2022/06/12",
                "document" => "lien",
            )];

        foreach ($assurances as $assurance)
        {
            $newassurance = new Assurance();
            $newassurance->montant = $assurance['montant'];
            $newassurance->debut = $assurance['debut'];
            $newassurance->fin = $assurance['fin'];
            $newassurance->document = $assurance['document'];
            $newassurance->save();
        }
    }
}
