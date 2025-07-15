<?php

use App\Typeintervention;
use Illuminate\Database\Seeder;

class TypeinterventionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typeinterventions = [
            array(
                "designation" => "generale",
                "code" => 1,
            ),
            array(
                "designation" => "particulier",
                "code" => 2,
            )];

        foreach ($typeinterventions as $typeintervention)
        {
            $obj = Typeintervention::where("designation",$typeintervention['designation'])->first();
            if (!$obj) {
                # code...
                $obj = new Typeintervention();
            }
          
            $obj->designation = $typeintervention['designation'];
            $obj->code = $typeintervention['code'];
            $obj->save();
        }
    }
}
