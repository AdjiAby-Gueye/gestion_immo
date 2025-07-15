<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ApportponctuelsQuery extends Query{

    protected $attributes = [
        'name' => 'apportponctuels',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Apportponctuel'));

    }

    public function args():array{
        return [
            'id' => ['type' => Type::id(), 'description' => ''],
            'date' => ['type' => Type::string(), 'description' => ''],
            'montant' => ['type' => Type::int(), 'description' => ''],
            'contrat_id' => ['type' => Type::id(), 'description' => ''],
            'typeapportponctuel_id' => ['type' => Type::id(), 'description' => ''],
            'observations' => ['type' => Type::string(), 'description' => '']
        ];
    }
    public function resolve($root, $args){

        $query = QueryModel::getQueryApportponctuel($args);
        return $query->get();
    }

}
