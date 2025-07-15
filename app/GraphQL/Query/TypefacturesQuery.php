<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class TypefacturesQuery extends Query
{
    protected $attributes = [
        'name' => 'typefactures',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Typefacture'));

    }

    // arguments to filter query
    public function args(): array
    {
        return
            [
                'id' => ['type' => Type::id()],
                'designation' => ['type' => Type::string()],
                'factures' => ['type' => Type::listOf(GraphQL::type('Facture'))],
                'facturelocations'=> ['type' => Type::listOf(GraphQL::type('Facturelocation'))],
                'order'          => ['type' => Type::string()],
                'direction'      => ['type' => Type::string()],
            ];
    }

    public function resolve($root, $args)
    {
        $query = QueryModel::getQueryTypefacture($args);
        return $query->get();

    }
}
