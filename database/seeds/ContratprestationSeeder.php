<?php

use App\Contratprestation;
use Illuminate\Database\Seeder;

class ContratprestationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contratprestations = [
            array(
                "datesignaturecontrat" => "2021/12/10",
                "datedemarragecontrat" => "2021/12/10",
                "daterenouvellementcontrat" => "2022/12/11",
                "frequenceprestation" => "journaliere",
                "datepremiereprestation" => "2021/12/10",
                "datepremierefacture" => "2021/12/10",
                "document" => "lien",
                "montant" => "200000",
            ),
                array(
                    "datesignaturecontrat" => "2022/01/10",
                    "datedemarragecontrat" => "2022/01/10",
                    "daterenouvellementcontrat" => "2023/01/11",
                    "frequenceprestation" => "journaliere",
                    "datepremiereprestation" => "2022/01/10",
                    "datepremierefacture" => "2022/01/10",
                    "document" => "lien",
                    "montant" => "250000",
                )];

        foreach ($contratprestations as $contratprestation)
        {
            $newcontratprestation = new Contratprestation();
            $newcontratprestation->datesignaturecontrat = $contratprestation['datesignaturecontrat'];
            $newcontratprestation->datedemarragecontrat = $contratprestation['datedemarragecontrat'];
            $newcontratprestation->daterenouvellementcontrat = $contratprestation['daterenouvellementcontrat'];
            $newcontratprestation->frequenceprestation = $contratprestation['frequenceprestation'];
            $newcontratprestation->datepremiereprestation = $contratprestation['datepremiereprestation'];
            $newcontratprestation->datepremierefacture = $contratprestation['datepremierefacture'];
            $newcontratprestation->document = $contratprestation['document'];
            $newcontratprestation->montant = $contratprestation['montant'];
            $newcontratprestation->save();
        }
    }
}
