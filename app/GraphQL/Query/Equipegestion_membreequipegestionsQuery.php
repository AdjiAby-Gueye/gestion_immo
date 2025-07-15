<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class Equipegestion_membreequipegestionsQuery extends Query
{
    protected $attributes = [
        'name' => 'equipegestion_membreequipegestions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Equipegestion_membreequipegestion'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'equipement' => ['type' =>  GraphQL::type('Equipementpiece')],
                'observation' => ['type' =>  GraphQL::type('Observation')],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryEquipegestion_membreequipegestion($args);
        return $query->get();

    }
}
