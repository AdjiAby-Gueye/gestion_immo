<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class AvisecheancesQuery extends Query
{
    protected $attributes = [
        'name' => 'avisecheances',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Avisecheance'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id(), 'description' => ''],
                'objet' => ['type' => Type::string(), 'description' => ''],

                'amortissement' => ['type' => Type::string()],
                'fraisgestion' => ['type' => Type::string()],

                'date' => ['type' => Type::string()],
                'date_echeance' => ['type' => Type::string()],
                'periodes' => ['type' => Type::string()],
                'est_activer' => ['type' => Type::int()],
                'numero' => ['type' => Type::string(), 'description' => ''],

                'contrat_id' => ['type' => Type::int()],

                'periodicite_id' => ['type' => Type::int()],

                'signature' => ['type' => Type::string(), 'description' => ''],
                'est_signer' => ['type' => Type::int()],
                'motif_annulation_paiement' => ['type' => Type::string()],
                'date_annulation_paiement' => ['type' => Type::string()],
                'created_at' => ['type' => Type::string(), 'description' => ''],

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryAvisecheance($args);
        return $query->get();

    }
}
