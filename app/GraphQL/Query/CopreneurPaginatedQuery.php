<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class CopreneurPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'copreneurspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('copreneurspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],

                'prenom' => ['type' => Type::string(), 'description' => ''],
                'nom' => ['type' => Type::string(), 'description' => ''],
                'adresse' => ['type' => Type::string(), 'description' => ''],
                'telephone1' => ['type' => Type::string(), 'description' => ''],
                'telephone2' => ['type' => Type::string(), 'description' => ''],
                'email' => ['type' => Type::string(), 'description' => ''],
                'profession' => ['type' => Type::string(), 'description' => ''],
                'cni' => ['type' => Type::string(), 'description' => ''],
                'passeport' => ['type' => Type::string(), 'description' => ''],

                'datenaissance' => ['type' => Type::string()],
                'lieunaissance' => ['type' => Type::string()],
                'pays' => ['type' => Type::string()],

                'ville' => ['type' => Type::string()],
                'situationfamiliale' => ['type' => Type::string()],
                'codepostal' => ['type' => Type::string()],
                'nationalite' => ['type' => Type::string()],
                'njf' => ['type' => Type::string()],

                'locataire_id' => ['type' => Type::int(), 'description' => ''],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryCopreneur($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('etat', 'asc')->paginate($count, ['*'], 'page', $page);
    }

}
