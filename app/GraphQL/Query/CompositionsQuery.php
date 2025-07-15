<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CompositionsQuery extends Query
{
    protected $attributes = [
        'name' => 'compositions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Composition'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'image' => ['type' => Type::string()],
                'superficie' => ['type' => Type::string()],
                'typeappartement_piece' => ['type' =>  GraphQL::type('Typeappartement_piece')],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'typeappartement_piece_id' => ['type' => Type::int()],
                'appartement_id' => ['type' => Type::int()],

                'niveauappartement_id' => ['type' => Type::int()],

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryComposition($args);
        return $query->get();

    }
}
