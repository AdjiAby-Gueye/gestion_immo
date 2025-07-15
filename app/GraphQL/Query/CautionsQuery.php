<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class CautionsQuery extends Query
{
    protected $attributes = [
        'name' => 'cautions',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Caution'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [

                'id' => ['type' => Type::id()],
                'document' => ['type' => Type::string()],
                'montantloyer' => ['type' => Type::string()],
                'montantcaution' => ['type' => Type::string()],
                'codeappartement' => ['type' => Type::string()],
                'dateversement' => ['type' => Type::int()],
                'datepaiement' => ['type' => Type::int()],
                'etat' => ['type' => Type::string()],
                'contrat_id' => ['type' => Type::string()],


                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],

            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryCaution($args);
        return $query->get();

    }
}
