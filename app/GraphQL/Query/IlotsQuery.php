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

class IlotsQuery extends Query
{
    protected $attributes = [
        'name' => 'ilots',
        'description' => ''
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Ilot'));
    }

    // arguments to filter query
    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::int()],
                'numero' => ['type' => Type::int()],
                'adresse' => ['type' => Type::string()],
                'nombrevilla' => ['type' => Type::int(), 'description' => ''],
                'numerotitrefoncier' => ['type' => Type::string(), 'description' => ''],
                'datetitrefoncier' => ['type' => Type::string(), 'description' => ''],
                'adressetitrefoncier' => ['type' => Type::string(), 'description' => ''],

                'order'                                     => ['type' => Type::string()],
                'direction'                                 => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryIlot($args);
        return $query->get();
    }
}
