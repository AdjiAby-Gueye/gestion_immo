<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class EtatlieuPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'etatlieuspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('etatlieuspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'type' => ['type' => Type::string()],
                'dateredaction' => ['type' => Type::string()],
                'devi_id' => ['type' => Type::int(), 'description' => ''],
                'particularite' => ['type' => Type::string()],
                'etatgenerale' => ['type' => Type::string()],
                'pieceappartement_id' => ['type' => Type::int()],
                'appartement_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],
                'factureintervention_id' => ['type' => Type::int(), 'description' => ''],

                'constituantpieces' => ['type' => Type::listOf(GraphQL::type('Constituantpiece'))],
                'equipementpieces' => ['type' => Type::listOf(GraphQL::type('Equipementpiece'))],
                'intervention_id' => ['type' => Type::int(), 'description' => ''],
                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryEtatlieu($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
