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

class Etatlieu_pieceType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Etatlieu_piece',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'image' => ['type' => Type::string(), 'description' => ''],
                'etatlieu' => ['type' =>  GraphQL::type('Etatlieu')],
                'pieceappartement' => ['type' =>  GraphQL::type('Pieceappartement')],
                'composition' => ['type' =>  GraphQL::type('Composition')],
                'etatlieu_id' => ['type' => Type::int()],
                'composition_id' => ['type' => Type::int()],


                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }

}

