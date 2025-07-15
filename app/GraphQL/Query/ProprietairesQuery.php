<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ProprietairesQuery extends Query
{
    protected $attributes = [
        'name' => 'proprietaires',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Proprietaire'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'nom' => ['type' => Type::string()],
                'immeubles' => ['type' => Type::listOf(GraphQL::type('Immeuble'))],
                'appartements' => ['type' => Type::listOf(GraphQL::type('Appartement'))],
                'versementloyers' => ['type' => Type::listOf(GraphQL::type('Versementloyer'))],
                'versementchargecoproprietes' => ['type' => Type::listOf(GraphQL::type('Versementchargecopropriete'))],
                'messages' => ['type' => Type::listOf(GraphQL::type('Message'))],
                'questionnairesatisfactions' => ['type' => Type::listOf(GraphQL::type('Questionnairesatisfaction'))],
                'proprietaire_id' => ['type' => Type::int()],
                'search' => ['type' => Type::string()],

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryProprietaire($args);
        return $query->get();

    }
}
