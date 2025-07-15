<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class Appartement_locatairePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'appartement_locatairespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('appartement_locatairespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'locataire' => ['type' =>  GraphQL::type('Appartement')],
                'appartement_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryAppartement_locataire($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
