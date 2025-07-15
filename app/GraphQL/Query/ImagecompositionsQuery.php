<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ImagecompositionsQuery extends Query
{
    protected $attributes = [
        'name' => 'imagecompositions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Imagecomposition'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'image' => ['type' => Type::string()],
                'imagecompteur' => ['type' => Type::string()],
                'composition_id' => ['type' => Type::int()],
                'composition' => ['type' =>  GraphQL::type('Composition')],



                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryImagecomposition($args);
        return $query->get();

    }
}
