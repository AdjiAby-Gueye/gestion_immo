<?php

use App\Typecontrat;
use Illuminate\Database\Seeder;

class TypecontratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typecontrats = [
            array(
                "designation" => "CDD",
            ),
            array(
                "designation" => "CDI",
            )
        ];

        foreach ($typecontrats as $typecontrat)
        {
            $newtypecontrat = Typecontrat::where('designation', $typecontrat['designation'])->first();
            if (!$newtypecontrat) {
                $newtypecontrat = new Typecontrat();
            }

            $newtypecontrat->designation = $typecontrat['designation'];
            $newtypecontrat->save();
        }
    }
}
