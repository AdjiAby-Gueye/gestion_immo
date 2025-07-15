<?php

use App\Observation;
use Illuminate\Database\Seeder;

class ObservationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $observations = [
            [
                "designation" => "RAS",
            ],
            [
                "designation" => "En mauvais etat",
            ],
            [
                "designation" => "Dysfonctionnement",
            ],
        ];

        foreach ($observations as $observation) {
            Observation::firstOrCreate([
                'designation' => $observation['designation'],
            ]);
        }
    }
}
