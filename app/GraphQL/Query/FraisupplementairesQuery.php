<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class FraisupplementairesQuery extends Query
{
    protected $attributes = [
        'name' => 'fraisupplementaires',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Fraisupplementaire'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id'                => ['type' => Type::id(), 'description' => ''],
                'designation'       => ['type' => Type::string(), 'description' => ''],
                'frais'             => ['type' => Type::string()],
                'avisecheance_id'   => ['type' => Type::int()],

                'order'             => ['type' => Type::string()],
                'direction'         => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryFraisupplementaires($args);
        return $query->get();

    }
}
