<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class DetailfactureinterventionsQuery extends Query
{
    protected $attributes = [
        'name' => 'detailfactureinterventions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Detailfactureintervention'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id'                    => ['type' => Type::id()],
                'montant'               => ['type' => Type::int()],
                'intervention_id'       => ['type' => Type::id()],
                'factureintervention_id'=> ['type' => Type::id()],
                'order'                 => ['type' => Type::string()],
                'direction'             => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDetailfactureintervention($args);
        return $query->get();

    }
}
