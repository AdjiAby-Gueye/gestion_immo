<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class ProprietairePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'proprietairespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('proprietairespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'nom' => ['type' => Type::string()],
                'immeubles' => ['type' => Type::listOf(GraphQL::type('Immeuble'))],
                'appartements' => ['type' => Type::listOf(GraphQL::type('Appartement'))],
                'versementloyers' => ['type' => Type::listOf(GraphQL::type('Versementloyer'))],
                'versementchargecoproprietes' => ['type' => Type::listOf(GraphQL::type('Versementchargecopropriete'))],
                'messages' => ['type' => Type::listOf(GraphQL::type('Message'))],
                'questionnairesatisfactions' => ['type' => Type::listOf(GraphQL::type('Questionnairesatisfaction'))],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryProprietaire($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
