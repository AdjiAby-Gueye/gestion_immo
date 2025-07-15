<?php

use App\Versementloyer;
use Illuminate\Database\Seeder;

class VersementloyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $versementloyers = [
            array(
                "dateversement" => "2021/12/10",
                "datedebut" => "2021/12/10",
                "datefin" => "2022/12/11",
                "montant" => "50000",
                "document" => "lien",

            ),
            array(
                "dateversement" => "2022/11/22",
                "datedebut" => "2022/11/22",
                "datefin" => "2022/12/22",
                "montant" => "15000",
                "document" => "lien",

            )];

        foreach ($versementloyers as $versementloyer)
        {
            $newversementloyer = new Versementloyer();
            $newversementloyer->dateversement = $versementloyer['dateversement'];
            $newversementloyer->datedebut = $versementloyer['datedebut'];
            $newversementloyer->datefin = $versementloyer['datefin'];
            $newversementloyer->montant = $versementloyer['montant'];
            $newversementloyer->document = $versementloyer['document'];
            $newversementloyer->save();
        }
    }
}
