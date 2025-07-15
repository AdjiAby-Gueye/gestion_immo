<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class FactureinterventionsQuery extends Query
{
    protected $attributes = [
        'name' => 'factureinterventions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Factureintervention'));
    }

    // arguments to filter query
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
                'est_activer' => ['type' => Type::int(), 'description' => ''],
                'etatlieu_id' => ['type' => Type::int(), 'description' => ''],

                'intervention' => ['type' =>  GraphQL::type('Intervention')],
                'typefacture' => ['type' =>  GraphQL::type('Typefacture')],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],

                'proprietaire_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],

                // datedeb
                'datedeb' => ['type' => Type::string()], // datedeb
                // datefin
                'datefin' => ['type' => Type::string()], // datefin



                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryFactureintervention($args);
        return $query->get();
    }
}
