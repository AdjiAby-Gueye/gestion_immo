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

class RapportinterventionType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Rapportintervention',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'prenom' => ['type' => Type::string(), 'description' => ''],
                'compagnietechnicien' => ['type' => Type::string(), 'description' => ''],
                'debut' => ['type' => Type::string(), 'description' => ''],
                'fin' => ['type' => Type::string(), 'description' => ''],
                'duree' => ['type' => Type::string(), 'description' => ''],
                'observations' => ['type' => Type::string(), 'description' => ''],
                'etat' => ['type' => Type::string(), 'description' => ''],
                'recommandations' => ['type' => Type::string(), 'description' => ''],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'intervention' => ['type' =>  GraphQL::type('Intervention')],
                'produitsutilises' => ['type' => Type::listOf(GraphQL::type('Produitsutilise')), 'description' => ''],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

}

