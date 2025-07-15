<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class QuestionnairesatisfactionPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'questionnairesatisfactionspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('questionnairesatisfactionspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'titre' => ['type' => Type::string()],
                'contenu' => ['type' => Type::string()],
                'intervention_id' => ['type' => Type::int()],
                'locataires' => ['type' => Type::listOf(GraphQL::type('Locataire'))],
                'proprietaires' => ['type' => Type::listOf(GraphQL::type('Proprietaire'))],
                'reponsequestionnaires' => ['type' => Type::listOf(GraphQL::type('Reponsequestionnaire')), 'description' => ''],
                'documents' => ['type' => Type::listOf(GraphQL::type('Document'))],


                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryQuestionnairesatisfaction($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
