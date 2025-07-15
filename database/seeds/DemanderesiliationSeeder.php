<?php

use App\Demanderesiliation;
use Illuminate\Database\Seeder;

class DemanderesiliationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $demanderesiliations = [
            array(
                "datedebutcontrat" => "2020/12/10",
                "datedemande" => "2021/12/10",
                "delaipreavisrespecte" => "oui",
                "raisonnonrespectdelai" => "",
                "delaipreavis" => "1 mois",
                "dateeffectivite" => "2021/12/10",

            ) ];

        foreach ($demanderesiliations as $demanderesiliation)
        {
            $newdemanderesiliation = new Demanderesiliation();
            $newdemanderesiliation->datedebutcontrat = $demanderesiliation['datedebutcontrat'];
            $newdemanderesiliation->datedemande = $demanderesiliation['datedemande'];
            $newdemanderesiliation->delaipreavisrespecte = $demanderesiliation['delaipreavisrespecte'];
            $newdemanderesiliation->raisonnonrespectdelai = $demanderesiliation['raisonnonrespectdelai'];
            $newdemanderesiliation->delaipreavis = $demanderesiliation['delaipreavis'];
            $newdemanderesiliation->dateeffectivite = $demanderesiliation['dateeffectivite'];


            $newdemanderesiliation->save();
        }
    }
}
