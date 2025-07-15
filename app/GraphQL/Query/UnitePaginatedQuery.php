<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Arr;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class UnitePaginatedQuery extends Query
{


    protected $attributes = [
        'name' => 'unitespaginated',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return GraphQL::type('unitespaginated');
    }


    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],

                'order' => ['type' => Type::string()],
                'direction' => ['type' => Type::string()],
                
                'page' => ['type' => Type::int()],
                'limit' => ['type' => Type::int()],
            ];
    }
    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryUnite($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);
        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }
}
