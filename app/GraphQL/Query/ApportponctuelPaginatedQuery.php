<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;


class ApportponctuelPaginatedQuery extends Query{

    protected $attributes = [
        'name' => 'apportponctuelspaginated',
        'description' => ''
    ];

    public function type():type{

        return GraphQL::type('Apportponctuelspaginated');
    }

    public function args():array{
        return [
            'id' => ['type' => Type::id(), 'description' => ''],
            'date' => ['type' => Type::string(), 'description' => ''],
            'montant' => ['type' => Type::int(), 'description' => ''],
            'contrat_id' => ['type' => Type::id(), 'description' => ''],
            'typeapportponctuel_id' => ['type' => Type::id(), 'description' => ''],
            'observations' => ['type' => Type::string(), 'description' => ''],

            'page' => ['type' => Type::int()],
            'count' => ['type' => Type::int()],
        ];
    }
    public function resolve($root, $args){

        $query = QueryModel::getQueryApportponctuel($args);
        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);

        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }

}
