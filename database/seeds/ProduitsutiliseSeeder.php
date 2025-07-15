<?php

use App\Produitsutilise;
use Illuminate\Database\Seeder;

class ProduitsutiliseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $produitsutilises = [
            array(
                "designation" => "javel",
            ),
            array(
                "designation" => "ciment",
            ),
            array(
                "designation" => "Desinfectant",
            )];

        foreach ($produitsutilises as $produitsutilise)
        {
            $newproduitsutilise = new Produitsutilise();
            $newproduitsutilise->designation = $produitsutilise['designation'];
            $newproduitsutilise->save();
        }
    }
}
