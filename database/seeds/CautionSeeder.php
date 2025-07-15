<?php

use App\Caution;
use Illuminate\Database\Seeder;

class CautionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cautions = [
            array(
                "codeappartement" => "A221Immeuble1",
                "document" => "lien",
                "montantloyer" => "75000",
                "montantcaution" => "225000",
                "dateversement" => "2022/01/01",
                "datepaiement" => "2022/01/01",
                "etat" => "Payé",
            ) ];

        foreach ($cautions as $caution)
        {
            $newcaution = new Caution();
            $newcaution->codeappartement = $caution['codeappartement'];
            $newcaution->document = $caution['document'];
            $newcaution->montantloyer = $caution['montantloyer'];
            $newcaution->montantcaution = $caution['montantcaution'];
            $newcaution->dateversement = $caution['dateversement'];
            $newcaution->datepaiement = $caution['datepaiement'];
            $newcaution->etat = $caution['etat'];
            $newcaution->save();
        }
    }
}
