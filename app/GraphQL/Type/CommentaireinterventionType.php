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

class CommentaireinterventionType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Commentaireintervention',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'description' => ['type' => Type::string(), 'description' => ''],
                'intervention' => ['type' =>  GraphQL::type('Intervention')],
                'prestataire' => ['type' =>  GraphQL::type('Prestataire')],
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'user' => ['type' =>  GraphQL::type('User')],
                'intervention_id' => ['type' => Type::int(), 'description' => ''],
                'prestataire_id' => ['type' => Type::int(), 'description' => ''],
                'locataire_id' => ['type' => Type::int(), 'description' => ''],
                'user_id' => ['type' => Type::int(), 'description' => ''],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

}

