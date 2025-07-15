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

class CompositionType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Composition',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'image' => ['type' => Type::string(), 'description' => ''],
                'superficie' => ['type' => Type::string(), 'description' => ''],
                'typeappartement_piece' => ['type' =>  GraphQL::type('Typeappartement_piece')],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'typeappartement_piece_id' => ['type' => Type::int()],
                'appartement_id' => ['type' => Type::int()],

                'niveauappartement' => ['type' =>  GraphQL::type('Niveauappartement')],
                'niveauappartement_id' => ['type' => Type::int()],
                'detailcompositions' => ['type' => Type::listOf(GraphQL::type('Detailcomposition')), 'description' => ''],


                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

}

