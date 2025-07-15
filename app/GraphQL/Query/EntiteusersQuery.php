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

class EntiteusersQuery extends Query
{
    protected $attributes = [
        'name' => 'entiteusers',
        'description' => ''
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Entiteuser'));
    }

    // arguments to filter query
    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::int()],

                'entite_id' => ['type' => Type::int(), 'designation' => ''],
                'user_id' => ['type' => Type::int(), 'designation' => ''],

                'order'                                     => ['type' => Type::string()],
                'direction'                                 => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryEntiteuser($args);
        // dd($query->get());
        return $query->get();

    }
}
