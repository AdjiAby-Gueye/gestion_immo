<?php

use App\Annonce;
use App\Questionnaire;
use Illuminate\Database\Seeder;

class QuestionnaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questionnaires = [
            array("designation" => "Salle de fete ?", "nom" => "salleFete" , "nombre" => "nombre_sallefete", "reponsetype" => "nombre", "typequestionnaire_id" => 1 ) ,
            array("designation" => "Salle de gym ?", "nom" => "salleGym", "nombre" => "nombre_salleGym", "reponsetype" => "nombre", "typequestionnaire_id" => 1 ),
            array("designation" => "Receptionniste ?", "nom" => "receptionniste", "nombre" => "nombre_receptionniste" , "reponsetype" => "nombre" , "typequestionnaire_id" => 1 ) ,
            array("designation" => "Jardin ?", "nom" => "jardin", "nombre" => "nombre_jardin" , "reponsetype" => "nombre" , "typequestionnaire_id" => 1 ),
            array("designation" => "Parking sous terrain ?", "nom" => "parkingsousterrain", "nombre" => "nombre_parkingsousterrain" , "reponsetype" => "nombre" , "typequestionnaire_id" => 1),
            array("designation" => "Parking externe ?", "nom" => "parkingexterne", "nombre" => "nombre_parkingexterne" , "reponsetype" => "nombre" , "typequestionnaire_id" => 1),
            array("designation" => "Entrepot ?", "nom" => "entrepot", "nombre" => "nombre_entrepot" , "reponsetype" => "nombre" , "typequestionnaire_id" => 1),
            array("designation" => "Syndic ?", "nom" => "syndic", "nombre" => "nombre_syndic" , "reponsetype" => "text" , "typequestionnaire_id" => 1),
        ];

        foreach ($questionnaires as $questionnaire)
        {
            $newquestionnaire = new Questionnaire();
            $newquestionnaire->designation = $questionnaire['designation'];
            $newquestionnaire->nom = $questionnaire['nom'];
            $newquestionnaire->nombre = $questionnaire['nombre'];
            $newquestionnaire->reponsetype = $questionnaire['reponsetype'];
            $newquestionnaire->typequestionnaire_id = $questionnaire['typequestionnaire_id'];
            $newquestionnaire->save();
        }
    }
}
