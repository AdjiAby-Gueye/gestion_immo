<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ImageetatlieupiecesQuery extends Query
{
    protected $attributes = [
        'name' => 'imageetatlieupieces',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Imageetatlieupiece'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'image' => ['type' => Type::string()],
                'imagecompteur' => ['type' => Type::string()],
                'etatlieupiece_id' => ['type' => Type::int()],
                'etatlieu_piece' => ['type' =>  GraphQL::type('Etatlieu_piece')],



                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryImageetatlieupiece($args);
        return $query->get();

    }
}
