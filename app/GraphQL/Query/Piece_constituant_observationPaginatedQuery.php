<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class Piece_constituant_observationPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'piece_constituant_observationspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('piece_constituant_observationspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'pieceappartement' => ['type' =>  GraphQL::type('Pieceappartement')],
                'constituantpiece' => ['type' =>  GraphQL::type('Constituantpiece')],
                'observation' => ['type' =>  GraphQL::type('Observation')],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryEquipement_observation($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
