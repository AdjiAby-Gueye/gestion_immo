<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class TypeinterventionsQuery extends Query
{
    protected $attributes = [
        'name' => 'typeinterventions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Typeintervention'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'interventions' => ['type' => Type::listOf(GraphQL::type('Intervention'))],

                'code' => ['type' => Type::int(), 'description' => ''],
                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryTypeintervention($args);
        return $query->get();

    }
}
