<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class DetailequipementPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'detailequipementspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('detailequipementspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'commentaire' => ['type' => Type::string(), 'description' => ''],
                'etatlieu_piece' => ['type' =>  GraphQL::type('Etatlieu_piece')],
                'equipementpiece' => ['type' =>  GraphQL::type('Equipementpiece')],
                'observation' => ['type' =>  GraphQL::type('Observation')],
                'etatlieu_piece_id' => ['type' => Type::int()],
                'equipementpiece_id' => ['type' => Type::int()],
                'observation_id' => ['type' => Type::int()],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDetailequipement($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
