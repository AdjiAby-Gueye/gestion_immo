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


class TypeapportponctuelpaginatedQuery extends Query{

    protected $attributes = [
        'name' => 'typeapportponctuelspaginated',
        'description' => ''
    ];

    public function type():type{

        return GraphQL::type('Typeapportponctuelspaginated');
    }

    public function args():array{
        return [
            'id' => ['type' => Type::id(), 'description' => '' ],
            'designation' => ['type' => Type::string(), 'description' => '' ],
            'description' => ['type' => Type::string(), 'description' => ''],

            'page' => ['type' => Type::int()],
            'count' => ['type' => Type::int()],
        ];
    }
    public function resolve($root, $args){
        $query = QueryModel::getQueryTypeapportponctuel($args);
        $count = Arr::get($args, 'count', 20);
        $page = Arr::get($args, 'page', 1);

        return $query->orderBy('id', 'desc')->paginate($count, ['*'], 'page', $page);
    }

}
