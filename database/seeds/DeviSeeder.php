<?php

use App\Detaildevi;
use App\Detaildevisdetail;
use App\Devi;
use App\Soustypeintervention;
use Illuminate\Database\Seeder;

class DeviSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $devis = [
            array(
                'demandeintervention_id' => 1,
                'montanttotaltva' => '100.00',
                'tauxtva' => '1',
                'affaireobjet' => 'Objet de l\'affaire',
                'dateenregistrement' => '2023-10-17',
            )
        ];
        /// faire truncate de tout les tables
        Devi::truncate();
        Detaildevi::truncate();
        Soustypeintervention::truncate();
        Detaildevisdetail::truncate();
        foreach ($devis as $key => $value) {
            $deviitem = new  Devi();

            $deviitem->demandeintervention_id = $value['demandeintervention_id'];
            $deviitem->montanttotaltva = $value['montanttotaltva'];
            $deviitem->tauxtva = $value['tauxtva'];
            $deviitem->affaireobjet = $value['affaireobjet'];
            $deviitem->dateenregistrement = $value['dateenregistrement'];
            $deviitem->save();
        }
    }
}
