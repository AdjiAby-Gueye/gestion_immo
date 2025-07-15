<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class DeviPaginatedQuery extends Query
{

    protected $attributes = [
        'name' => 'devispaginated',
    ];

    public function type(): Type
    {
        return GraphQL::type('devispaginated');
    }
    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'demandeintervention_id' => ['type' => Type::int()],
                'date' => ['type' => Type::string()],
                'object' => ['type' => Type::string()],
                'code'=> ['type' => Type::string()],
                'etatlieu_id'=>['type'=> Type::int()],
                'est_activer' => ['type' => Type::int()],
                'detaildevi_id' => ['type'=> Type::int(),'description' =>''],
                'detaildevisdetail_id' => ['type'=> Type::int(),'description' =>''],


                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],
            ];
    }
    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDevi($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);

        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }
}
