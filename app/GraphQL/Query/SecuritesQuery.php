<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class SecuritesQuery extends Query
{
    protected $attributes = [
        'name' => 'securites',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Securite'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'adresse' => ['type' => Type::string()],
                'etat' => ['type' => Type::string()],
                'telephone1' => ['type' => Type::string()],
                'telephone2' => ['type' => Type::string()],
                'immeuble_id' => ['type' => Type::int()],
                'prestataire_id' => ['type' => Type::int()],
                'horaire_id' => ['type' => Type::int()],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'prestataire' => ['type' =>  GraphQL::type('Prestataire')],
                'horaire' => ['type' =>  GraphQL::type('Horaire')],



                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQuerySecurite($args);
        return $query->get();

    }
}
