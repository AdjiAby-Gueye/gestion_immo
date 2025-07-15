<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class FactureacomptePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'factureacomptespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('factureacomptespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'date' => ['type' => Type::string(), 'description' => ''],
                'montant' => ['type' => Type::int(), 'description' => ''],
                'date_echeance' => ['type' => Type::string(), 'description' => ''],
                'commentaire' => ['type' => Type::string(), 'description' => ''],

                'contrat_id' => ['type' => Type::int(), 'description' => ''],
                'est_activer' => ['type' => Type::int()],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryFactureacompte($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
