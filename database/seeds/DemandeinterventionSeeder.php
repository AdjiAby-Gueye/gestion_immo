<?php

use App\Demandeintervention;
use Illuminate\Database\Seeder;

class DemandeinterventionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $demandeinterventions = [
            array(
                "designation" => "Robinet cassé",
                'locataire_id' =>3 ,
                'appartement_id' => 2,
            ),
            array(
                "designation" => "Pas d'electricité",
                'locataire_id' =>5 ,
                'appartement_id' => 3,
            )];
            // eviter les doubles

          
            
            foreach ($demandeinterventions as $key => $value) {
                $demandeintervention = Demandeintervention::where('designation', $value['designation'])->first();
                if ($demandeintervention === null) {
                    $newdemandeintervention = new Demandeintervention();
                    $newdemandeintervention->designation = $value['designation'];
                    $newdemandeintervention->locataire_id = $value['locataire_id'];
                    $newdemandeintervention->appartement_id = $value['appartement_id'];
                    $newdemandeintervention->save();
                }
            }



      
    }
}
