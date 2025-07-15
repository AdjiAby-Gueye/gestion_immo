<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class ImageetatlieupiecePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'imageetatlieupiecespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('imageetatlieupiecespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'image' => ['type' => Type::string()],
                'imagecompteur' => ['type' => Type::string()],
                'etatlieupiece_id' => ['type' => Type::int()],
                'etatlieu_piece' => ['type' =>  GraphQL::type('Etatlieu_piece')],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryImageetatlieupiece($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
