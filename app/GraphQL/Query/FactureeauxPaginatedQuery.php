<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class FactureeauxPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'factureeauxspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('factureeauxspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],

                'paiementloyer_id' => ['type' => Type::int()],


                'contrat_id' => ['type' => Type::int(), 'description' => ''],
                'debutperiode' => ['type' => Type::string(), 'description' => ''],
                'finperiode' => ['type' => Type::string(), 'description' => ''],
                'consommation' => ['type' => Type::string(), 'description' => ''],
                'prixmetrecube' => ['type' => Type::string(), 'description' => ''],
                'soldeanterieur' => ['type' => Type::string(), 'description' => ''],
                'montantfacture' => ['type' => Type::string(), 'description' => ''],
                'quantitedebut' => ['type'=> Type::string(),  'description' => ''],
                'quantitefin'=> ['type'=> Type::string(), 'description' => ''],
                'demanderesiliation_id' => ['type' => Type::int(), 'description' => ''],
                'est_activer' => ['type' => Type::int()],
                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],

            ];

        }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryFactureeaux($args);

        $count = Arr::get($args, 'count', 10);
        $page = Arr::get($args, 'page', 1);
        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);

    }

}