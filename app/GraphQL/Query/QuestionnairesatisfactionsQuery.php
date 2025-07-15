<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class QuestionnairesatisfactionsQuery extends Query
{
    protected $attributes = [
        'name' => 'questionnairesatisfactions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Questionnairesatisfaction'));

    }

    // arguments to filter query
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


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryQuestionnairesatisfaction($args);
        return $query->get();

    }
}
