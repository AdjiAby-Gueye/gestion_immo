<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class EtatlieusQuery extends Query
{
    protected $attributes = [
        'name' => 'etatlieus',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Etatlieu'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'type' => ['type' => Type::string()],
                'dateredaction' => ['type' => Type::string()],
                'particularite' => ['type' => Type::string()],
                'devi_id' => ['type' => Type::int(), 'description' => ''],
                'etatgenerale' => ['type' => Type::string()],
                'pieceappartement_id' => ['type' => Type::int()],
                'appartement_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],
                'constituantpieces' => ['type' => Type::listOf(GraphQL::type('Constituantpiece'))],
                'equipementpieces' => ['type' => Type::listOf(GraphQL::type('Equipementpiece'))],
                'factureintervention_id' => ['type' => Type::int(), 'description' => ''],

                'intervention_id' => ['type' => Type::int(), 'description' => ''],

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryEtatlieu($args);
        return $query->get();

    }
}
