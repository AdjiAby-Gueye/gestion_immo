<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class FactureacomptesQuery extends Query
{
    protected $attributes = [
        'name' => 'factureacomptes',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Factureacompte'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'date' => ['type' => Type::string(), 'description' => ''],
                'montant' => ['type' => Type::int(), 'description' => ''],
                'date_echeance' => ['type' => Type::string(), 'description' => ''],
                'contrat_id' => ['type' => Type::int(), 'description' => ''],
                'commentaire' => ['type' => Type::string(), 'description' => ''],
                'est_activer' => ['type' => Type::int()],

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryFactureacompte($args);
        return $query->get();

    }
}
