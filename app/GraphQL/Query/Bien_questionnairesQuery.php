<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class Bien_questionnairesQuery extends Query
{
    protected $attributes = [
        'name' => 'bien_questionnaires',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Bien_questionnaire'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'etat' => ['type' => Type::string()],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'questionnaire' => ['type' =>  GraphQL::type('Questionnaire')],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryBien_questionnaire($args);
        return $query->get();

    }
}
