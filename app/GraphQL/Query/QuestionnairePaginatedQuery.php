<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class QuestionnairePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'questionnairespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('questionnairespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'sallefete' => ['type' => Type::string()],
                'sallegym' => ['type' => Type::string()],
                'receptionniste' => ['type' => Type::string()],
                'jardin' => ['type' => Type::string()],
                'parkingsousterrain' => ['type' => Type::string()],
                'parkingexterne' => ['type' => Type::string()],
                'entrepot' => ['type' => Type::string()],
                'syndic' => ['type' => Type::string()],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'appartement' =>  ['type' =>  GraphQL::type('Appartement')],
                'typequestionnaire' =>  ['type' =>  GraphQL::type('Typequestionnaire')],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryQuestionnaire($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
