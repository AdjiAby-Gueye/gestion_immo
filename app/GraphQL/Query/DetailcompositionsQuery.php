<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class DetailcompositionsQuery extends Query
{
    protected $attributes = [
        'name' => 'detailcompositions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Detailcomposition'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id(), 'description' => ''],
                'idDetailtypeappartement' => ['type' => Type::int()],
                'composition' => ['type' =>  GraphQL::type('Composition')],
                'equipement' => ['type' =>  GraphQL::type('Equipementpiece')],
                'composition_id' => ['type' => Type::int()],
                'equipement_id' => ['type' => Type::int()],
                'appartement_id' => ['type' => Type::int()],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDetailcomposition($args);
        return $query->get();

    }
}
