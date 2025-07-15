<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class DetailinterventionPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'detailinterventionspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('detailinterventionspaginated');
    }

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

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDetailintervention($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
