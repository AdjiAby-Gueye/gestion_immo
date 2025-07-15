<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class DemandeinterventionPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'demandeinterventionspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('demandeinterventionspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'image' => ['type' => Type::string()],
                'typepiece' => ['type' =>  GraphQL::type('Typepiece')],
                'locataire_id' => ['type' => Type::int()],
                'appartement_id' => ['type' => Type::int()],
                'membreequipegestion_id' => ['type' => Type::int()],
                'immeuble_id' => ['type' => Type::int()],
                'devi' => ['type' => Type::int()],
                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryDemandeintervention($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
