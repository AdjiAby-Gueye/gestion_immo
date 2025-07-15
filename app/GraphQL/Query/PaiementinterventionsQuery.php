<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class PaiementinterventionsQuery extends Query
{
    protected $attributes = [
        'name' => 'paiementinterventions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Paiementintervention'));

    }

    // arguments to filter query
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
                

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryPaiementintervention($args);
        return $query->get();

    }
}
