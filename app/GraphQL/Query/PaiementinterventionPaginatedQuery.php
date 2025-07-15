<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class PaiementinterventionPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'paiementinterventionspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('paiementinterventionspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'factureintervention_id' => ['type' => Type::id()],
                'modepaiement_id' => ['type' => Type::int()],
                'date' => ['type' => Type::string(), 'description' => ''],
                'montant' => ['type' => Type::string(), 'description' => ''],
                'cheque' => ['type' => Type::string(), 'description' => ''],
                'est_activer' => ['type' => Type::int(), 'description' => ''],
                
                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryPaiementintervention($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);
        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }
}
