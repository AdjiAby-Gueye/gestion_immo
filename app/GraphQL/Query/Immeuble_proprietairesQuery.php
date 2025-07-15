<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class Immeuble_proprietairesQuery extends Query
{
    protected $attributes = [
        'name' => 'immeuble_proprietaires',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Immeuble_proprietaire'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'proprietaire' => ['type' =>  GraphQL::type('Proprietaire')],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryImmeuble_proprietaire($args);
        return $query->get();

    }
}
