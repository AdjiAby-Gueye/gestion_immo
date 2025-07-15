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

class FactureType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Facture',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'datefacture' => ['type' => Type::string(), 'description' => ''],
                'datefacture_format' => ['type' => Type::string(), 'description' => ''],
                'moisfacture' => ['type' => Type::string(), 'description' => ''],
                'documentfacture' => ['type' => Type::string(), 'description' => ''],
                'recupaiement' => ['type' => Type::string(), 'description' => ''],
                'montant' => ['type' => Type::string(), 'description' => ''],
                'montant_format' => ['type' => Type::string(), 'description' => ''],
                'intervenantassocie' => ['type' => Type::string(), 'description' => ''],
                'periode' => ['type' => Type::string(), 'description' => ''],
                'partiecommune' => ['type' => Type::string(), 'description' => ''],
                'intervention_id' => ['type' => Type::string(), 'description' => ''],
                'intervention' => ['type' =>  GraphQL::type('Intervention')],
                'typefacture_id' => ['type' => Type::string(), 'description' => ''],
                'typefacture' => ['type' =>  GraphQL::type('Typefacture')],
                'appartement_id' => ['type' => Type::string(), 'description' => ''],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'proprietaire_id' => ['type' => Type::int(), 'description' => ''],
                'proprietaire'    => ['type' =>  GraphQL::type('Proprietaire')],
                'locataire_id' => ['type' => Type::int(), 'description' => ''],
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'immeuble_id' => ['type' => Type::int(), 'description' => ''],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],




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

    protected function resolveMontantFormatField($root, $args)
    {
        $valeur_ht_format = Outil::formatPrixToMonetaire($root['montant']);
        if (empty($valeur_ht_format)) {
            $valeur_ht_format = "";
        }

        return $valeur_ht_format;
    }




}

