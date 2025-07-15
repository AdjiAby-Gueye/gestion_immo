<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class EquipegestionsQuery extends Query
{
    protected $attributes = [
        'name' => 'equipegestions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Equipegestion'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'immeubles' => ['type' => Type::listOf(GraphQL::type('Immeuble'))],
                'membreequipegestions' => ['type' => Type::listOf(GraphQL::type('Membreequipegestion'))],
                'fonction' => ['type' => Type::listOf(GraphQL::type('Fonction'))],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryEquipegestion($args);
        return $query->get();

    }
}
