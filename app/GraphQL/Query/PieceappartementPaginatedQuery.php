<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class PieceappartementPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'pieceappartementspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('pieceappartementspaginated');
    }

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

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryPieceappartement($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
