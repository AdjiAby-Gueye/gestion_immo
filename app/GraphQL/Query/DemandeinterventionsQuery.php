<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class
DemandeinterventionsQuery extends Query
{
    protected $attributes = [
        'name' => 'demandeinterventions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Demandeintervention'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'isgeneral' => ['type' => Type::string()],
                
                'image' => ['type' => Type::string()],
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'appartement' => ['type' =>  GraphQL::type('Appartement')],
                'typepiece' => ['type' =>  GraphQL::type('Typepiece')],
                'membreequipegestion' => ['type' =>  GraphQL::type('Membreequipegestion')],
                'immeuble' => ['type' =>  GraphQL::type('Immeuble')],
                'locataire_id' => ['type' => Type::int()],
                'appartement_id' => ['type' => Type::int()],
                'membreequipegestion_id' => ['type' => Type::int()],
                'immeuble_id' => ['type' => Type::int()],
                'devi'=> ['type' => Type::int()],
                'intervention_id' => ['type' => Type::int()],

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDemandeintervention($args);
        return $query->get();

    }
}
