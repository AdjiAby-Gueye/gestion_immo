<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class AvisecheancePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'avisecheancespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('avisecheancespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'objet' => ['type' => Type::string(), 'description' => ''],

                'amortissement' => ['type' => Type::string()],
                'fraisgestion' => ['type' => Type::string()],

                'date' => ['type' => Type::string()],
                'date_echeance' => ['type' => Type::string()],
                'periodes' => ['type' => Type::string()],
                'est_activer' => ['type' => Type::int()],

                'contrat_id' => ['type' => Type::int()],

                'periodicite_id' => ['type' => Type::int()],
                'numero' => ['type' => Type::string(), 'description' => ''],

                'signature' => ['type' => Type::string(), 'description' => ''],
                'est_signer' => ['type' => Type::int()],
                'motif_annulation_paiement' => ['type' => Type::string()],
                'date_annulation_paiement' => ['type' => Type::string()],
                'created_at' => ['type' => Type::string(), 'description' => ''],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryAvisecheance($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('date', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
