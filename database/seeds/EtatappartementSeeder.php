<?php

use App\Etatappartement;
use Illuminate\Database\Seeder;

class EtatappartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $etatappartements = [
            array(
                "designation" => "En location",
            ),
            array(
                "designation" => "Libre",
            ),
            array(
                "designation" => "En construction",
            ),
            array(
                "designation" => "Archive",
            )];

        foreach ($etatappartements as $etatappartement)
        {
            $etatExistant = Etatappartement::where('designation',$etatappartement['designation'])->first();
            if (!$etatExistant) {
                $newetatappartement = new Etatappartement();
                $newetatappartement->designation = $etatappartement['designation'];
                $newetatappartement->save();
            }
        }
    }
}
