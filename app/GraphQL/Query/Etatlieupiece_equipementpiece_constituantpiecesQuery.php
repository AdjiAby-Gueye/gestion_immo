<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class Etatlieupiece_equipementpiece_constituantpiecesQuery extends Query
{
    protected $attributes = [
        'name' => 'etatlieupiece_equipementpiece_constituantpieces',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Etatlieupiece_equipementpiece_constituantpiece'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id(), 'description' => ''],
                'etatlieupiece' => ['type' =>  GraphQL::type('Etatlieu_piece')],
                'pieceequipementobservation' => ['type' =>  GraphQL::type('Piece_equipement_observation')],
                'piececonstituantobservation' => ['type' =>  GraphQL::type('Piece_constituant_observation')],
                'equipementobservation' => ['type' =>  GraphQL::type('Equipement_observation')],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryEquipement_observation($args);
        return $query->get();

    }
}
