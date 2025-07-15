<?php

use App\Paiementloyer;
use Illuminate\Database\Seeder;

class PaiementloyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paiementloyers = [
            array(
                "datepaiement" => "2021/12/10",
                "codefacture" => "20211210",
                "montantfacture" => "80000",
                "debutperiodevalide" => "2021/12/11",
                "finperiodevalide" => "2022/01/11",
                "periode" => "lien",

            ),
            array(
                "datepaiement" => "2022/01/10",
                "codefacture" => "20211210",
                "montantfacture" => "80000",
                "debutperiodevalide" => "2022/01/11",
                "finperiodevalide" => "2022/02/11",
                "periode" => "lien",

            )];

        foreach ($paiementloyers as $paiementloyer)
        {
            $newpaiementloyer = new Paiementloyer();
            $newpaiementloyer->datepaiement = $paiementloyer['datepaiement'];
            $newpaiementloyer->codefacture = $paiementloyer['codefacture'];
            $newpaiementloyer->montantfacture = $paiementloyer['montantfacture'];
            $newpaiementloyer->debutperiodevalide = $paiementloyer['debutperiodevalide'];
            $newpaiementloyer->finperiodevalide = $paiementloyer['finperiodevalide'];
            $newpaiementloyer->periode = $paiementloyer['periode'];
            $newpaiementloyer->save();
        }
    }
}
