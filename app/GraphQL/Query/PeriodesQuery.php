<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Periode;
use Carbon\Carbon;
use App\QueryModel;
use App\Candidature;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class PeriodesQuery extends Query
{
    protected $attributes = [
        'name' => 'periodes',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Periode'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'description' => ['type' => Type::string()],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryParametrage($args , Periode::class);
        return $query->get();

    }
}
