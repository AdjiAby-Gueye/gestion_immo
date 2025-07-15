<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class Etatlieupiece_equipementpiece_constituantpiecePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'etatlieupiece_equipementpiece_constituantpiecespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('etatlieupiece_equipementpiece_constituantpiecespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id(), 'description' => ''],
                'etatlieupiece' => ['type' =>  GraphQL::type('Etatlieu_piece')],
                'pieceequipementobservation' => ['type' =>  GraphQL::type('Piece_equipement_observation')],
                'piececonstituantobservation' => ['type' =>  GraphQL::type('Piece_constituant_observation')],
                'equipementobservation' => ['type' =>  GraphQL::type('Equipement_observation')],

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
