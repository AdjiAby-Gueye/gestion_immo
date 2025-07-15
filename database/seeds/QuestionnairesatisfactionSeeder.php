<?php

use App\Questionnairesatisfaction;
use Illuminate\Database\Seeder;

class QuestionnairesatisfactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questionnairesatisfactions = [
            array(
                "titre" => "questionnaire sur l'operation de nettoyage",
                "contenu" => "est ce que l'immeuble a ete bien nettoyé ?",
            ),
            array(
                "titre" => "questionnaire sur la reparation",
                "contenu" => "etes vous satisfaite ?",
            )];

        foreach ($questionnairesatisfactions as $questionnairesatisfaction)
        {
            $newquestionnairesatisfaction = new Questionnairesatisfaction();
            $newquestionnairesatisfaction->titre = $questionnairesatisfaction['titre'];
            $newquestionnairesatisfaction->contenu = $questionnairesatisfaction['contenu'];
            $newquestionnairesatisfaction->save();
        }
    }
}
