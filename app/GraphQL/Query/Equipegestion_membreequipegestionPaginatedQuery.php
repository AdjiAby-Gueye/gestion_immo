<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class Equipegestion_membreequipegestionPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'equipegestion_membreequipegestionspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('equipegestion_membreequipegestionspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'equipegestion' => ['type' =>  GraphQL::type('Equipegestion')],
                'membreequipegestion' => ['type' =>  GraphQL::type('Membreequipegestion')],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryEquipegestion_membreequipegestion($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
