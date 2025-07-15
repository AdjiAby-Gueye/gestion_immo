<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class VersementchargecoproprietesQuery extends Query
{
    protected $attributes = [
        'name' => 'versementchargecoproprietes',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Versementchargecopropriete'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'dateversement' => ['type' => Type::string(), 'description' => ''],
                'anneecouverte' => ['type' => Type::string(), 'description' => ''],
                'montant' => ['type' => Type::string(), 'description' => ''],
                'document' => ['type' => Type::string(), 'description' => ''],
                'proprietaire_id' => ['type' => Type::string(), 'description' => ''],
                'contrat_id' => ['type' => Type::string(), 'description' => ''],
                'contrat' =>  ['type' =>  GraphQL::type('Contrat')],
                'proprietaire' =>  ['type' => Type::string(), 'Proprietaire' => ''],

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryVersementchargecopropriete($args);
        return $query->get();

    }
}
