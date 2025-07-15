<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class FacturesQuery extends Query
{
    protected $attributes = [
        'name' => 'factures',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Facture'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'datefacture' => ['type' => Type::string()],
                'moisfacture' => ['type' => Type::string()],
                'documentfacture' => ['type' => Type::string()],
                'recupaiement' => ['type' => Type::string()],
                'montant' => ['type' => Type::string()],
                'intervenantassocie' => ['type' => Type::string()],
                'periode' => ['type' => Type::string()],
                'partiecommune' => ['type' => Type::string()],
                'intervention_id' => ['type' => Type::id()],
                'typefacture_id' => ['type' => Type::id()],
                'appartement_id' => ['type' => Type::id()],
                'locataire_id' => ['type' => Type::int(), 'description' => ''],
                'proprietaire_id' => ['type' => Type::int(), 'description' => ''],
                'immeuble_id' => ['type' => Type::int(), 'description' => ''],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryFacture($args);
        return $query->get();

    }
}
