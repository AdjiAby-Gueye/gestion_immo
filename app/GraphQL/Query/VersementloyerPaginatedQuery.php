<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class VersementloyerPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'versementloyerspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('versementloyerspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'dateversement' => ['type' => Type::string()],
                'debut' => ['type' => Type::string()],
                'fin' => ['type' => Type::string()],
                'montant' => ['type' => Type::string()],
                'document' => ['type' => Type::string()],
                'proprietaire_id' => ['type' => Type::int()],
                'contrat_id' =>  ['type' => Type::int()],
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
        $query = QueryModel::getQueryVersementloyer($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
