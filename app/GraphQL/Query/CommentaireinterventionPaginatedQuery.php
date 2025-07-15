<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class CommentaireinterventionPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'commentaireinterventionspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('commentaireinterventionspaginated');
    }

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


                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryCommentaireintervention($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
