<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class PaiementecheancesQuery extends Query
{
    protected $attributes = [
        'name' => 'paiementecheances',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Paiementecheance'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],

                'date' => ['type' => Type::string(), 'description' => ''],

                'numero' => ['type' => Type::string(), 'description' => ''],

                'numero_cheque' => ['type' => Type::string(), 'description' => ''],

                'montant' => ['type' => Type::string(), 'description' => ''],

                'etat' => ['type' => Type::int(), 'description' => ''],

                'montantencaisse' => ['type' => Type::string(), 'description' => ''],

                'montantenattente' => ['type' => Type::string(), 'description' => ''],

                'paiementecheance_id' => ['type' => Type::string(), 'description' => ''],

                'avisecheance_id' => ['type' => Type::int(), 'description' => ''],

                'periodes' => ['type' => Type::string(), 'description' => ''],
                'lot' => ['type' => Type::string(), 'description' => ''],
                'ilot' => ['type' => Type::string(), 'description' => '' ],
                'locataire_id' => ['type' =>  Type::int()],

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryPaiementecheance($args);
        return $query->get();

    }
}
