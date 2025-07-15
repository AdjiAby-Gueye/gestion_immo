<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class DetailinterventionsQuery extends Query
{
    protected $attributes = [
        'name' => 'detailinterventions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Detailintervention'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'intervention' => ['type' =>  GraphQL::type('Intervention')],
                'detailconstituant' => ['type' =>  GraphQL::type('Detailconstituant')],
                'detailequipement' => ['type' =>  GraphQL::type('Detailequipement')],
                'intervention_id' => ['type' => Type::int()],
                'detailconstituant_id' => ['type' => Type::int()],
                'detailequipement_id' => ['type' => Type::int()],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDetailintervention($args);
        return $query->get();

    }
}
