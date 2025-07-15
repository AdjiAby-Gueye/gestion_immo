<?php

use App\Facture;
use Illuminate\Database\Seeder;

class FactureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $factures = [
            array(
                "datefacture" => "2021/02/03",
                "moisfacture" => "Fevrier",
                "documentfacture" => "lien",
                "recupaiement" => "lien",
                "montant" => "50000",
                "intervenantassocie" => "Plombier",
                "periode" => "1 jour",
                "partiecommune" => "Jardin",

            ),
            array(
                "datefacture" => "2021/03/03",
                "moisfacture" => "Mars",
                "documentfacture" => "lien",
                "recupaiement" => "lien",
                "montant" => "150000",
                "intervenantassocie" => "Electricien",
                "periode" => "1 jour",
                "partiecommune" => "Couloire principale",

            )];

        foreach ($factures as $facture)
        {
            $newfacture = new Facture();
            $newfacture->datefacture = $facture['datefacture'];
            $newfacture->moisfacture = $facture['moisfacture'];
            $newfacture->documentfacture = $facture['documentfacture'];
            $newfacture->recupaiement = $facture['recupaiement'];
            $newfacture->montant = $facture['montant'];
            $newfacture->intervenantassocie = $facture['intervenantassocie'];
            $newfacture->periode = $facture['periode'];
            $newfacture->partiecommune = $facture['partiecommune'];
            $newfacture->save();
        }
    }
}
