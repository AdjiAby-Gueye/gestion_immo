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

class DemandeinterventionType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Demandeintervention',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'designation' => ['type' => Type::string(), 'description' => ''],
                'isgeneral' => ['type' => Type::string(), 'description' => ''],
                'etat' => ['type' => Type::string(), 'description' => ''],
                'appartement_id'=> ['type' => Type::int(), 'description' => ''],
                'image' => ['type' => Type::string(), 'description' => ''],
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'typepiece' => ['type' =>  GraphQL::type('Typepiece')],
                'membreequipegestion' => ['type' =>  GraphQL::type('Membreequipegestion')],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'devi' => ['type' =>  GraphQL::type('Devi')],
                'intervention_id' => ['type' => Type::int(), 'description' => ''],
                'interventions' => ['type' => Type::listOf(GraphQL::type('Intervention')), 'description' => ''],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

}

