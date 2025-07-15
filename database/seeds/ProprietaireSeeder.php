<?php

use App\Proprietaire;
use Illuminate\Database\Seeder;

class ProprietaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $proprietaires = [
            array(
                "nom" => "Mouhamed Ndiaye",
            ),
            array(
                "nom" => "Ngor Sene",
            )];

        foreach ($proprietaires as $proprietaire)
        {
            $newproprietaire = new Proprietaire();
            $newproprietaire->nom = $proprietaire['nom'];
            $newproprietaire->save();
        }
    }
}
