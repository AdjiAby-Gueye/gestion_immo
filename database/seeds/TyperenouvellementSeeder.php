<?php

use App\Typerenouvellement;
use Illuminate\Database\Seeder;

class TyperenouvellementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typerenouvellements = [
            array(
                "designation" => "TASSIT",
            ),
            array(
                "designation" => "NON TASSIT",
            )];

        foreach ($typerenouvellements as $typerenouvellement)
        {
            $newtyperenouvellement = new Typerenouvellement();
            $newtyperenouvellement->designation = $typerenouvellement['designation'];
            $newtyperenouvellement->save();
        }
    }
}
