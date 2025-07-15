<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class AssurancesQuery extends Query
{
    protected $attributes = [
        'name' => 'assurances',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Assurance'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'descriptif' => ['type' => Type::string()],
                'montant' => ['type' => Type::string()],
                'debut' => ['type' => Type::string()],
                'fin' => ['type' => Type::string()],
                'document' => ['type' => Type::string()],
                'assureur_id' => ['type' => Type::int()],
                'etatassurance_id' => ['type' => Type::int()],
                'contrat_id' => ['type' => Type::int()],
                'assureur' => ['type' =>  GraphQL::type('Assureur')],
                'typeassurance' => ['type' =>  GraphQL::type('Typeassurance')],
                'prestataire' => ['type' =>  GraphQL::type('Prestataire')],
                'etatassurance' => ['type' =>  GraphQL::type('Etatassurance')],
                'contrat' => ['type' =>  GraphQL::type('Contrat')],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryAssurance($args);
        return $query->get();

    }
}
