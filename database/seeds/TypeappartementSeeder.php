<?php

use App\Typeappartement;
use Illuminate\Database\Seeder;

class TypeappartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typeappartements = [
            array(
                "designation" => "Studio",
            ),
            array(
                "designation" => "Mini studion",
            ),
            array(
                "designation" => "Duplex",
            )];

        foreach ($typeappartements as $typeappartement)
        {
            $newtypeappartement = new Typeappartement();
            $newtypeappartement->designation = $typeappartement['designation'];
            $newtypeappartement->save();
        }
    }
}
