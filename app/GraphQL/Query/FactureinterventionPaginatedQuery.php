<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class FactureinterventionPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'factureinterventionspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('factureinterventionspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'datefacture' => ['type' => Type::string()],
                'montant' => ['type' => Type::string()],
                'intervenantassocie' => ['type' => Type::string()],
                'intervention_id' => ['type' => Type::id()],
                'typefacture_id' => ['type' => Type::id()],
                'appartement_id' => ['type' => Type::id()],
                'paiementintervention_id' => ['type' => Type::id()],
                'intervention' => ['type' =>  GraphQL::type('Intervention')],
                'typefacture' => ['type' =>  GraphQL::type('Typefacture')],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'est_activer' => ['type' => Type::int(), 'description' => ''],
                'etatlieu_id' => ['type' => Type::int(), 'description' => ''],
                'locataire_id' => ['type' => Type::int()],



                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryFactureintervention($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
