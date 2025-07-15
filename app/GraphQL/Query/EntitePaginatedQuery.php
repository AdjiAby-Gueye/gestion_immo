<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class EntitePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'entitespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('entitespaginated');
    }

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

                // / notaire
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

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryEntite($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
