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

class AnnonceType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Annonce',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'titre' => ['type' => Type::string(), 'description' => ''],
                'debut' => ['type' => Type::string(), 'description' => ''],
                'fin' => ['type' => Type::string(), 'description' => ''],
                'concernes' => ['type' => Type::string(), 'description' => ''],
                'description' => ['type' => Type::string(), 'description' => ''],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'documents' => ['type' => Type::listOf(GraphQL::type('Document')), 'description' => ''],

                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

}

