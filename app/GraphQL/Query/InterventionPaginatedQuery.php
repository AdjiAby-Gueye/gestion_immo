<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class InterventionPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'interventionspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('interventionspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'partiecommune' => ['type' => Type::string()],
                'etat' => ['type' => Type::string()],
                'image' => ['type' => Type::string()],
                'dateintervention' => ['type' => Type::string()],
                'categorieintervention' => ['type' =>  GraphQL::type('Categorieintervention')],
                'typeintervention' => ['type' =>  GraphQL::type('Typeintervention')],
                'demandeintervention' => ['type' =>  GraphQL::type('Demandeintervention')],
                'etatlieu_id' => ['type' => Type::int()],
                'prestataire' => ['type' =>  GraphQL::type('Prestataire')],
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'facture' => ['type' =>  GraphQL::type('Facture')],
                'factureintervention_id' => ['type' => Type::int()],
                'categorieintervention_id' => ['type' => Type::int()],
                'typeintervention_id' => ['type' => Type::int()],
                'demandeintervention_id' => ['type' => Type::int()],
                'prestataire_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],
                'getLocataire' => ['type' => Type::int()],
                'facture_id' => ['type' => Type::int()],
                'membreequipegestion' => ['type' =>  GraphQL::type('Membreequipegestion')],
                'questionnairesatisfactions' => ['type' => Type::listOf(GraphQL::type('Questionnairesatisfaction'))],


                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryIntervention($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
