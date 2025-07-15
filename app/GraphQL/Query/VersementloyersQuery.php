<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class VersementloyersQuery extends Query
{
    protected $attributes = [
        'name' => 'versementloyers',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Versementloyer'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'dateversement' => ['type' => Type::string()],
                'debut' => ['type' => Type::string()],
                'fin' => ['type' => Type::string()],
                'montant' => ['type' => Type::string()],
                'document' => ['type' => Type::string()],
                'proprietaire_id' => ['type' => Type::int()],
                'contrat_id' =>  ['type' => Type::int()],
                'contrat' =>  ['type' =>  GraphQL::type('Contrat')],
                'proprietaire' =>  ['type' => Type::string(), 'Proprietaire' => ''],

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryVersementloyer($args);
        return $query->get();

    }
}
