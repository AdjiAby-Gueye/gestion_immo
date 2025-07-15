<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class CompteclientPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'compteclientspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('compteclientspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
              'locataire_id' => ['type' => Type::string(), 'description' => ''],
                // montant
                'montant' => ['type' => Type::string(), 'description' => ''],
                // date
                'date' => ['type' => Type::string(), 'description' => ''],
                // typetransaction
                'typetransaction' => ['type' => Type::int() ,'description' => '' ],

                'etat' => ['type' => Type::int(), 'description' => ''],

                'paiementecheance_id' => ['type' => Type::int(), 'description' => ''],

                'user_id' => ['type' => Type::int(), 'description' => ''],


                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryCompteclient($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
