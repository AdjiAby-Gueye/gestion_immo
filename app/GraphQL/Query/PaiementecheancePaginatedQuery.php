<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class PaiementecheancePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'paiementecheancespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('paiementecheancespaginated');
    }

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

                'avisecheance_id' => ['type' => Type::int(), 'description' => ''],

                'paiementecheance_id' => ['type' => Type::string(), 'description' => ''],

                'periodes' => ['type' => Type::string(), 'description' => ''],

                'locataire_id' => ['type' =>  Type::int()],
                'modepaiement_id' => ['type' => Type::int()],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryPaiementecheance($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);
        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
