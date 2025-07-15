<?php

use App\Frequencepaiementappartement;
use Illuminate\Database\Seeder;

class FrequencepaiementappartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $frequencepaiementappartements = [
            array(
                "designation" => "Mensuelle",

            ),
            array(
                "designation" => "Annuelle",

            ),
            array(
                "designation" => "Trimestriel",

            ) ];


        foreach ($frequencepaiementappartements as $frequencepaiementappartement)
        {
            $newfrequencepaiementappartement = Frequencepaiementappartement::where("designation" ,  $frequencepaiementappartement['designation'])->first();

            if (!$newfrequencepaiementappartement) {
                $newfrequencepaiementappartement = new Frequencepaiementappartement();
            }

            $newfrequencepaiementappartement->designation = $frequencepaiementappartement['designation'];
            $newfrequencepaiementappartement->save();
        }
    }
}
