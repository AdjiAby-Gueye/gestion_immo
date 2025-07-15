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

class DetailconstituantType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Detailconstituant',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'commentaire' => ['type' => Type::string(), 'description' => ''],
                'etatlieu_piece' => ['type' =>  GraphQL::type('Etatlieu_piece')],
                'constituantpiece' => ['type' =>  GraphQL::type('Constituantpiece')],
                'observation' => ['type' =>  GraphQL::type('Observation')],
                'etatlieu_piece_id' => ['type' => Type::int()],
                'constituantpiece_id' => ['type' => Type::int()],
                'observation_id' => ['type' => Type::int()],


                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }



}

