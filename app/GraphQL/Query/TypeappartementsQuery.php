<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class TypeappartementsQuery extends Query
{
    protected $attributes = [
        'name' => 'typeappartements',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Typeappartement'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'appartements' => ['type' => Type::listOf(GraphQL::type('Appartement'))],
                'typeappartement_pieces' => ['type' => Type::listOf(GraphQL::type('Typeappartement_piece'))],
                'usage' => ['type' => Type::int(), 'description' => ''],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryTypeappartement($args);
        return $query->get();

    }
}
