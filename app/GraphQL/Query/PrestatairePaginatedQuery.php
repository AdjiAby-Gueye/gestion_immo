<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class PrestatairePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'prestatairespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('prestatairespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'nom' => ['type' => Type::string()],
                'adresse' => ['type' => Type::string()],
                'email' => ['type' => Type::string()],
                'telephone1' => ['type' => Type::string()],
                'telephone2' => ['type' => Type::int()],
                'interventions' => ['type' => Type::listOf(GraphQL::type('Intervention'))],
                'contacts' => ['type' => Type::listOf(GraphQL::type('Contactprestataire'))],
                'contratprestations' => ['type' => Type::listOf(GraphQL::type('Contratprestation'))],
                'categorieprestataire' => ['type' =>  GraphQL::type('Categorieprestataire')],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryPrestataire($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
