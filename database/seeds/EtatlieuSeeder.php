<?php

use App\Etatlieu;
use Illuminate\Database\Seeder;

class EtatlieuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $etatlieus = [
            array(
                "designation" => "Etat des lieux du 6 janvier 2021",
                "dateredaction" => "2021/01/06",
                "particularite" => "sortie locataire",
                "etatgenerale" => "en bon etat",
            ) ];

        foreach ($etatlieus as $etatlieu)
        {
            $newetatlieu = new Etatlieu();
            $newetatlieu->designation = $etatlieu['designation'];
            $newetatlieu->dateredaction = $etatlieu['dateredaction'];
            $newetatlieu->particularite = $etatlieu['particularite'];
            $newetatlieu->etatgenerale = $etatlieu['etatgenerale'];
            $newetatlieu->save();
        }
    }
}
