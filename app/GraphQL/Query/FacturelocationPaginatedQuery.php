<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class FacturelocationPaginatedQuery extends Query
{

    protected $attributes = [
        'name' => 'facturelocationspaginated',
    ];




    public function type(): Type
    {
        return GraphQL::type('facturelocationspaginated');
    }

    public function args(): array
    {
        return
            [
                
                'id' => ['type' => Type::id()],
                'typefacture_id' => ['type' => Type::int()],
                'periodicite_id' => ['type' =>  Type::int()],
                'contrat_id' => ['type' => Type::int()],
                'objetfacture' => ['type' => Type::string()],
                'datefacture' => ['type' => Type::string()],
                'nbremoiscausion' => ['type' => Type::int()],
                'montant' => ['type' => Type::string()],
                'date_echeance' => ['type' => Type::string(), 'description' => ''],
                'proprietaire_id' => ['type' => Type::int()],
                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],
                'demanderesiliation_id' => ['type' => Type::int(), 'description' => ''],
                'est_activer' => ['type' => Type::int(), 'description' => ''],
                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryFacturelocation($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);

        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }
}
