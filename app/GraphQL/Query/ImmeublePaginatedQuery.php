<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class ImmeublePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'immeublespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('immeublespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'nom' => ['type' => Type::string()],
                'adresse' => ['type' => Type::string()],
                'iscopropriete' => ['type' => Type::string()],
                'nombreascenseur' => ['type' => Type::string()],
                'nombrepiscine' => ['type' => Type::string()],
                'structureimmeuble_id' => ['type' => Type::int(), 'description' => ''],
                'equipegestion_id' => ['type' => Type::string()],
                'structureimmeuble' => ['type' =>  GraphQL::type('Structureimmeuble')],
                'gardien' => ['type' =>  GraphQL::type('Gardien')],
                'pieceappartements' => ['type' => Type::listOf(GraphQL::type('Pieceappartement'))],
                'proprietaires' => ['type' => Type::listOf(GraphQL::type('Proprietaire'))],
                'appartements' => ['type' => Type::listOf(GraphQL::type('Appartement'))],
                'obligationadministratives' => ['type' => Type::listOf(GraphQL::type('Obligationadministrative'))],
                'annonces' => ['type' => Type::listOf(GraphQL::type('Annonce'))],
                'factures' => ['type' => Type::listOf(GraphQL::type('Facture'))],
                'rapportinterventions' => ['type' => Type::listOf(GraphQL::type('Rapportintervention'))],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryImmeuble($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
