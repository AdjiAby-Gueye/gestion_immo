<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class FacturePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'facturespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('facturespaginated');
    }

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

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryFacture($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
