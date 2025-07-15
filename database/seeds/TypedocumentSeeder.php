<?php

use App\Typedocument;
use Illuminate\Database\Seeder;

class TypedocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typedocuments = [
            array(
                "designation" => "message",
            ),
            array(
                "designation" => "annonce",
            )];

        foreach ($typedocuments as $typedocument)
        {
            $newtypedocument = new Typedocument();
            $newtypedocument->designation = $typedocument['designation'];
            $newtypedocument->save();
        }
    }
}
