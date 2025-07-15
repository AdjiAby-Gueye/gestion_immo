<?php

namespace App\GraphQL\Type;

use App\Appartement;
use App\Detailfactureintervention;
use App\Intervention;
use App\Locataire;
use App\RefactoringItems\RefactGraphQLType;


use App\Outil;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class FactureinterventionType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Factureintervention',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'datefacture' => ['type' => Type::string(), 'description' => ''],
                'datefacture_format' => ['type' => Type::string(), 'description' => ''],
                'montant' => ['type' => Type::string(), 'description' => ''],
                'montant_format' => ['type' => Type::string(), 'description' => ''],
                'intervenantassocie' => ['type' => Type::string(), 'description' => ''],
                'intervention_id' => ['type' => Type::int(), 'description' => ''],
                'intervention' => ['type' =>  GraphQL::type('Intervention')],
                'interventions' => ['type' => Type::listOf(GraphQL::type('Intervention'))],
                'typefacture_id' => ['type' => Type::string(), 'description' => ''],
                'typefacture' => ['type' =>  GraphQL::type('Typefacture')],
                'appartement_id' => ['type' => Type::string(), 'description' => ''],
                'locataire_id' => ['type' => Type::string(), 'description' => ''],
                'est_activer' => ['type' => Type::int(), 'description' => ''],

                'demandeintervention_id' => ['type' => Type::int(), 'description' => ''],
                'demandeintervention' => ['type' =>  GraphQL::type('Demandeintervention')],

                'etatlieu_id' => ['type' => Type::int(), 'description' => ''],
                'etatlieu' => ['type' =>  GraphQL::type('Etatlieu')],
                
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'detailfactureinterventions' => ['type' => Type::listOf(GraphQL::type('Detailfactureintervention'))],
                'paiementintervention_id' => ['type' => Type::id(), 'description' => ''],
                'paiementintervention' => ['type' =>  GraphQL::type('Paiementintervention')],
               // 'paiementinterventions' => ['type' => Type::listOf(GraphQL::type('Paiementintervention'))],


                



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
        return $this->resolveAllDateFR($root['datefacture']);
    }

    protected function resolveMontantField($root, $args)
    {
        $query = Detailfactureintervention::where('factureintervention_id',$root['id'])
          ->get();
          $amount = 0;
          
          foreach ($query as $value) {
            $amount = $amount + intval($value->montant);
          }
        return $amount;
    }

    protected function resolveMontantFormatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire(self::resolveMontantField($root, $args));
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }




}

