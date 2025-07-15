<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class PrestatairesQuery extends Query
{
    protected $attributes = [
        'name' => 'prestataires',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Prestataire'));

    }

    // arguments to filter query
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


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryPrestataire($args);
        return $query->get();

    }
}
