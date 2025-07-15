<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class PieceappartementsQuery extends Query
{
    protected $attributes = [
        'name' => 'pieceappartements',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Pieceappartement'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'typepiece' => ['type' =>  GraphQL::type('Typepiece')],
                'appartement_id' => ['type' => Type::int()],
                'immeuble_id' => ['type' => Type::int()],
                'typepiece_id' => ['type' => Type::int()],
                'etatlieus' => ['type' => Type::listOf(GraphQL::type('Etatlieu'))],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryPieceappartement($args);
        return $query->get();

    }
}
