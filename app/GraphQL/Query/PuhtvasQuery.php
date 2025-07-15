<?php

namespace App\GraphQL\Query;


use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class PuhtvasQuery extends Query
{
    protected $attributes = [
        'name' => 'puhtvas',
        'description' => ''
    ];


    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Puhtva'));
    }


    
    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'puhtva' => ['type' => Type::string()],

                'order' => ['type' => Type::string()],
                'direction'=> ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryPuhtva($args);
        return $query->get();
    }
}
