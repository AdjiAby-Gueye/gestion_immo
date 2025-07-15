<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CompteclientsQuery extends Query
{
    protected $attributes = [
        'name' => 'compteclients',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Compteclient'));

    }

    // arguments to filter query
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



                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryCompteclient($args);
        return $query->get();

    }
}
