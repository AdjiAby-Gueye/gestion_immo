<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ReponsequestionnairesQuery extends Query
{
    protected $attributes = [
        'name' => 'reponsequestionnaires',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Reponsequestionnaire'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'contenu' => ['type' => Type::string(), 'description' => ''],
                'questionnairesatisfaction' =>['type' =>  GraphQL::type('Questionnairesatisfaction')],
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'proprietaire' => ['type' =>  GraphQL::type('Proprietaire')],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryReponsequestionnaire($args);
        return $query->get();

    }
}
