<?php

use App\Contrat;
use Illuminate\Database\Seeder;

class ContratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contrats = [
            array(
                "codeappartement" => "A221Immeuble1",
                "document" => "lien",
                "scanpreavis" => "lien",
                "descriptif" => "premier contrat d'Andrea",
                "documentretourcaution" => "lien",
                "documentrecucaution" => "lien",
                "montantloyer" => "75000",
                "montantloyerbase" => "75000",
                "montantloyertom" => "75000",
                "montantcharge" => "5000",
                "tauxrevision" => "10",
                "frequencerevision" => "Mensuelle",
                "dateenregistrement" => "2021/12/10",
                "daterenouvellement" => "2022/12/10",
                "datepremierpaiement" => "2021/12/11",
                "dateretourcaution" => "2022/12/10",
                "daterenouvellementcontrat" => "2022/12/10",
                "datedebutcontrat" => "2021/12/10",
            ) ];

        foreach ($contrats as $contrat)
        {
            $newcontrat = new Contrat();
            $newcontrat->codeappartement = $contrat['codeappartement'];
            $newcontrat->document = $contrat['document'];
            $newcontrat->scanpreavis = $contrat['scanpreavis'];
            $newcontrat->descriptif = $contrat['descriptif'];
            $newcontrat->documentretourcaution = $contrat['documentretourcaution'];
            $newcontrat->documentrecucaution = $contrat['documentrecucaution'];
            $newcontrat->montantloyer = $contrat['montantloyer'];
            $newcontrat->montantloyerbase = $contrat['montantloyerbase'];
            $newcontrat->montantloyertom = $contrat['montantloyertom'];
            $newcontrat->montantcharge = $contrat['montantcharge'];
            $newcontrat->tauxrevision = $contrat['tauxrevision'];
            $newcontrat->frequencerevision = $contrat['frequencerevision'];
            $newcontrat->dateenregistrement = $contrat['dateenregistrement'];
            $newcontrat->daterenouvellement = $contrat['daterenouvellement'];
            $newcontrat->datepremierpaiement = $contrat['datepremierpaiement'];
            $newcontrat->dateretourcaution = $contrat['dateretourcaution'];
            $newcontrat->daterenouvellementcontrat = $contrat['daterenouvellementcontrat'];
            $newcontrat->datedebutcontrat = $contrat['datedebutcontrat'];
            $newcontrat->save();
        }
    }
}
