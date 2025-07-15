<?php

use App\Entite;
use App\Appartement;
use Illuminate\Database\Seeder;

class AppartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appartements = [
            array(
                "codeappartement" => "A221Immeuble1",
                "nom" => "A221",
                "isassurance" => 0,
                "iscontrat" => 0,
                "islocataire" => 0,
                "entite_id" => 1,
                "immeuble_id" => 1,

            ),
            array(
                "codeappartement" => "A23Immeuble2",
                "nom" => "A23",
                "isassurance" => 0,
                "iscontrat" => 0,
                "islocataire" => 0,
                "entite_id" => 1,
                "immeuble_id" => 2,

            )];
               
        foreach ($appartements as $appartement)
        {
            $newappartement = Appartement::where('nom', $appartement['nom'])->first();

            if (!$newappartement) {
                $newappartement = new Appartement();
            }

            $newappartement->codeappartement = $appartement['codeappartement'];
            $newappartement->nom = $appartement['nom'];
            $newappartement->isassurance = $appartement['isassurance'];
            $newappartement->iscontrat = $appartement['iscontrat'];
            $newappartement->islocataire = $appartement['islocataire'];
            $newappartement->entite_id = $appartement['entite_id'];
            $newappartement->immeuble_id = $appartement['immeuble_id'];
            $newappartement->save();
        }

        $entite = Entite::where("code", "RID")->first();
        $pps = Appartement::where("entite_id" , $entite->id)->get();
        foreach ($pps as $appartement)
        {
            if ($appartement->id && $appartement->entite->code == $entite->code) {
                $appartement->etatlieu = '0';
                $appartement->save();
            }
          
        }
    }
}
