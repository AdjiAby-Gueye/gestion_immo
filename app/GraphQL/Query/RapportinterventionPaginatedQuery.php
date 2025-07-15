<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class RapportinterventionPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'rapportinterventionspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('rapportinterventionspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'prenom' => ['type' => Type::string()],
                'compagnietechnicien' => ['type' => Type::string()],
                'debut' => ['type' => Type::string()],
                'fin' => ['type' => Type::string()],
                'duree' => ['type' => Type::string()],
                'observations' => ['type' => Type::string()],
                'etat' => ['type' => Type::string()],
                'recommandations' => ['type' => Type::string()],
                'immeuble_id' => ['type' => Type::int()],
                'intervention' => ['type' =>  GraphQL::type('Intervention')],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'appartement_id' => ['type' => Type::int()],
                'produitsutilises' => ['type' => Type::listOf(GraphQL::type('Produitsutilise'))],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryRapportintervention($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
