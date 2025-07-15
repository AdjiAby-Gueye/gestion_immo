<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class AnnexesQuery extends Query
{
    protected $attributes = [
        'name' => 'annexes',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Annexe'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id(), 'description' => ''],
                'filename' => ['type' => Type::string(), 'description' => ''],
                'numero' => ['type' => Type::string()],
                'filepath' => ['type' => Type::string()],

                'contrat_id' => ['type' => Type::int()],
                

                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryAnnexe($args);
        return $query->get();

    }
}
