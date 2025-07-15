<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CommentaireinterventionsQuery extends Query
{
    protected $attributes = [
        'name' => 'commentaireinterventions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Commentaireintervention'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'description' => ['type' => Type::string()],
                'intervention' => ['type' =>  GraphQL::type('Intervention')],
                'locataire' => ['type' =>  GraphQL::type('Locataire')],
                'prestataire' => ['type' =>  GraphQL::type('Prestataire')],
                'user' => ['type' =>  GraphQL::type('User')],
                'intervention_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],
                'prestataire_id' => ['type' => Type::int()],
                'user_id' => ['type' => Type::int()],

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryCommentaireintervention($args);
        return $query->get();

    }
}
