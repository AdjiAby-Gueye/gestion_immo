<?php

namespace Database\Seeders;

use App\Contratproprietaire;
use Illuminate\Database\Seeder;

class ContratproprietaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contratproprietaires = [
            array(
                "descriptif" => "lorem ipsum",
                "date" => "01/01/2024",
                "entite_id" => 2,
                "proprietaire_id" => 1,
                "appartement_id" => 12,
                "immeuble_id" => 5,
            ) ];

        foreach ($contratproprietaires as $contratproprietaire)
        {
            $newcontratproprietaire = new Contratproprietaire();
            $newcontratproprietaire->descriptif = $contratproprietaire['descriptif'];
            $newcontratproprietaire->date = $contratproprietaire['date'];
            $newcontratproprietaire->entite_id = $contratproprietaire['entite_id'];
            $newcontratproprietaire->proprietaire_id = $contratproprietaire['proprietaire_id'];
            $newcontratproprietaire->immeuble_id = $contratproprietaire['immeuble_id'];
            $newcontratproprietaire->appartement_id = $contratproprietaire['appartement_id'];
            $newcontratproprietaire->save();
        }
    }
}
