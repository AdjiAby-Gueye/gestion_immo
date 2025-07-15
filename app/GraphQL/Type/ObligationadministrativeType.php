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

class ObligationadministrativeType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Obligationadministrative',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'designation' => ['type' => Type::string(), 'description' => ''],
                'debut' => ['type' => Type::string(), 'description' => ''],
                'debut_format' => ['type' => Type::string(), 'description' => ''],
                'fin' => ['type' => Type::string(), 'description' => ''],
                'fin_format' => ['type' => Type::string(), 'description' => ''],
                'montant' => ['type' => Type::string(), 'description' => ''],
                'montant_format' => ['type' => Type::string(), 'description' => ''],
                'document' => ['type' => Type::string(), 'description' => ''],
                'typeobligationadministrative_id' => ['type' => Type::string(), 'description' => ''],
                'immeuble_id' => ['type' => Type::string(), 'description' => ''],
                'appartement_id' => ['type' => Type::string(), 'description' => ''],
                'typeobligationadministrative' => ['type' =>  GraphQL::type('Typeobligationadministrative')],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

    protected function resolveDebutFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['debut']);
    }

    protected function resolveFinFormatField($root, $args)
    {
        return $this->resolveAllDateFR($root['fin']);
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

