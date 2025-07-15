<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class MembreequipegestionPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'membreequipegestionspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('membreequipegestionspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'prenom' => ['type' => Type::string()],
                'nom' => ['type' => Type::string()],
                'email' => ['type' => Type::string()],
                'telephone' => ['type' => Type::string()],
                'interventions' => ['type' => Type::listOf(GraphQL::type('Intervention'))],
                'demandeinterventions' => ['type' => Type::listOf(GraphQL::type('Demandeintervention'))],


                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryMembreequipegestion($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
