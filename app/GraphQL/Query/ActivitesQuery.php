<?php

namespace App\GraphQL\Query;

use App\Outil;
use App\Candidature;
use App\QueryModel;
use Carbon\Carbon;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ActivitesQuery extends Query{

    protected $attributes = [
        'name' => 'activites',
        'description' => ''
    ];

    public function type(): Type
    {
        // result of query with pagination laravel
        return Type::listOf(GraphQL::type('Activite'));

    }

    public function args():array{
        return [
            'id' => ['type' => Type::id(), 'description' => '' ],
            'designation' => ['type' => Type::string(), 'description' => '' ],
            'description' => ['type' => Type::string(), 'description' => ''],
        ];
    }
    public function resolve($root, $args){

        $query = QueryModel::getQueryActivite($args);
        return $query->get();
    }

}
