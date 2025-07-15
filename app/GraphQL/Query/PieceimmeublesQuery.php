<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class PieceimmeublesQuery extends Query
{
    protected $attributes = [
        'name' => 'pieceimmeubles',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Pieceimmeuble'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'typepiece' => ['type' =>  GraphQL::type('Typepiece')],
                'immeuble_id' => ['type' => Type::int()],
                'typepiece_id' => ['type' => Type::int()],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryPieceimmeuble($args);
        return $query->get();

    }
}
