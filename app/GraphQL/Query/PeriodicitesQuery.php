<?php

namespace App\GraphQL\Query;

use App\User;
use App\Outil;
use App\Entite;
use Carbon\Carbon;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class PeriodicitesQuery extends Query
{
    protected $attributes = [
        'name' => 'periodicites',
        'description' => ''
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Periodicite'));
    }

    // arguments to filter query
    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::int()],
                'designation' => ['type' => Type::string()],
                'nbr_mois' => ['type' => Type::string()],
                'description' => ['type' => Type::string()],

                'order'                                     => ['type' => Type::string()],
                'direction'                                 => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryPeriodicite($args);
        return $query->get();
    }
}
