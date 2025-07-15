<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class DetailcompositionPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'detailcompositionspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('detailcompositionspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'idDetailtypeappartement' => ['type' => Type::int()],
                'composition' => ['type' =>  GraphQL::type('Composition')],
                'equipement' => ['type' =>  GraphQL::type('Equipementpiece')],
                'composition_id' => ['type' => Type::int()],
                'equipement_id' => ['type' => Type::int()],
                'appartement_id' => ['type' => Type::int()],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQuerydetailcomposition($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
