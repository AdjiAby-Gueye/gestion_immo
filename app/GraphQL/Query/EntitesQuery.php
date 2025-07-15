<?php

namespace App\GraphQL\Query;

use App\User;
use App\Outil;
use App\Entite;
use Carbon\Carbon;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class EntitesQuery extends Query
{
    protected $attributes = [
        'name' => 'entites',
        'description' => ''
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Entite'));
    }

    // arguments to filter query
    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::int()],
                'designation' => ['type' => Type::string()],
                'description' => ['type' => Type::string()],
                'image' => ['type' => Type::string()],
                'code' => ['type' => Type::string()],
                'gestionnaire_id' => ['type' => Type::int(), 'designation' => ''],

                // notaire
                'nomcompletnotaire' => ['type' => Type::string(), 'description' => ''],
                'emailnotaire' => ['type' => Type::string(), 'description' => ''],
                'telephone1notaire' => ['type' => Type::string(), 'description' => ''],
                'nometudenotaire' => ['type' => Type::string(), 'description' => ''],
                'emailetudenotaire' => ['type' => Type::string(), 'description' => ''],
                'telephoneetudenotaire' => ['type' => Type::string(), 'description' => ''],
                'assistantetudenotaire' => ['type' => Type::string(), 'description' => ''],
                'adressenotaire' => ['type' => Type::string(), 'description' => ''],
                'adresseetudenotaire' => ['type' => Type::string(), 'description' => ''],
                'activite_id' => ['type' => Type::id(), 'description' => ''],
                'activite' => ['type' =>  GraphQL::type('Activite')],

                'etat' => ['type' => Type::int(), 'description' => ''],
                
                'code' => ['type' => Type::string()],






                'order'                                     => ['type' => Type::string()],
                'direction'                                 => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryEntite($args);
        return $query->get();

    }
}
