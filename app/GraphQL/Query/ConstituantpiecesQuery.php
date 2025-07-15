<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ConstituantpiecesQuery extends Query
{
    protected $attributes = [
        'name' => 'constituantpieces',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Constituantpiece'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'commentaire' => ['type' => Type::string()],
                'etat' => ['type' => Type::string()],
                'etatlieu_id' => ['type' => Type::string()],
                'observation_id' => ['type' => Type::int()],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryConstituantpiece($args);
        return $query->get();

    }
}
