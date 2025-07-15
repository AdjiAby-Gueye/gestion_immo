<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class TaxesQuery extends Query{

    protected $attributes = [
        'name' => 'taxes',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Taxe'));

    }

    public function args():array{
        return [
            'id' => ['type' => Type::id(), 'description' => '' ],
            'designation' => ['type' => Type::string(), 'description' => '' ],
            'description' => ['type' => Type::string(), 'description' => ''],
            'valeur' => ['type' => Type::int(), 'description' => ''],
        ];
    }
    public function resolve($root, $args){

        $query = QueryModel::getQueryTaxe($args);
        return $query->get();
    }

}
