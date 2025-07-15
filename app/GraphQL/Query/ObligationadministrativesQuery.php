<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ObligationadministrativesQuery extends Query
{
    protected $attributes = [
        'name' => 'obligationadministratives',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Obligationadministrative'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'debut' => ['type' => Type::string()],
                'fin' => ['type' => Type::string()],
                'montant' => ['type' => Type::string()],
                'document' => ['type' => Type::string()],
                'typeobligationadministrative_id' => ['type' => Type::int()],
                'immeuble_id' => ['type' => Type::int()],
                'appartement_id' => ['type' => Type::int()],
                'typeobligationadministrative' => ['type' =>  GraphQL::type('Typeobligationadministrative')],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryObligationadministrative($args);
        return $query->get();

    }
}
