<?php

use App\Annonce;
use Illuminate\Database\Seeder;

class AnnonceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $annonces = [
            [
                "titre" => "Netoyage",
                "debut" => "2021/12/10",
                "fin" => "2021/12/11",
                "description" => "netoyage de tout l'immeuble",
            ]
        ];

        foreach ($annonces as $annonce) {
            Annonce::firstOrCreate(
                [
                    'titre' => $annonce['titre'],
                    'debut' => $annonce['debut'],
                    'fin' => $annonce['fin'],
                ],
                [
                    'description' => $annonce['description'],
                ]
            );
        }
    }
}
