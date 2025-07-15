<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\QueryModel;
use App\Candidature;
use App\Secteuractivite;
use Illuminate\Support\Arr;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;


class SecteuractivitePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'secteuractivitespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('secteuractivitespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'description' => ['type' => Type::string()],


                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryParametrage($args , Secteuractivite::class);
        
        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
