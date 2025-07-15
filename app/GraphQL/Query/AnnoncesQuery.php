<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class AnnoncesQuery extends Query
{
    protected $attributes = [
        'name' => 'annonces',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Annonce'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'titre' => ['type' => Type::string()],
                'debut' => ['type' => Type::string()],
                'fin' => ['type' => Type::string()],
                'concernes' => ['type' => Type::string(), 'description' => ''],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'description' => ['type' => Type::string()],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'documents' => ['type' => Type::listOf(GraphQL::type('Document'))],
                'locataire_id' => ['type' => Type::int()],



                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryAnnonce($args);
        return $query->get();

    }
}
