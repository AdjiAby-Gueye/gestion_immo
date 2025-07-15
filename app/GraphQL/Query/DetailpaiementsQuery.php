<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Periode;
use Carbon\Carbon;
use App\QueryModel;
use App\Candidature;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class DetailpaiementsQuery extends Query
{
    protected $attributes = [
        'name' => 'detailpaiements',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Detailpaiement'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],

                'periode_id' => ['type' => Type::int(), 'description' => ''],
                'paiementloyer_id' => ['type' => Type::string(), 'description' => ''],

                'montant' => ['type' => Type::int(), 'description' => ''],

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDetailPaiement($args);
        return $query->get();

    }
}
