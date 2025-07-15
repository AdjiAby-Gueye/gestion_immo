<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class Etatlieu_piecePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'etatlieu_piecespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('etatlieu_piecespaginated');
    }

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

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryEtatlieu_piece($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
