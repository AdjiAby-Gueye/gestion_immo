<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class IlotPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'ilotspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('ilotspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],

                'numero' => ['type' => Type::int()],
                'adresse' => ['type' => Type::string()],
                'nombrevilla' => ['type' => Type::int(), 'description' => ''],

                'numerotitrefoncier' => ['type' => Type::string(), 'description' => ''],
                'datetitrefoncier' => ['type' => Type::string(), 'description' => ''],
                'adressetitrefoncier' => ['type' => Type::string(), 'description' => ''],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryIlot($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);

        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
