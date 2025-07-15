<?php

use App\Constituantpiece;
use Illuminate\Database\Seeder;

class ConstituantpieceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $constituantpieces = [
            array(
                "designation" => "Sol",
            ),
            array(
                "designation" => "Toit",
            ),
            array(
                "designation" => "Mure",
            )];

        foreach ($constituantpieces as $constituantpiece)
        {
            $newconstituantpiece = new Constituantpiece();
            $newconstituantpiece->designation = $constituantpiece['designation'];
            $newconstituantpiece->save();
        }
    }
}
