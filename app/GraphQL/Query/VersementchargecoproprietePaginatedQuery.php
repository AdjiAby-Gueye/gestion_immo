<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class VersementchargecoproprietePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'versementchargecoproprietespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('versementchargecoproprietespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'dateversement' => ['type' => Type::string(), 'description' => ''],
                'anneecouverte' => ['type' => Type::string(), 'description' => ''],
                'montant' => ['type' => Type::string(), 'description' => ''],
                'document' => ['type' => Type::string(), 'description' => ''],
                'proprietaire_id' => ['type' => Type::string(), 'description' => ''],
                'contrat_id' => ['type' => Type::string(), 'description' => ''],
                'contrat' =>  ['type' =>  GraphQL::type('Contrat')],
                'proprietaire' =>  ['type' =>  GraphQL::type('Proprietaire')],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryVersementchargecopropriete($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
