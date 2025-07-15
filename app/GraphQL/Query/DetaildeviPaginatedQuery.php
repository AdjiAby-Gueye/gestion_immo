<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class DetaildeviPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'detaildevispaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('detaildevispaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'devi_id' => ['type' => Type::int()],
                'categorieintervention_id' => ['type' => Type::int()],
                'prixunitaire' => ['type' => Type::string()],
                'quantite' => ['type' => Type::string()],
                'detaildevisdetail_id' => ['type'=> Type::int(),'description' =>''],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDetaildevi($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);

        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }
    
}
