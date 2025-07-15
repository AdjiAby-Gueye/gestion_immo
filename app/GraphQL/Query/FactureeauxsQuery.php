<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class FactureeauxsQuery extends Query
{

    protected $attributes = [
        'name' => 'factureeauxs',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Factureeaux'));

    }

    // arguments to filter query

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
                
                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryFactureeaux($args);
        return $query->get();

    }


}