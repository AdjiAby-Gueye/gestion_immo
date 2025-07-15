<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class Piece_constituant_observationsQuery extends Query
{
    protected $attributes = [
        'name' => 'piece_constituant_observations',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Piece_constituant_observation'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'pieceappartement' => ['type' =>  GraphQL::type('Pieceappartement')],
                'constituantpiece' => ['type' =>  GraphQL::type('Constituantpiece')],
                'observation' => ['type' =>  GraphQL::type('Observation')],


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
