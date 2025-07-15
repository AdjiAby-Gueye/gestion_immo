<?php

namespace App\GraphQL\Type;

use App\Intervention;
use App\RefactoringItems\RefactGraphQLType;


use App\Outil;
use Illuminate\Support\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use Psy\Util\Str;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class DetailinterventionType extends RefactGraphQLType
{
    protected $attributes = [
        'name' => 'Detailintervention',
        'description' => ''
    ];

    public function fields(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'intervention' => ['type' =>  GraphQL::type('Intervention')],
                'detailconstituant' => ['type' =>  GraphQL::type('Detailconstituant')],
                'detailequipement' => ['type' =>  GraphQL::type('Detailequipement')],

                'intervention_id' => ['type' => Type::int()],
                'detailconstituant_id' => ['type' => Type::int()],
                'detailequipement_id' => ['type' => Type::int()],


                'created_at' => ['type' => Type::string(), 'description' => ''],
                'created_at_fr' => ['type' => Type::string(), 'description' => ''],
                'updated_at' => ['type' => Type::string(), 'description' => ''],
                'updated_at_fr' => ['type' => Type::string(), 'description' => ''],
                'deleted_at' => ['type' => Type::string(), 'description' => ''],
                'deleted_at_fr' => ['type' => Type::string(), 'description' => ''],

            ];
    }



}

