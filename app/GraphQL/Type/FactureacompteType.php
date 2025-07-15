<?php

namespace App\GraphQL\Type;

use App\RefactoringItems\RefactGraphQLType;


use App\Outil;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class FactureacompteType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Factureacompte',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'date' => ['type' => Type::string(), 'description' => ''],
                'montant' => ['type' => Type::int(), 'description' => ''],
                'montant_format' => ['type' => Type::string(), 'description' => ''],
                'date_echeance' => ['type' => Type::string(), 'description' => ''],
                'date_echeance_format' => ['type' => Type::string(), 'description' => ''],
                'datefacture_format' => ['type' => Type::string(), 'description' => ''],
                'contrat_id' => ['type' => Type::int(), 'description' => ''],
                'contrat' => ['type' =>  GraphQL::type('Contrat')],
                'commentaire' => ['type' => Type::string(), 'description' => ''],

                'est_activer' => ['type' => Type::int()],
                'etat_text' => ['type' => Type::string()],
                'etat_badge' => ['type' => Type::string()],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    protected function resolveDatefactureFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['date']);
    }
    protected function resolveDateEcheanceFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['date_echeance']);
    }


    protected function resolveMontantFormatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['montant']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }


    protected function resolveEtatTextField($root, $args)
    {

        $itemArray = array("etat" => $root['est_activer']);
        $retour = Outil::donneEtatGeneral("avisecheance", $itemArray)['texte'];
        if (empty($retour)) {
            $retour = "";
        }
        return $retour;
    }

    protected function resolveEtatBadgeField($root, $args)
    {

        $itemArray = array("etat" => $root['est_activer']);
        $retour = Outil::donneEtatGeneral("avisecheance", $itemArray)['badge'];
        if (empty($retour)) {
            $retour = "";
        }
        return $retour;
    }


}

