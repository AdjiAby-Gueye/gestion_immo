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

class DetailcompositionType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Detailcomposition',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'idDetailtypeappartement' => ['type' => Type::int()],
                'composition' => ['type' =>  GraphQL::type('Composition')],
                'composition_id' => ['type' => Type::int()],
                'equipement_id' => ['type' => Type::int()],
                'appartement_id' => ['type' => Type::int()],
                'equipement' => ['type' =>  GraphQL::type('Equipementpiece')],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],


                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }



}

