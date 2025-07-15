<?php

namespace App\GraphQL\Query;


use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class QuantitesQuery extends Query
{
    protected $attributes = [
        'name' => 'quantites',
        'description' => ''
    ];


    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Quantite'));
    }


    
    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'qunatite' => ['type' => Type::string()],

                'order' => ['type' => Type::string()],
                'direction'=> ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryQuantite($args);
        return $query->get();
    }
}
