<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class InboxsQuery extends Query
{
    protected $attributes = [
        'name' => 'inboxs',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Inbox'));

    }

    // arguments to filter query
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
               
                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryInbox($args);
        return $query->get();

    }
}
