<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class InboxPaginatedQuery extends Query
{
    protected $attributes = [
        'name' => 'inboxspaginated'
    ];

    public function type(): Type
    {
        return GraphQL::type('inboxspaginated');
    }

    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'subject' => ['type' => Type::string()],
                'body' => ['type' => Type::string()],
                'sender_email' => ['type' => Type::string()],
                'user_id' => ['type' => Type::int()],
                'locataire_id' => ['type' => Type::int()],
                'appartement_id' => ['type' => Type::int()],


                'page' => ['type' => Type::int()],
                'count' => ['type' => Type::int()],

                'order'       => ['type' => Type::string()],
                'direction'   => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryInbox($args);

        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);


        return $query->orderBy('inboxs.id', 'desc')->paginate($count, ['*'], 'page', $page);
    }


}
