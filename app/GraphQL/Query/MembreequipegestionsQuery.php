<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class MembreequipegestionsQuery extends Query
{
    protected $attributes = [
        'name' => 'membreequipegestions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Membreequipegestion'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'prenom' => ['type' => Type::string()],
                'nom' => ['type' => Type::string()],
                'email' => ['type' => Type::string()],
                'telephone' => ['type' => Type::string()],
                'interventions' => ['type' => Type::listOf(GraphQL::type('Intervention'))],
                'demandeinterventions' => ['type' => Type::listOf(GraphQL::type('Demandeintervention'))],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryMembreequipegestion($args);
        return $query->get();

    }
}
