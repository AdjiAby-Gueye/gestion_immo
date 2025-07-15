<?php

use App\Membreequipegestion;
use Illuminate\Database\Seeder;

class MembreequipegestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $membreequipegestions = [
            array(
                "prenom" => "Alioune",
                "nom" => "Cisse",
                "email" => "alioune@gmail.com",
                "telephone" => "775349374",

            ),
            array(
                "prenom" => "Anta",
                "nom" => "Gueye",
                "email" => "anta@gmail.com",
                "telephone" => "773533437",

            )];

        foreach ($membreequipegestions as $membreequipegestion)
        {
            $newmembreequipegestions = new Membreequipegestion();
            $newmembreequipegestions->prenom = $membreequipegestion['prenom'];
            $newmembreequipegestions->nom = $membreequipegestion['nom'];
            $newmembreequipegestions->email = $membreequipegestion['email'];
            $newmembreequipegestions->telephone = $membreequipegestion['telephone'];
            $newmembreequipegestions->save();
        }
    }
}
