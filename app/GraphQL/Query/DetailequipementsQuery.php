<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class DetailequipementsQuery extends Query
{
    protected $attributes = [
        'name' => 'detailequipements',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Detailequipement'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id(), 'description' => ''],
                'commentaire' => ['type' => Type::string()],
                'etatlieu_piece' => ['type' =>  GraphQL::type('Etatlieu_piece')],
                'equipementpiece' => ['type' =>  GraphQL::type('Equipementpiece')],
                'observation' => ['type' =>  GraphQL::type('Observation')],
                'etatlieu_piece_id' => ['type' => Type::int()],
                'equipementpiece_id' => ['type' => Type::int()],
                'observation_id' => ['type' => Type::int()],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDetailequipement($args);
        return $query->get();

    }
}
