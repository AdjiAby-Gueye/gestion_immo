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

class SecuriteType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Securite',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'designation' => ['type' => Type::string(), 'description' => ''],
                'adresse' => ['type' => Type::string(), 'description' => ''],
                'etat' => ['type' => Type::string(), 'description' => ''],
                'telephone1' => ['type' => Type::string(), 'description' => ''],
                'telephone2' => ['type' => Type::string(), 'description' => ''],
                'immeuble_id' => ['type' => Type::int()],
                'prestataire_id' => ['type' => Type::int()],
                'horaire_id' => ['type' => Type::int()],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'prestataire' => ['type' =>  GraphQL::type('Prestataire')],
                'horaire' => ['type' =>  GraphQL::type('Horaire')],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

}

