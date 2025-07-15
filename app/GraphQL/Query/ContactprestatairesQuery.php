<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ContactprestatairesQuery extends Query
{
    protected $attributes = [
        'name' => 'contactprestataires',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Contactprestataire'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'prenom' => ['type' => Type::string()],
                'nom' => ['type' => Type::string()],
                'telephone' => ['type' => Type::string()],
                'email' => ['type' => Type::string()],
                'prestataire_id' => ['type' => Type::int()],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryContactprestataire($args);
        return $query->get();

    }
}
