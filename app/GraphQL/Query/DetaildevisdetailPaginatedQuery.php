<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class DetaildevisdetailPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'detaildevisdetailspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('detaildevisdetailspaginated');
    }
    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],


                'detaildevi_id' => ['type' => Type::int()],
                'soustypeintervention_id'=> ['type' => Type::int()],
                'quantite_id'=> ['type' => Type::string()],
               
     

                'created_at' => ['type' => Type::string()],
                'created_at_fr' => ['type' => Type::string()],
                'updated_at' => ['type' => Type::string()],
                'updated_at_fr' => ['type' => Type::string()],
                'deleted_at' => ['type' => Type::string()],
                'deleted_at_fr' => ['type' => Type::string()],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDetaildevisdetail($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);

        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);

    }
}