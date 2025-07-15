<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class InterventionsQuery extends Query
{
    protected $attributes = [
        'name' => 'interventions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Intervention'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'partiecommune' => ['type' => Type::string()],
                'etat' => ['type' => Type::string()],
                'image' => ['type' => Type::string()],
                'dateintervention' => ['type' => Type::string()],
                'datefinintervention' => ['type' => Type::string()],
                'categorieintervention' => ['type' =>  GraphQL::type('Categorieintervention')],
                'typeintervention' => ['type' =>  GraphQL::type('Typeintervention')],
                'demandeintervention' => ['type' =>  GraphQL::type('Demandeintervention')],
                'prestataire' => ['type' =>  GraphQL::type('Prestataire')],
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'facture' => ['type' =>  GraphQL::type('Facture')],
                'categorieintervention_id' => ['type' => Type::int()],
                'factureintervention_id' => ['type' => Type::int()],
                'typeintervention_id' => ['type' => Type::int()],
                'demandeintervention_id' => ['type' => Type::int()],
                'etatlieu_id' => ['type' => Type::int()],
                'prestataire_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],
                'getLocataire' => ['type' => Type::int()],
                'facture_id' => ['type' => Type::int()],
                'membreequipegestion' => ['type' =>  GraphQL::type('Membreequipegestion')],
                'questionnairesatisfactions' => ['type' => Type::listOf(GraphQL::type('Questionnairesatisfaction'))],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryIntervention($args);
        return $query->get();

    }
}
