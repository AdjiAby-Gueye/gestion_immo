<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class AnnoncePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'annoncespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('annoncespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'titre' => ['type' => Type::string()],
                'datedebut' => ['type' => Type::string()],
                'datefin' => ['type' => Type::string()],
                'description' => ['type' => Type::string()],
                'concernes' => ['type' => Type::string(), 'description' => ''],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'immeublie_id' => ['type' => Type::int()],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'documents' => ['type' => Type::listOf(GraphQL::type('Document'))],
                'locataire_id' => ['type' => Type::int()],


                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryAnnonce($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
