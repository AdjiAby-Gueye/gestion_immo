<?php

use App\Contactprestataire;
use Illuminate\Database\Seeder;

class ContactprestataireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contactprestataires = [
            array(
                "nom" => "Faye",
                "prenom" => "Ousmane",
                "telephone" => "774893949",
                "email" => "ousman@gmail.com",
            ),
            array(
                "nom" => "Ndiaye",
                "prenom" => "Astou",
                "telephone" => "774893949",
                "email" => "astou@gmail.com",
            )];

        foreach ($contactprestataires as $contactprestataire)
        {
            $newcontactprestataire = new Contactprestataire();
            $newcontactprestataire->nom = $contactprestataire['nom'];
            $newcontactprestataire->prenom = $contactprestataire['prenom'];
            $newcontactprestataire->telephone = $contactprestataire['telephone'];
            $newcontactprestataire->email = $contactprestataire['email'];
            $newcontactprestataire->save();
        }
    }
}
