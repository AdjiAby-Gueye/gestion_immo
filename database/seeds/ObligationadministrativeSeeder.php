<?php

use App\Obligationadministrative;
use Illuminate\Database\Seeder;

class ObligationadministrativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $obligationadministratives = [
            array(
                "designation" => "paiement impot",
                "debut" => "2021/12/10",
                "fin" => "2022/12/11",
                "montant" => "15000",
                "document" => "lien",

            ),
            array(
                "designation" => "paiement taxe",
                "debut" => "2021/12/10",
                "fin" => "2022/12/11",
                "montant" => "15000",
                "document" => "lien",

            )];

        foreach ($obligationadministratives as $obligationadministrative)
        {
            $newobligationadministrative = new Obligationadministrative();
            $newobligationadministrative->designation = $obligationadministrative['designation'];
            $newobligationadministrative->debut = $obligationadministrative['debut'];
            $newobligationadministrative->fin = $obligationadministrative['fin'];
            $newobligationadministrative->montant = $obligationadministrative['montant'];
            $newobligationadministrative->document = $obligationadministrative['document'];
            $newobligationadministrative->save();
        }
    }
}
