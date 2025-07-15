<?php

use App\Obligationadministrative;
use App\Typeobligationadministrative;
use App\Typpeobligationadministrative;
use Illuminate\Database\Seeder;

class TypeobligationadministrativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typeobligationadministratives = [
            array(
                "designation" => "impot",
            ),
            array(
                "designation" => "assurance",
            )];

        foreach ($typeobligationadministratives as $typeobligationadministrative)
        {
            $newtypeobligationadministrative = new Typeobligationadministrative();
            $newtypeobligationadministrative->designation = $typeobligationadministrative['designation'];
            $newtypeobligationadministrative->save();
        }
    }
}
