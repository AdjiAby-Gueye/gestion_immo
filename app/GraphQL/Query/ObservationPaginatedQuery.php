<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class ObservationPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'observationspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('observationspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'equipementpieces' => ['type' => Type::listOf(GraphQL::type('Equipementpiece'))],
                'constituantpieces' => ['type' => Type::listOf(GraphQL::type('Constituantpiece'))],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryObservation($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
