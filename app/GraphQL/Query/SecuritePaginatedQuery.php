<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class SecuritePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'securitespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('securitespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'etat' => ['type' => Type::string()],
                'adresse' => ['type' => Type::string()],
                'telephone1' => ['type' => Type::string()],
                'telephone2' => ['type' => Type::string()],
                'immeuble_id' => ['type' => Type::int()],
                'prestataire_id' => ['type' => Type::int()],
                'horaire_id' => ['type' => Type::int()],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'prestataire' => ['type' =>  GraphQL::type('Prestataire')],
                'horaire' => ['type' =>  GraphQL::type('Horaire')],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQuerySecurite($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
