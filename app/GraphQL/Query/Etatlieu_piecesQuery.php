<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class Etatlieu_piecesQuery extends Query
{
    protected $attributes = [
        'name' => 'etatlieu_pieces',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Etatlieu_piece'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'image' => ['type' => Type::string()],
                'etatlieu' => ['type' =>  GraphQL::type('Etatlieu')],
                'pieceappartement' => ['type' =>  GraphQL::type('Pieceappartement')],
                'composition' => ['type' =>  GraphQL::type('Composition')],
                'etatlieu_id' => ['type' => Type::int()],
                'composition_id' => ['type' => Type::int()],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryEtatlieu_piece($args);
        return $query->get();

    }
}
