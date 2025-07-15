<?php

use App\Delaipreavi;
use Illuminate\Database\Seeder;

class DelaipreaviSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $delaipreavis = [
            array(
                "designation" => "1 mois",
            ),
            array(
                "designation" => "2 mois",
            )];

        foreach ($delaipreavis as $delaipreavi)
        {
            $newdelaipreavi = new Delaipreavi();
            $newdelaipreavi->designation = $delaipreavi['designation'];
            $newdelaipreavi->save();
        }
    }
}
