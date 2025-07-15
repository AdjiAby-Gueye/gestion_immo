<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class Typeappartement_piecesQuery extends Query
{
    protected $attributes = [
        'name' => 'typeappartement_pieces',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Typeappartement_piece'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'typeappartement' => ['type' =>  GraphQL::type('Typeappartement')],
                'typepiece' => ['type' =>  GraphQL::type('Typepiece')],
                'commentaire' => ['type' => Type::string()],
                'designation' => ['type' => Type::string()],
                'typeappartement_id' => ['type' => Type::int(), 'description' => ''],
                'typepiece_id' => ['type' => Type::int(), 'description' => ''],

                
                'niveauappartement_id' => ['type' => Type::int()],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryTypeappartement_piece($args);
        return $query->get();

    }
}
