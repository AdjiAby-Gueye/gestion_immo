<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class RapportinterventionsQuery extends Query
{
    protected $attributes = [
        'name' => 'rapportinterventions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Rapportintervention'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'prenom' => ['type' => Type::string()],
                'compagnietechnicien' => ['type' => Type::string()],
                'debut' => ['type' => Type::string()],
                'fin' => ['type' => Type::string()],
                'duree' => ['type' => Type::string()],
                'observations' => ['type' => Type::string()],
                'etat' => ['type' => Type::string()],
                'intervention' => ['type' =>  GraphQL::type('Intervention')],
                'recommandations' => ['type' => Type::string()],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'immeuble_id' => ['type' => Type::int()],
                'appartement_id' => ['type' => Type::int()],
                'produitsutilises' => ['type' => Type::listOf(GraphQL::type('Produitsutilise'))],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryRapportintervention($args);
        return $query->get();

    }
}
