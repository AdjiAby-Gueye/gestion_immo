<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class QuestionnairesQuery extends Query
{
    protected $attributes = [
        'name' => 'questionnaires',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Questionnaire'));

    }

    // arguments to filter query
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


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryQuestionnaire($args);
        return $query->get();

    }
}
