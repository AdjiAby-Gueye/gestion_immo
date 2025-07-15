<?php

use App\Annonce;
use App\Questionnaire;
use App\Typequestionnaire;
use Illuminate\Database\Seeder;

class TypequestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $typequestionnaires = [
            array("designation" => "immeuble") ,
            array("designation" => "appartement"),
        ];

        foreach ($typequestionnaires as $typequestionnaire)
        {
            $newtypequestionnaire = new Typequestionnaire();
            $newtypequestionnaire->designation = $typequestionnaire['designation'];
            $newtypequestionnaire->save();
        }
    }
}
