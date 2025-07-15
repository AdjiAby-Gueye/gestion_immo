<?php

use App\Versementchargecopropriete;
use Illuminate\Database\Seeder;

class VersementchargecoproprieteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $versementchargecoproprietes = [
            array(
                "dateversement" => "2021/12/10",
                "anneecouverte" => "2022",
                "montant" => "50000",
                "document" => "lien",

            ),
            array(
                "dateversement" => "2022/11/22",
                "anneecouverte" => "2021",
                "montant" => "15000",
                "document" => "lien",

            )];

        foreach ($versementchargecoproprietes as $versementchargecopropriete)
        {
            $newversementchargecopropriete = new Versementchargecopropriete();
            $newversementchargecopropriete->dateversement = $versementchargecopropriete['dateversement'];
            $newversementchargecopropriete->anneecouverte = $versementchargecopropriete['anneecouverte'];
            $newversementchargecopropriete->montant = $versementchargecopropriete['montant'];
            $newversementchargecopropriete->document = $versementchargecopropriete['document'];
            $newversementchargecopropriete->save();
        }
    }
}
