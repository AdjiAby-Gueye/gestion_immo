<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class CompositionPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'compositionspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('compositionspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'image' => ['type' => Type::string()],
                'superficie' => ['type' => Type::string()],
                'typeappartement_piece' => ['type' =>  GraphQL::type('Typeappartement_piece')],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'typeappartement_piece_id' => ['type' => Type::int()],
                'appartement_id' => ['type' => Type::int()],

                'niveauappartement_id' => ['type' => Type::int()],
                
                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryComposition($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
