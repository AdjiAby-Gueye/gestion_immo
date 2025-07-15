<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class MessagesQuery extends Query
{
    protected $attributes = [
        'name' => 'messages',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Message'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'objet' => ['type' => Type::string()],
                'contenu' => ['type' => Type::string()],

                'typedocument_id' => ['type' => Type::int()],

                'locataires' => ['type' => Type::listOf(GraphQL::type('Locataire'))],
                'proprietaires' => ['type' => Type::listOf(GraphQL::type('Proprietaire'))],
                'documents' => ['type' => Type::listOf(GraphQL::type('Document'))],
                'locataire_id'          => ['type' => Type::int()],



                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryMessage($args);
        return $query->get();

    }
}
