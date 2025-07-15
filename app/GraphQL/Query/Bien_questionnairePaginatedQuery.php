<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class Bien_questionnairePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'bien_questionnairespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('bien_questionnairespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'etat' => ['type' => Type::string()],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'questionnaire' => ['type' =>  GraphQL::type('Questionnaire')],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryBien_questionnaire($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
