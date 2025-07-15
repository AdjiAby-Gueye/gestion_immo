<?php

use App\Typefacture;
use Illuminate\Database\Seeder;

class TypefactureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {

        $typefactures = [
            array(
                "designation" => "eau",
            ),
            array(
                "designation" => "loyer",
            ),
            array(
                "designation" => "caution",
            ),
            array(
                "designation" => "échéance",
            ),
            array(
                "designation" => "acompte",
            )

        ];


        foreach ($typefactures as $typefacture)
        {

            $newtypefactures =  Typefacture::firstOrCreate(["designation" => $typefacture['designation']]);
        }
    }
}
