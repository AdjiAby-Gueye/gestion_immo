<?php

use App\Pieceappartement;
use Illuminate\Database\Seeder;

class PieceappartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pieceappartements = [
            array(
                "designation" => "Chambre",
            ),
            array(
                "designation" => "Salon",
            ),
            array(
                "designation" => "Salle de bain",
            )];

        foreach ($pieceappartements as $pieceappartement)
        {
            $newpieceappartement = new Pieceappartement();
            $newpieceappartement->designation = $pieceappartement['designation'];
            $newpieceappartement->save();
        }
    }
}
