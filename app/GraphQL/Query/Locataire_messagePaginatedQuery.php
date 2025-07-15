<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class Locataire_messagePaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'locataire_messagespaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('locataire_messagespaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'locataire_id' => ['type' => Type::int(), 'description' => ''],
                'message_id' => ['type' => Type::int(), 'description' => ''],

                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryLocataire_message($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
