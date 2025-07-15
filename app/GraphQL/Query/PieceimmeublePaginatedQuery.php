<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class PieceimmeublePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'pieceimmeublespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('pieceimmeublespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'typepiece' => ['type' =>  GraphQL::type('Typepiece')],
                'immeuble_id' => ['type' => Type::int()],
                'typepiece_id' => ['type' => Type::int()],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryPieceimmeuble($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
