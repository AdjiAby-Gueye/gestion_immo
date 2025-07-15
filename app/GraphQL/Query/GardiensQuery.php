<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class GardiensQuery extends Query
{
    protected $attributes = [
        'name' => 'gardiens',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Gardien'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'prenom' => ['type' => Type::string()],
                'nom' => ['type' => Type::string()],
                'adresse' => ['type' => Type::string()],
                'telephone1' => ['type' => Type::string()],
                'telephone2' => ['type' => Type::string()],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryGardien($args);
        return $query->get();

    }
}
